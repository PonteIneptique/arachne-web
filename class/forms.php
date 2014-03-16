<?php
class Forms {
	private static function DB() {
		global $DB;
		return $DB;
	}

	static function Insert($val) {
		$query = self::DB()->prepare("
			INSERT INTO 
			
				form 
				
				(text_form) 
				
			VALUES 
				
				( ? )
		");
		try {
			$query->execute(array($val));
			$id = self::DB()->lastInsertId();

			Logs::Save("form", $id, "new", $_SESSION["user"]["id"]);


			return $id;
		} catch (Exception $e) {
			return false;
		}
	}
	
	static function Get($formId = false, $options = array("query" => false, "strict" => false, "count" => true), $name = "lemma") {
		$exec= array();
		$where = array();
		if($formId) { 
			$exec["formId"] = $formId; 
			$where[] = " f.id_form = :formId ";
		}
		if($options["query"]) {
			$exec["query"] = $options["query"];
			if(is_numeric($exec["query"])) {
				$where[] = " f.id_form = :query ";
			}
			elseif($options["strict"]) {
				$where[] = " f.text_form = :query ";
			}
			else {
				$exec["query"] = "%".$exec["query"]."%";
				$where[] = " f.text_form LIKE :query ";
			}
		}
		$query = "
			SELECT
				f.text_form,
				f.id_form
			FROM 
				form f
			";

		if(count($where) > 0) {
			$query .= " WHERE " . implode(" AND ", $where) . " ";
		}

		$query = self::DB()->prepare($query);
		$query->execute($exec);

		if($query->rowCount() == 1) {
			$data = $query->fetch(PDO::FETCH_ASSOC);
		} else {
			$data = $query->fetchAll(PDO::FETCH_ASSOC);
		}
		
		return $data;
	}

	static function LemmaHasForm($id_lemma, $id_form, $id_sentence) {
		$exec = array(
			"id_lemma" => $id_lemma,
			"id_form" => $id_form,
			"id_sentence" => $id_sentence,
		);
		$query = "
			SELECT 
				id_lemma_has_form
			FROM 
				lemma_has_form
			WHERE
				id_lemma = :id_lemma
				AND id_sentence = :id_sentence
				AND id_form = :id_form
			LIMIT 1
		";
		$query = self::DB()->prepare($query);
		$query->execute($exec);

		$data = $query->fetch(PDO::FETCH_ASSOC);
		if(count($data) > 0) {
			return $data["id_lemma_has_form"];
		} else {
			return false;
		}
	}
	
	static function VoteExists($id_lemma_has_form, $user) {
		$exec = array("id_lemma_has_form" => $id_lemma_has_form, "user" => $user);
		$query = "
			SELECT 
				id_form_vote
			FROM
				form_vote
			WHERE
				id_lemma_has_form = :id_lemma_has_form AND
				id_user = :user
			LIMIT 1
		";

		$query = self::DB()->prepare($query);
		$query->execute($exec);
		if($query->rowCount() == 1) {
			$data = $query->fetch(PDO::FETCH_ASSOC);
			return $data["id_form_vote"];
		} else {
			return false;
		}

	}

	static function Vote($id_lemma_has_form, $value, $user = 0) {
		$id_form_vote = self::VoteExists($id_lemma_has_form, $user);
		if($id_form_vote != false) {
			$exec= array("id_form_vote" => $id_form_vote, "id_user" => $_SESSION["user"]["id"], "value" => $value);
			$query = "
				UPDATE
					form_vote
				SET
					value = :value
				WHERE
					id_user = :id_user AND
					id_form_vote = :id_form_vote
				LIMIT 1
			";
			$upd = 1;
		} else {
			$exec= array("id_lemma_has_form" => $id_lemma_has_form, "id_user" => $_SESSION["user"]["id"], "value" => $value);
			$query = "
				INSERT INTO `form_vote`
				(
				`id_lemma_has_form`,
				`id_user`,
				`value`)
				VALUES
				(
				:id_lemma_has_form,
				:id_user,
				:value
				)
			";
			$upd = 0;
		}
		$query = self::DB()->prepare($query);
		 $query->execute($exec);
		if($query->rowCount() == 1) {
			Logs::Save("lemma_has_form", $id_lemma_has_form, "vote", $_SESSION["user"]["id"], $upd);
			return true;
		} else {
			return false;
		}
	}
}

?>