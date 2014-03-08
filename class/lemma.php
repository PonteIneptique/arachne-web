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
	
	static function Get($lemmaid = false, $options = array("count" => true), $name = "lemma") {
		$exec= array();
		if($lemmaid) { $exec["lemmaId"] = $lemmaid; }
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
		if($lemmaid) {
			$query .= " WHERE l.id_lemma = :lemmaId ";
		}
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
		$data = $query->fetch(PDO::FETCH_ASSOC);
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

	static function MaxMin($lemma = False) {
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


	static function Links($lemma = False,$options = array("group" => true)) {
		$exec= array();
		$where = array("lfOne.id_sentence = lfTwo.id_sentence AND
			    lfOne.id_form != lfTwo.id_form AND
			    lfOne.id_lemma != lfTwo.id_lemma");
		if($lemma) {
			$exec["idLemma"] = $lemma;
			$where[] = "(lfOne.id_lemma = :idLemma OR lfTwo.id_lemma = :idLemma)";
		}
		$query = "
			SELECT 
			    lfOne.id_lemma as source, 
			    lfTwo.id_lemma as target, 
			    COUNT(lfOne.id_sentence) as weight
			FROM 
			    lemma_has_form lfOne, lemma_has_form lfTwo
			WHERE
				" . implode(" AND ", $where) . "
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
	static function Sentences($lemma = false, $options = array()) {
		$exec= array();
		$where = array("s.id_sentence = lf.id_sentence");
		if($lemma) {
			$exec["idLemma"] = $lemma;
			$where[] = "lf.id_lemma = :idLemma";
		}
		$query = "
			SELECT 
				lf.id_lemma,
				s.id_sentence,
				s.text_sentence
			FROM
				sentence s,
				lemma_has_form lf
			WHERE 
				" . implode(" AND ", $where) . "
			ORDER BY 
				s.id_sentence";
				print $query;
		/*
		*	Options
		*/
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	static function All($lemma = false)  {
		$exec= array();
		$where = array("l.id_lemma = lf.id_lemma");
		$table = array(
				"lemma_has_form lf
				LEFT OUTER JOIN form_vote fv ON (fv.id_lemma_has_form = lf.id_lemma_has_form)",
				"lemma l");

		if($lemma) { 
			$exec["idLemma"] = $lemma;
			$where[] = "(lf.id_lemma = :idLemma OR lf.id_sentence = links.id_sentence)";
			$table[] = "(SELECT id_sentence FROM lemma_has_form WHERE id_lemma = :idLemma) links";
		}

		$query = "
			SELECT
				l.id_lemma,
				l.text_lemma,
				COUNT(lf.id_form) as sentences,
				COUNT(lf.id_sentence) as forms,
				COALESCE(SUM(fv.value), 0) as votes
			FROM 
				" . implode(" , ", $table) . "
				
			WHERE " . implode(" AND ", $where) . "			    
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
