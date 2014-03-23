<?php
class Polarity {
	private static function DB() {
		global $DB;
		return $DB;
	}

	public static function Exists($user, $lemma) {
		$exec = array($lemma, $user);
		$query = "SELECT id_polarity FROM polarity WHERE id_lemma = ? AND id_user = ? LIMIT 1";
		
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		if($query->rowCount() == 1) {
			$data = $query->fetch(PDO::FETCH_ASSOC);
			return $data["id_polarity"];
		}
		return false;
	}
	public static function Insert($user, $lemma, $val){
		$id_polarity = self::Exists($user, $lemma);
		if($id_polarity != false) {
			return self::Update($id_polarity, $val);
		}
		$exec = array($lemma, $val, $user);

		$query = "
		INSERT INTO 
			`polarity`
		(
			`id_lemma`,
			`id_user`,
			`value_polarity`
		)
		VALUES
		(
			? ,
			? ,
			? 
		);
		";
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		if($query->rowCount() == 1) {
			Logs::Save("lemma", $lemma, "polarization", $_SESSION["user"]["id"]);
			return true;
		} else {
			return false;
		}
	}
	public static function Update($id, $val){
		$query = "
		UPDATE
			polarity
		SET
			value_polarity = ?
		WHERE 
			id_polarity = ?
		";
		$query = self::DB()->prepare($query);
		$query->execute(array($val, $id));
		$count += $query->rowCount();

		if($count > 0) {
			//Logs::Save("lemma_has_form", $source, "relationUpdate", $_SESSION["user"]["id"]);
			return true;
		} else {
			return false;
		}
	}
}
?>