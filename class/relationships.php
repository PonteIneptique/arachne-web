<?php
class Relationship {
	private static function DB() {
		global $DB;
		return $DB;
	}

	public function Get($source) {
		if(!isset($_SESSION["user"])) {
			return array();
		}
		$user = $_SESSION["user"]["id"];
		$exec = array( $source, $user);
		$query = "SELECT id_lemma, value_relationship FROM relationship WHERE id_lemma_has_form = ? AND id_user = ?";
		
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		if($query->rowCount() > 0) {
			$data = $query->fetchAll(PDO::FETCH_ASSOC);
			return $data;
		}
		return array();
	}
	public function Exists($user, $source, $target) {
		$exec = array( $source, $target, $user);
		$query = "SELECT id_relationship FROM relationship WHERE id_lemma_has_form = ? AND id_lemma = ? AND id_user = ? LIMIT 1";
		
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		if($query->rowCount() == 1) {
			$data = $query->fetch(PDO::FETCH_ASSOC);
			return $data["id_relationship"];
		}
		return false;
	}
	public function Insert($user, $source, $target, $val){
		$id = self::Exists($user, $source, $target);
		if(is_numeric($id)) {
			return self::Update($id, $val, $source);
		}
		$exec = array( $source, $target, $val, $user);

		$query = "
		INSERT INTO 
			`relationship`
		(
			`id_lemma_has_form`,
			`id_lemma`,
			`value_relationship`,
			`id_user`
		)
		VALUES
		(
			? ,
			? ,
			? ,
			?
		);
		";
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		if($query->rowCount() == 1) {

			Logs::Save("lemma_has_form", $source, "relation", $_SESSION["user"]["id"]);
			return true;
		} else {
			return false;
		}
	}
	public function Update($id, $val, $source){
		$exec = array($val, $id);
		$query = "
		UPDATE
			relationship
		SET
			value_relationship = ?
		WHERE 
			id_relationship = ?
		LIMIT 1
		";
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		if($query->rowCount() == 1) {
			//Logs::Save("lemma_has_form", $source, "relationUpdate", $_SESSION["user"]["id"]);
			return true;
		} else {
			return false;
		}
	}
}
?>