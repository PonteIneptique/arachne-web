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
		/*
		if($options["count"]) {
		$query .= ",
				COUNT(lf.id_form) count
				";
		}
		*/
		$query .= "
			FROM 
				lemma l
				";
		/*
		if($options["count"]) {
		$query .= "
				LEFT JOIN lemma_has_form lf ON lf.id_lemma = lf.id_lemma
				";
		} 
		*/
		if(isset($options["query"])) {
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

	static function MaxMin() {
		$exec= array();
		$query = "
			SELECT 
			    Max(counted) as maximum,
			    Min(counted) as minimum
			FROM 
			    (
			        SELECT 
			        COUNT(id_sentence) as counted
			        FROM
			        lemma_has_form
			         GROUP BY
			        id_lemma
			    )
			    as counts
			";
		/*
		*	Options
		*/
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$data = $query->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	static function RelativeWeight($weight, $min, $max, $baseMax = 50, $baseMin = 2) {
		$weight = ($weight / $max) * $baseMax;
		if($weight < $baseMin) { $weight = $baseMin; }
		if($weight > $baseMax) { $weight = $baseMax; }
		return strval($weight);
	}

	static function RelativeColor($weight, $min, $max, $baseMax = 100, $baseMin = 20) {
		$weight = ($weight / $max) * $baseMax;
		if($weight < $baseMin) { $weight = $baseMin; }
		if($weight > $baseMax) { $weight = $baseMax; }
		return "rgb(50,50,".intval($weight).")";
	}


	static function Links($options = array("group" => true)) {
		$exec= array();
		$query = "
			SELECT 
			    lfOne.id_lemma as source, 
			    lfTwo.id_lemma as target, 
			    COUNT(lfOne.id_sentence) as weight
			FROM 
			    lemma_has_form lfOne, lemma_has_form lfTwo
			WHERE
			    lfOne.id_sentence = lfTwo.id_sentence AND
			    lfOne.id_form != lfTwo.id_form AND
			    lfOne.id_lemma != lfTwo.id_lemma
			GROUP BY
				lfOne.id_lemma, lfTwo.id_lemma
			";
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
				fl.id_lemma,
				s.id_sentence,
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
	static function All() {
		$exec= array();
		$query = "
			SELECT
				l.id_lemma,
				l.text_lemma,
				COUNT(lf.id_form) as sentences,
				COUNT(lf.id_sentence) as forms,
				COALESCE(SUM(fv.value), 0) as votes
			FROM 
				lemma l,
				lemma_has_form lf
				LEFT OUTER JOIN form_vote fv ON (fv.id_lemma_has_form = lf.id_lemma_has_form)
			WHERE
				l.id_lemma = lf.id_lemma
			GROUP BY
				l.id_lemma
		";
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
