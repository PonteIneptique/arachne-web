<?php
class Author {
	private static function DB() {
		global $DB;
		return $DB;
	}
	static function Get($options = array()) {
		$exec= array();
		$query = "
			SELECT
			s.uid_author as uid,
			a.name_author as label,
			count(s.uid_sentence) weight
		FROM 
			lemma l,
			word_has_lemma wl,
			word_has_phrase wp,
			sentence s,
			author a
		WHERE
			l.uid_lemma = wl.uid_lemma AND
			wl.uid_word = wp.uid_word AND
			wp.uid_sentence = s.uid_sentence AND
			s.active = 1 AND
			a.uid_author = s.uid_author
		GROUP BY  s.uid_author
		;";
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