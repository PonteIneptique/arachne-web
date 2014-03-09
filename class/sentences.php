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
				s.id_sentence as uid,
				s.id_document as document
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

		$sentence = preg_replace("(\\w+)", "<a class='sentence-lemma neutral' title='$0' data-id='0'>$0</a>", $sentence);
		foreach($forms as $id => $form) {
			$sentence = str_replace("<a class='sentence-lemma neutral' title='".$form["text_form"]."' data-id='0'>".$form["text_form"]."</a>", self::href($form["text_form"], $form["id_form"], $form["count_lemma"]), $sentence);
		}

		return $sentence;
	}

	static function Lemma($sentence, $form) {
		$exec = array($sentence, $form);
		$query = "
            SELECT
				l.id_lemma,
				l.text_lemma,
				lf.id_lemma_has_form,
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

	static function Metadata($document) {
		$exec = array($document);
		$query = "SELECT key_name, value FROM metadata WHERE document_id = ?";
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$data = $query->fetchall(PDO::FETCH_ASSOC);
		$r = array();

		foreach( $data as $index => $value ) {
			$r[$value["key_name"]] = $value["value"];
		}

		return $r;
	}

	static function All($group = False) {
		$exec = array();
		$query = "
			SELECT
			    s.id_sentence,
			    s.id_document,
			    s.text_sentence,
			    mOne.value as book,
			    COALESCE(mTwo.value, 'Unknown') as author,
			    COALESCE(count_votes, 0) as votes,
			    COALESCE(count_annotation, 0) as annotations
			FROM 
			    sentence s
			    LEFT JOIN metadata mOne ON (mOne.key_name = 'DC:Title' AND mOne.document_id = s.id_document)
			    LEFT JOIN metadata mTwo ON (mTwo.key_name = 'DC:Creator' AND mTwo.document_id = s.id_document)
			    LEFT JOIN (
			        SELECT 
			            id_target_annotation,
			            COUNT(*) as count_annotation 
			        FROM annotation a 
			        WHERE a.table_target_annotation='sentence'
			        ) anno ON (anno.id_target_annotation = s.id_sentence)
			    LEFT JOIN (
			    	SELECT 
			    		lf.id_sentence, 
			    		COUNT(*) as count_votes 
			    	FROM form_vote fv, lemma_has_form lf 
			    	WHERE fv.id_lemma_has_form = lf.id_lemma_has_form GROUP BY lf.id_sentence
			    	) vtes ON (vtes.id_sentence = s.id_sentence)
				";
		if($group) { $query .= " GROUP BY s.id_document "; }
		$query .=
			" ORDER BY
				author,
				book
			";
		$query = self::DB()->prepare($query);
		$query->execute($exec);

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	static function NewForm($lemma, $form, $sentence) {

		if(count(self::Get($sentence)) == 0) {
			return false;
		}

		$id_lemma = Lemma::Get(false, $options = array("query" => $lemma, "strict" => true));
		if(count($id_lemma) == 0) { $id_lemma = Lemma::Insert($lemma); } else { $id_lemma = $id_lemma["uid"]; }

		$id_form = Forms::Get(false, $options = array("query" => $form, "strict" => true));
		if(count($id_form) == 0) { $id_form = Forms::Insert($form); } else { $id_form = $id_form["id_form"]; }


		$exec = array("id_sentence" => $sentence, "id_form" => $id_form, "id_lemma" => $id_lemma);
		$query = "
			INSERT INTO
				lemma_has_form
			(id_sentence, id_form, id_lemma)
			VALUES
			(:id_sentence, :id_form, :id_lemma)
		";
		$query = self::DB()->prepare($query);
		$query->execute($exec);

		return array("lemma" => $id_lemma, "form" => $id_form, "sentence" => $sentence, "id_lemma_has_form" => self::DB()->lastInsertId());
	}
}