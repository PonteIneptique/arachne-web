<?php
class Relationship {
	private static function DB() {
		global $DB;
		return $DB;
	}

	public static function Get($sentence, $form) {
		if(!isset($_SESSION["user"])) {
			return array();
		}
		$user = $_SESSION["user"]["id"];
		$exec = array($sentence, $form, $user);
		$query = "SELECT id_lemma, value_relationship FROM relationship WHERE id_sentence = ? AND id_form = ? AND id_user = ?";
		
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		if($query->rowCount() > 0) {
			$data = $query->fetchAll(PDO::FETCH_ASSOC);
			return $data;
		}
		return array();
	}
	public static function Exists($user, $form, $lemma, $sentence) {
		$exec = array($form, $sentence, $lemma, $user);
		$query = "SELECT id_relationship FROM relationship WHERE id_form = ? AND id_sentence = ? AND id_lemma = ? AND id_user = ?";
		
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		if($query->rowCount() >= 1) {
			$data = $query->fetchAll(PDO::FETCH_ASSOC);
			return $data;
		}
		return array();
	}
	public static function Insert($user, $form, $lemma, $sentence, $val){
		$id = self::Exists($user, $form, $lemma, $sentence);
		if(count($id) > 0) {
			return self::Update($id, $val);
		}
		$exec = array( $form, $lemma, $sentence, $val, $user);

		$query = "
		INSERT INTO 
			`relationship`
		(
			`id_form`,
			`id_lemma`,
			`id_sentence`,
			`value_relationship`,
			`id_user`
		)
		VALUES
		(
			? ,
			? ,
			? ,
			? ,
			?
		);
		";
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		if($query->rowCount() == 1) {
			Logs::Save("sentence", $sentence, "relation", $_SESSION["user"]["id"]);
			return true;
		} else {
			return false;
		}
	}
	public static function Update($id, $val){
		$query = "
		UPDATE
			relationship
		SET
			value_relationship = ?
		WHERE 
			id_relationship = ?
		";
		$query = self::DB()->prepare($query);
		$count = 0;

		foreach($id as &$identifier) {
			$query->execute(array($val, $identifier["id_relationship"]));
			$count += $query->rowCount();
		}

		if($count > 0) {
			//Logs::Save("lemma_has_form", $source, "relationUpdate", $_SESSION["user"]["id"]);
			return true;
		} else {
			return false;
		}
	}
}
?>