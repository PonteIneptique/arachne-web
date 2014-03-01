<?php

class elements {

	private static function DB() {
		global $DB;
		return $DB;
	}
}

class Words {
	private static function DB() {
		global $DB;
		return $DB;
	}
	static function LinkTo($word, $lemma) {
		#If not numeric
		if(!is_numeric($word) || !is_numeric($lemma)) {
			return false;
		}
		$query = self::DB()->prepare("
			INSERT INTO
				word_has_lemma
				(uid_word, uid_lemma)
			VALUES
				( ? , ? )
			");
		try {
			$query->execute(array($word, $lemma));
			return true;
		} catch (Exception $e) {
			return false;
		}
		
		
	}
	static function Get($options = array()) {
		$exec= array();
		$query = "
			SELECT 
				w.text_word AS word, 
				w.uid_word AS uid, 
				w.active as active,
				s.text_sentence as sentence, 
				s.uid_sentence as uid_sentence, 
				b.name_book as book, 
				b.url_book as url, 
				a.name_author as author,
				l.text_lemma as lemma
			FROM 
				word w
				LEFT OUTER JOIN word_has_lemma hl ON (hl.uid_word = w.uid_word)
				LEFT OUTER JOIN lemma l ON (l.uid_lemma = hl.uid_lemma),
				book b,
				sentence s,
				author a,
				word_has_phrase ws
			WHERE
				w.uid_word = ws.uid_word AND
				ws.uid_sentence = s.uid_sentence AND
				b.uid_book = s.uid_book AND
				a.uid_author = s.uid_author";
		/*
		*	Options
		*/
		if(isset($options["active_sentence"])) {
			$query.= " AND
				s.active = 1";
		}
		if(isset($options["uid_sentence"])) {
			$query.= " AND
				s.uid_sentence = ? ";
			$exec[] = $options["uid_sentence"];
		}
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		if($options["raw"]) {
			$d = array();
			foreach($data as &$val) {
				if(!$val["lemma"]) {
					$d[] = $val;
				}
			}
			$data = $d;
		}
		return $data;
	}
}
class Metadata {
	private static function DB() {
		global $DB;
		return $DB;
	}
	
	static function Connect($lemma, $items) {
		$clean = self::DB()->prepare("DELETE FROM lemma_has_metadata WHERE uid_lemma = ? ");
		$clean->execute(array($lemma));
		
		$query = self::DB()->prepare("INSERT INTO lemma_has_metadata (uid_lemma, uid_metadata) VALUES ( ? , ? )");
		foreach($items as $item) {
			$query->execute(array($lemma, $item));
		}
		return true;
	}
	
	static function Link($id) {
		$query = self::DB()->prepare("SELECT m.uid_metadata, m.uid_type_metadata FROM lemma_has_metadata lm, metadata m WHERE m.uid_metadata = lm.uid_metadata AND lm.uid_lemma = ?");
		$query->execute(array($id));
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	static function Get() {
		$query = self::DB()->prepare("
			SELECT
				m.label_metadata metadata,
				m.uid_metadata uid,
				t.label_type_metadata type_metadata,
				t.uid_type_metadats type_uid
			FROM
				metadata m,
				type_metadata t
			WHERE
				m.uid_type_metadata = t.uid_type_metadats
			ORDER BY t.label_type_metadata
		");
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	static function Type() {
		$query = self::DB()->prepare("
			SELECT
				t.uid_type_metadats uid,
				t.label_type_metadata label,
				t.varchar_type_metadata var
			FROM
				type_metadata t
		");
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	static function TypeInsert($val) {
		$query = self::DB()->prepare("
			INSERT INTO
				type_metadata
			(label_type_metadata, varchar_type_metadata)
			VALUES
				( ? , ? )");
		$query->execute(array($val, $val));
		return self::DB()->lastInsertId();
	}
	static function Insert($val, $type) {
		$query = self::DB()->prepare("
			INSERT INTO
				metadata
			(label_metadata, uid_type_metadata)
			VALUES
				( ? , ? )");
		$query->execute(array($val, $type));
		return self::DB()->lastInsertId();
	}
}
?>
