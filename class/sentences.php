<?php
class Sentence {
	private static function DB() {
		global $DB;
		return $DB;
	}

	static function Get($id = false) {
		$exec = array();
		$query = "
			SELECT 
				s.text_sentence as sentence, 
				s.id_sentence as uid
			FROM 
				sentence s";
		if(is_numeric($id)) {
			$query .= " WHERE 
				s.id_sentence = ?
			";
			$exec[] = $id;
		}
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$data = $query->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	static function Forms($id) {
		$exec = array($id);
		$query = "
			SELECT
				lf.id_form,
				lf.id_lemma_has_form,
				f.text_form,
				COUNT(id_lemma) as count_lemma
			FROM
				form f,
				lemma_has_form lf
			WHERE 
				lf.id_sentence = ?
				AND f.id_form = lf.id_form
			GROUP BY id_form
		";
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}

	static private function href($form, $id_form, $count) {
		//Color switch
		if($count == 0 || $count > 1) {
			$class = "red";
		} else {
			$class = "green";
		}

		$form = '<a href="#" title="' . $form . '" class="sentence-lemma ' . $class . '" data-id="' . $id_form . '">' . $form . '</a>';
		return $form;

	}

	static function Process($sentence, $forms) {
		$sentence = $sentence["sentence"];

		foreach($forms as $id => $form) {
			$sentence = str_replace($form["text_form"], self::href($form["text_form"], $form["id_form"], $form["count_lemma"]), $sentence);
		}

		return $sentence;
	}

	static function Lemma($sentence, $form) {
		$exec = array($sentence, $form);
		$query = "
            SELECT
				l.id_lemma,
				l.text_lemma,
				COALESCE(SUM(fv.value), 0) as votes
			FROM
				lemma l,
				lemma_has_form lf
				LEFT OUTER JOIN form_vote fv ON (fv.id_lemma_has_form = lf.id_lemma_has_form)
			WHERE 
				lf.id_sentence = ?
				AND lf.id_form = ?
                AND l.id_lemma = lf.id_lemma
                GROUP BY lf.id_lemma
            ORDER BY votes DESC
			";
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
}