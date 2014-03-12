<?php
class Annotations {
	private static function DB() {
		global $DB;
		return $DB;
	}
	static function Available($target = false, $format = false) {
		$exec= array();
		$where = array();
		$where[] = "at.id_annotation_type = av.id_annotation_type";
		if(gettype ($target) == "string") {
			$exec["target"] = $target;
			$where[] = "at.target_annotation_type = :target";
		}
		$query = "
		SELECT
			av.id_annotation_value as id_value,
			av.legend_annotation_value as text_value,
			at.legend_annotation_type as text_type,
			at.id_annotation_type as id_type
		FROM 
			annotation_value av,
			annotation_type at
		WHERE
			".implode(" AND ", $where)."
		ORDER BY
			at.id_annotation_type
		;";
		/*
		*	Options
		*/
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$data = $query->fetchAll(PDO::FETCH_ASSOC);

		if($format == false && (gettype($target) == "string" || $target = false)) {
			return $data;
		}
		$returned = array();
		$actual = false;
		if(count($data) >= 1) {
			foreach ($data as $key => $value) {
				if(!isset($returned[$value["id_type"]])) {
					$returned[$value["id_type"]] = array("id" => $value["id_type"], "text" => $value["text_type"], "options" => array());
				}
				$returned[$value["id_type"]]["options"][] = array("id" => $value["id_value"], "text" => $value["text_value"]);
			}
		}
		return $returned;
	}

	static function Get($target, $id) {
		$exec= array();
		$where = array();
		$where[] = "a.id_annotation_type = at.id_annotation_type";
		$where[] = "a.id_annotation_value = av.id_annotation_value";
		if($target) {
			$exec["target"] = $target;
			$exec["id"] = $id;
			$where[] = "a.table_target_annotation = :target";
			$where[] = "a.id_target_annotation = :id";
		}
		$query = "
		SELECT
			av.id_annotation_value as id_value,
			av.legend_annotation_value as text_value,
			at.legend_annotation_type as text_type,
			at.id_annotation_type as id_type,
			a.id_annotation as id_annotation,
			COALESCE(SUM(votes.value), 0) as votes
		FROM 
			annotation_value av,
			annotation_type at,
			annotation a
			LEFT JOIN annotation_vote votes ON (a.id_annotation = votes.id_annotation)
		WHERE
			".implode(" AND ", $where)."
		GROUP BY
			a.id_annotation
		ORDER BY
			at.id_annotation_type
		;";

		/*
		*	Options
		*/
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}

	static function Exists($target, $id, $type, $value) {
		$exec = array("table" => $target, "id" => $id, "type" => $type, "value" => $value);
		$query = "
			SELECT 
				id_annotation
			FROM
				annotation
			WHERE
				id_annotation_type = :type AND 
				id_annotation_value = :value AND 
				table_target_annotation = :table AND 
				id_target_annotation = :id
			LIMIT 1
		";
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		if($query->rowCount() == 1) {
			$data = $query->fetch(PDO::FETCH_ASSOC);
			return $data["id_annotation"];
		} else {
			return false;
		}
	}

	static function Vote($target, $user = 0, $vote) {
		$id_vote = self::VoteExists($target, $user);
		if($id_vote) {
			$exec = array("id_vote" => $id_vote, "vote" => $vote);
			$query = "
				UPDATE
					annotation_vote
				SET
					value = :vote
				WHERE
					id_annotation_vote = :id_vote
				LIMIT 1
			";
		} else {
			$exec = array("target" => $target, "user" => $user, "vote" => $vote);
			$query = "
				INSERT INTO
					annotation_vote
				(
					id_annotation,
					id_user,
					value
				)
				VALUES
				(
					:target,
					:user,
					:vote
				)
			";
		}
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$rows = $query->rowCount();
		if($rows == 1 || $rows == 2) {
			return true;
		} else {
			return false;
		}
	}

	static function VoteExists($target, $user) {
		$exec = array("id_annotation" => $target, "user" => $user);
		$query = "
			SELECT 
				id_annotation
			FROM
				annotation_vote
			WHERE
				id_annotation = :id_annotation AND
				id_user = :user
			LIMIT 1
		";

		$query = self::DB()->prepare($query);
		$query->execute($exec);
		if($query->rowCount() == 1) {
			$data = $query->fetch(PDO::FETCH_ASSOC);
			return $data["id_annotation"];
		} else {
			return false;
		}

	}

	static function Insert($target, $id, $type, $value, $user = 0) {
		$id_exist = self::Exists($target, $id, $type, $value);
		if($id_exist) {
			return self::Vote($id_exist, $user, 1);
		}
		$exec= array("table" => $target, "id" => $id, "type" => $type, "value" => $value, "user" => $user);
		$query = "
			INSERT INTO `clotho_web`.`annotation`
			(
				`id_annotation_type`,
				`id_annotation_value`,
				`id_user`,
				`table_target_annotation`,
				`id_target_annotation`
			)
			VALUES
			(
				:type ,
				:value ,
				:user ,
				:table,
				:id
			);
		";
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		if($query->rowCount() == 1) {
			return true;
		}
		return false;
		/**/
	}
}
?>