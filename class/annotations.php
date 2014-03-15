<?php
class Annotations {
	private static function DB() {
		global $DB;
		return $DB;
	}
	static function Available($target = false, $format = false, $notnull = false) {
		$exec= array();
		$where = array();
		if(gettype ($target) == "string") {
			$exec["target"] = $target;
			$where[] = "at.target_annotation_type = :target";
		}
		$query = "
		SELECT
			av.id_annotation_value as id_value,
			av.legend_annotation_value as text_value,
			at.legend_annotation_type as text_type,
			at.id_annotation_type as id_type,
			at.target_annotation_type as target_type
		FROM 
			annotation_type at
		";
		if($notnull == true) {
			$query .= "LEFT JOIN annotation_value av ON (at.id_annotation_type = av.id_annotation_type)";
		} else {
			$query .= ", annotation_value av ";
			$where[] .= " at.id_annotation_type = av.id_annotation_type ";
		}
		
		if(count($where) > 0) {
			$query .= "
			WHERE
				".implode(" AND ", $where)."
			";
		}
		$query .=
		"
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
					$returned[$value["id_type"]] = array("id" => $value["id_type"], "target" => $value["target_type"], "text" => $value["text_type"], "options" => array());
				}
				if($value["id_value"] != null) {
					$returned[$value["id_type"]]["options"][] = array("id" => $value["id_value"], "text" => $value["text_value"]);
				}
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

	static function Vote($target, $user, $vote) {
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
				id_annotation_vote
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
			return $data["id_annotation_vote"];
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

	static function TypeExists($name, $target) {
		$exec = array("name" => $name, "target" => $target);
		$query = "
			SELECT
				id_annotation_type
			FROM
				annotation_type
			WHERE
				text_annotation_type = :name AND
				target_annotation_type = :target
			LIMIT 1
		";
		$query = self::DB()->prepare($query);
		$query->execute($exec);

		if($query->rowCount() == 1) {
			return true;
		}
		return false;

	}

	static function TypeNew($name, $target, $user) {
		if(self::TypeExists($name, $target)) {
			return false;
		}
		switch($target) {
			case "lemma":
			case "sentence":
				continue;
				break;
			default:
				return false;
		}
		$exec = array("name" => $name, "target" => $target);
		$query = "
			INSERT INTO `clotho_web`.`annotation_type`
				(`text_annotation_type`,
				`legend_annotation_type`,
				`target_annotation_type`)
			VALUES
			(
				:name ,
				:name ,
				:target
			);
		";
		$query = self::DB()->prepare($query);
		$query->execute($exec);

		if($query->rowCount() == 1) {
			return self::DB()->lastInsertId();
		}
		return false;

	}

	static function ValueExists($name, $type) {
		$exec = array("name" => $name, "type" => $type);
		$query = "
			SELECT
				id_annotation_value
			FROM
				annotation_value
			WHERE
				text_annotation_value = :name AND
				id_annotation_type = :type
			LIMIT 1
		";
		$query = self::DB()->prepare($query);
		$query->execute($exec);

		if($query->rowCount() == 1) {
			return true;
		}
		return false;

	}

	static function ValueNew($name, $type, $user) {
		if(self::ValueExists($name, $type)) {
			return false;
		}
		$exec = array("name" => $name, "type" => $type);
		$query = "
			INSERT INTO 
				`annotation_value`
			(
				`text_annotation_value`,
				`legend_annotation_value`,
				`id_annotation_type`
			)
				VALUES
			(
				:name,
				:name,
				:type
			);

		";
		$query = self::DB()->prepare($query);
		$query->execute($exec);

		if($query->rowCount() == 1) {
			return self::DB()->lastInsertId();
		}
		return false;

	}
}
?>