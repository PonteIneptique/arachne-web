<?php
	class Gamification {
	 
		/**
		 *	Get the DB in a PDO way, can be called through self::DB()->PdoFunctions
		 * @return PDO php object
		 */
		private static function DB() {
			global $DB;
			return $DB;
		}


		public static function Progress() {
			$return = array();

			$query = self::DB()->prepare("SELECT COUNT(*) as forms FROM form");
			$query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$return["forms"] = $data["forms"];

			$query = self::DB()->prepare("SELECT COUNT(*) as lemma_query FROM lemma WHERE query_lemma = 1");
			$query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$return["lemma_query"] = $data["lemma_query"];

			$query = self::DB()->prepare("SELECT COUNT(*) as lemmas FROM lemma WHERE query_lemma = 0");
			$query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$return["lemmas"] = $data["lemmas"];

			$query = self::DB()->prepare("SELECT COUNT(*) as sentences FROM sentence");
			$query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$return["sentences"] = $data["sentences"];


			$query = self::DB()->prepare("SELECT COUNT(*) as votes FROM annotation_vote;");
			$query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$return["votes"] = $data["votes"];

			$query = self::DB()->prepare("SELECT COUNT(*) as form_votes FROM form_vote WHERE id_user != 0;");
			$query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$return["form_votes"] = $data["form_votes"];

			$query = self::DB()->prepare("SELECT COUNT(*) as annotation FROM annotation;");
			$query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$return["annotation"] = $data["annotation"];

			//10% missed lemma and forms
			//2 Annotations / sentence or lemma
			//2 Votes / form or annotation
			$return["target"] = (($return["lemmas"] + $return["forms"]) * 1.1 + $return["sentences"])*3*2;
			$return["done"] = $return["annotation"] + $return["form_votes"] + $return["votes"];
			$return["percent"] = floor($return["done"] / $return["target"] * 100);

			return $return;

		}

		public static function Rank($user, $total, $max) {
			if ((($user == $max) || ($user > 0.9*$total)) && ($user > 5)){
				return "Consul";
			} elseif (($user > 0.75*$total) && ($total > 5)){
				return "Tribune";
			} elseif (($user > 0.50*$total) && ($total > 5)){
				return "Aedile";
			} elseif (($user > 0.25*$total) && ($total > 5)){
				return "Rhetor";
			} elseif ($user > 0){
				return "Citizen";
			} else {
				return "Student";
			}
		}
		public static function Image($user, $total, $max) {
			$rank = self::Rank($user, $total, $max);
			return '<img src="/assets/images/badges/'.strtolower($rank).'.png" title="'.$rank.'" alt="'.$rank.'" />';
		}
		public static function Message($user, $total, $max) {
			$rank = self::Rank($user, $total, $max);
			if ($rank == "Consul"){

				return "You are a Consul here ! Well done !";

			} elseif ($rank == "Tribune"){

				return "You are a Tribune here ! Not so far from the top of the Cursus Honorum !";

			} elseif ($rank == "Aedile"){

				return "You are a Aedile here ! You got elected for your work here. Want to be Tribune ? Work harder !";

			} elseif ($rank == "Rhetor"){

				return "You are a young Rhetor here ! Now that you are a Rhetor, let's try to be elected as Aedile";

			} elseif ($rank == "Citizen"){

				return "You are a Citizen here !  Let's try to work enough to be a Rhetor";

			} else {

				return "You are only a student here. Maybe we should do something about that... Like a vote or an annotation ?";

			}
		}
	}
?>