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
			$id = self::DB()->lastInsertId();

			Logs::Save("lemma", $id, "new", $_SESSION["user"]["id"]);

			return $id;
		} catch (Exception $e) {
			return false;
		}
	}
	
	static function Get($lemmaid = false, $options = array("query" => false, "strict" => false, "count" => true), $name = "lemma") {
		$exec= array();
		$where = array();
		if($lemmaid) { 
			$exec["lemmaId"] = $lemmaid; 
			$where[] = " l.id_lemma = :lemmaId ";
		}
		if($options["query"]) {
			$exec["query"] = $options["query"];
			if(is_numeric($exec["query"]) || $exec["query"] == "-1" || $exec["query"] == -1) {
				$where[] = " l.id_lemma = :query ";
			} elseif(isset($options["strict"]) && $options["strict"]) {
				$where[] = " l.text_lemma = :query ";
			} else {
				$exec["query"] = $exec["query"]."%";
				$where[] = " l.text_lemma LIKE :query ";
			}
		}
		$query = "
			SELECT
				l.text_lemma as ".$name.",
				l.id_lemma as uid
			FROM 
				lemma l
				";

		if(count($where) > 0) {
			$query .= " WHERE " . implode(" AND ", $where) . " ";
		}

		$query .= "
			GROUP BY l.id_lemma
			";
		if(isset($options["list"])) {
			$query .= "
				LIMIT 5
				";
		}

		$query = self::DB()->prepare($query);
		$query->execute($exec);

		$data = array();

		if($query->rowCount() == 1 && !isset($options["list"])) {
			$data = $query->fetch(PDO::FETCH_ASSOC);
		} elseif($query->rowCount() > 1 || isset($options["list"])) {
			$data = $query->fetchAll(PDO::FETCH_ASSOC);
		}

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
		/*
		*	Options
		*/
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	static function All($lemma = false, $form = false)  {
		$exec= array();
		$select = array("COUNT(DISTINCT lf.id_form) as forms", "COUNT(DISTINCT lf.id_sentence) as sentences", "COUNT(DISTINCT a.id_annotation) as annotations");
		$where = array("l.id_lemma = lf.id_lemma", "l.id_lemma != -1");
		$table = array(
				"lemma l"
				);
		$groupby = "l.id_lemma";


		$select[] = "COALESCE(value, 0) as votes";
		$table[] = "
			lemma_has_form lf
			LEFT JOIN 
				(
					SELECT 
						id_lemma_has_form, SUM(value) as value
				    FROM
						form_vote
				GROUP BY id_lemma_has_form
				) fv 
				ON (fv.id_lemma_has_form = lf.id_lemma_has_form)

			LEFT JOIN 
				annotation a 
				ON (a.id_target_annotation = lf.id_lemma AND a.table_target_annotation = 'lemma')
		";

		if($lemma) { 
			$exec["idLemma"] = $lemma;
			if($form) {
				$table[] = "form f";

				$select[] = "f.text_form";

				$where[] = "lf.id_lemma = :idLemma";
				$where[] = "lf.id_form = f.id_form";

				$groupby = "f.text_form , l.id_lemma";
			} else {
				$where[] = "(lf.id_lemma = :idLemma OR lf.id_sentence = links.id_sentence)";
				$table[] = "(SELECT id_sentence FROM lemma_has_form WHERE id_lemma = :idLemma) links";
			}
		}

		$query = "
			SELECT
				l.id_lemma,
				l.text_lemma,
				".implode(", ", $select)."
			FROM 
				" . implode(" , ", $table) . "
				
			WHERE " . implode(" AND ", $where) . "			    
			GROUP BY
				".$groupby."
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
