<?php
	class Gamification {

		private static function Rank($user, $total, $max) {
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
			return '<img src="/assets/images/badges/'.strtolower($rank).'.png" alt="'.$rank.'" />';
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