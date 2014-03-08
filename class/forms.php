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
			return self::DB()->lastInsertId();
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

	static function Vote($id_lemma_has_form, $value, $user = 0) {
		$exec= array("id_lemma_has_form" => $id_lemma_has_form, "id_user" => $user, "value" => $value);
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
		$query = self::DB()->prepare($query);
		 $query->execute($exec);
		if($query->rowCount() == 1) {
			return true;
		} else {
			return false;
		}
	}
}

?>