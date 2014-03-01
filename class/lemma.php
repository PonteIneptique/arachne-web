<?php
class Lemma {
	private static function DB() {
		global $DB;
		return $DB;
	}

	static function Insert($val) {
		$query = self::DB()->prepare("
			INSERT INTO 
			
				lemma 
				
				(text_lemma) 
				
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
	
	static function Get($options = array("count" => true), $name = "lemma") {
		$exec= array();
		$query = "
			SELECT
				l.text_lemma as ".$name.",
				l.id_lemma as uid";
		if($options["count"]) {
		$query .= ",
				COUNT(wl.uid_word) count
				";
		}
		$query .= "
			FROM 
				lemma l
				";
		if($options["count"]) {
		$query .= "
				LEFT JOIN lemma_has_form wl ON wl.id_lemma = l.id_lemma
				";
		} if($options["query"]) {
		$query .= "
			WHERE
				l.text_lemma LIKE ?
				";
				$exec[] = "%".$options["query"]."%";
		}
		$query .= "
			GROUP BY l.id_lemma
			;";
		/*
		*	Options
		*/
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	static function Weight($options = array(), $name = "lemma") {
		$exec= array();
		$query = "
			SELECT
				l.id_lemma as uid,
				l.text_lemma as ".$name.",
				count(s.uid_sentence) weight
			FROM 
				lemma l,
				lemma_as_form lf,
				sentence s
			WHERE
				l.id_lemma = lf.id_lemma AND
				lf.id_sentence = s.id_sentence
			GROUP BY  l.id_lemma
			;";
		/*
		*	Options
		*/
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	static function Links($options = array("group" => true)) {
		$exec= array();
		$query = "
			SELECT
				l.id_lemma as lemma,
				s.uid_author as author,
				count(s.uid_sentence) weight
			FROM 
				lemma l,
				word_has_lemma wl,
				word_has_phrase wp,
				sentence s
			WHERE
				l.uid_lemma = wl.uid_lemma AND
				wl.uid_word = wp.uid_word AND
				wp.uid_sentence = s.uid_sentence AND 
				s.active = 1
				";
		$query .= "
			GROUP BY  l.uid_lemma
				;";
		/*
		*	Options
		*/
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	static function Sentences($options = array()) {
		$exec= array();
		$query = "
			SELECT 
				wl.id_lemma,
				s.uid_sentence,
				s.text_sentence
			FROM
				sentence s,
				form_has_lemma fl
			WHERE 
				s.id_sentence = fl.id_sentence
			ORDER BY 
				id_sentence";
		/*
		*	Options
		*/
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
}
?>
