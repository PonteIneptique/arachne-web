<?php
	class Logs {
	/**
	 * Log class
	 *	Retrieve and save actions of user
	 *
	 */
	 
		/**
		 *	Get the DB in a PDO way, can be called through self::DB()->PdoFunctions
		 * @return PDO php object
		 */
		private static function DB() {
			global $DB;
			return $DB;
		}


		public static function LastSentence() {
			$exec = array($_SESSION["user"]["id"]);
			$query = "
			SELECT 
				table_log, target_log
			FROM
				log
			WHERE 
				id_user = ? AND
				(table_log = 'lemma_has_form' OR table_log = 'sentence')	
			ORDER BY time_log DESC
			LIMIT 1
			";
			$query = self::DB()->prepare($query);
			$query->execute($exec);
			if($query->rowCount() == 1) {
				$data = $query->fetch(PDO::FETCH_ASSOC);
				if($data["table_log"] == "sentence") {
					return $data["target_log"];
				} else {
					$exec = array($data["target_log"]);
					$query = "
						SELECT
							id_sentence
						FROM
							lemma_has_form
						WHERE 
							id_lemma_has_form = ?
						LIMIT 1
					";
					$query->execute($exec);
					$data = $query->fetch(PDO::FETCH_ASSOC);
					return $data["id_sentence"];
				}
			}
			return false;

		}

		public static function Related($field, $value) {
			$returned = array();
			$exec = array($value);

			$query = "
				SELECT
					id_lemma_has_form
				FROM
					lemma_has_form
				WHERE
					".$field."  = ?
			";

			$query = self::DB()->prepare($query);
			$query->execute($exec);
			while($data = $query->fetch(PDO::FETCH_ASSOC)) {
				$returned[] = array("table" => "lemma_has_form", "target" => $data["id_lemma_has_form"]);
			}

			return $returned;

		}

		public static function Save($table, $target, $action, $user, $update = 0, $secondary = false) {
			if($secondary == false) {
				$secondary = time();
			}

			$exec = array(
				"table" => $table,
				"target" => $target,
				"user" => $user,
				"time" => time(),
				"action" => $action,
				"secondary" => $secondary,
				"update" => $update
			);

			$query = "
			INSERT INTO `log`
			(
			`table_log`,
			`target_log`,
			`time_log`,
			`action_log`,
			`secondary_log`,
			`update_log`,
			`id_user`
			)
			VALUES
			(
				:table,
				:target,
				:time,
				:action,
				:secondary,
				:update,
				:user
			);
			";

			$query = self::DB()->prepare($query);
			$query->execute($exec);

			if($table != "user" && isset($_SESSION["user"])) {
				$_SESSION["user"]["game"] = User::Rank();
			}
		}

		public static function Get($table, $target, $user = false, $max = false) {
			$exec = array(
				"table" => $table,
				"target" => $id
			);
			$where = array(
				"table_log = :table",
				"target_log = :target"
			);

			$table = array("log");
			$select = array(
				"log.time_log",
				"log.action_log"
			);

			if($user) { 
				$exec["user"] = $user; 
				$where[] = " id_user = :user ";
			} else {
				$table[] = "user";
				$select[] = "user.name_user";
				$select[] = "user.id_user";
				$where[] = "log.id_user = user.id_user";
			}

			$query = "
				SELECT
					".implode(" , ", $select)."
				FROM
					".implode(" , ", $table)."
				WHERE
					".implode(" AND ", $where)."

			";

			$query = self::DB()->prepare($query);
			$query->execute($exec);

			return $query->fetchAll(PDO::FETCH_ASSOC);
		}

		private static function Options($options, $glue = " OR ") {
			$exec = array();
			$where = array();

			foreach ($options as $key => &$option) {
				//Option has array("table" => table, "target" => target)
				$ex = "table" . $key;
				switch($option["table"]) {
					case "notuser":
						$string = "(table_log != 'user' )";

						if($option["target"] != false) {
							$exec[$ex] = $option["target"];
							$string .= "AND id_user = :table" . $key . " ";
						}

						$where[] = $string;
						break;
					case "lemma_has_form":
						$exec[$ex] = $option["target"];
						$where[] = "(table_log = 'lemma_has_form' AND target_log = :table" . $key . " )";
						break;
					case "lemma":
						$exec[$ex] = $option["target"];
						$where[] = "(table_log = 'lemma' AND target_log = :table" . $key . " )";
						break;
					case "sentence":
						$exec[$ex] = $option["target"];
						$where[] = "(table_log = 'sentence' AND target_log = :table" . $key . " )";
						break;
					case "user":
						$exec[$ex] = $option["target"];
						$where[] = "(table_log = 'user' AND target_log = :table" . $key . " )";
						break;
				}
			}
			$where = "(".implode($glue, $where).")";
			return array($exec, $where);

		}

		public static function Count($options, $user = false) {
			$data = self::Options($options);

			$exec = $data[0];
			$where = array($data[1]);
			$where[] = " update_log = 0 ";

			if($user == false) {
				$query = "
					SELECT
						COUNT(DISTINCT id_user, target_log, action_log, table_log, secondary_log)
					FROM
						log
					WHERE
						".implode(" AND ", $where)."

				";
			} else {
				$exec["user"] = $user;
				$query = "
					SELECT
						COALESCE(total.count, 100) as total,
						COALESCE(u.count, 0) as user,
						COALESCE(MAX(maxi.count), 100) as max
					FROM

						(
						SELECT
							COUNT(DISTINCT id_user, action_log, target_log, table_log, secondary_log) as count
						FROM
							log
						WHERE
							".implode(" AND ", $where)."
						) total,

						(
						SELECT
							COUNT(DISTINCT target_log, action_log, table_log, secondary_log) as count
						FROM
							log
						WHERE
							".implode(" AND ", $where)."
						GROUP BY
							id_user
						) maxi,";

				$where[] = "id_user = :user";

				$query .= "
						(
						SELECT
							COUNT(DISTINCT target_log, action_log, table_log, secondary_log) as count
						FROM
							log
						WHERE
							".implode(" AND ", $where)."
						) u
					
				";
			}
			
			$query = self::DB()->prepare($query);
			$query->execute($exec);
			return $query->fetch(PDO::FETCH_ASSOC);

		}
	}
?>