<?php
class Sentences {
	private static function DB() {
		global $DB;
		return $DB;
	}
	static function Author($id) {
		$exec = array();
		$query = "
			SELECT
				s.text_sentence as sentence,
				a.name_author as author,
				b.name_book as book,
				b.url_book as url
			FROM 
				sentence s,
				author a,
				book b
			WHERE
				s.uid_author = ? AND
				a.uid_author = s.uid_author AND
				b.uid_book = s.uid_book
			GROUP BY s.uid_sentence
			";
		$query = self::DB()->prepare($query);
		$query->execute(array($id));
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	static function Update($uid, $field, $val) {
		$exec = array();
		$query = "
			UPDATE
				sentence
			SET
				" . $field . " = ?
			WHERE
				uid_sentence = ?
			LIMIT 1
			";
		$query = self::DB()->prepare($query);
		$query->execute(array(trim($val), $uid));
		if($query->rowCount() == 1) {
			return trim($val);
		} else {
			return false;
		}
	}
	static function Word($id, $active = 1) {
		$exec = array();
		$query = "
			SELECT
				s.text_sentence as sentence,
				a.name_author as author,
				b.name_book as book,
				b.url_book as url
			FROM 
				word_has_lemma wl,
				word_has_phrase wp,
				sentence s,
				author a,
				book b
			WHERE
				wl.uid_lemma = ? AND
				wl.uid_word = wp.uid_word AND
				wp.uid_sentence = s.uid_sentence AND
				s.active = ? AND
				a.uid_author = s.uid_author AND
				b.uid_book = s.uid_book
			GROUP BY s.uid_sentence
			";
		$query = self::DB()->prepare($query);
		$query->execute(array($id, $active));
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	static function Get($id = false) {
		$exec = array();
		$query = "
			SELECT 
				s.text_sentence as sentence, 
				s.uid_sentence as uid, 
				s.active as active, 
				b.name_book as book, 
				b.url_book as url, 
				a.name_author as author
			FROM 
				book b,
				sentence s,
				author a
			WHERE
				b.uid_book = s.uid_book AND
				a.uid_author = s.uid_author";
		if(is_numeric($id)) {
			$query .= " AND
				s.uid_sentence = ?
			";
			$exec[] = $id;
		}
		$query = self::DB()->prepare($query);
		$query->execute($exec);
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	private static function Cache($url) {
		$md5 = md5($url);
		$path = "./cache/" . $md5;
		if(file_exists($path)) {
			$body = file_get_contents ($path);
		} else {
			//Getting the file
			$ch = curl_init($url);
			
			//Starting memorizing
			ob_start();
			curl_exec($ch);
			curl_close($ch);
			$response = ob_get_contents();
			ob_end_clean();
			
			//Getting the body
			libxml_use_internal_errors(true);
			$d = new DOMDocument;
			$mock = new DOMDocument;
			$d->loadHTML($response);
			$body = $d->getElementsByTagName('body')->item(0);
			foreach ($body->childNodes as $child){
				$mock->appendChild($mock->importNode($child, true));
			}
			$body = utf8_encode($mock->saveHTML());
			$body = str_replace("\n", " ", $mock->saveHTML());
			
			//Write to a cache file
			$fp = fopen($path, 'w');
			fwrite($fp, $body);
			fclose($fp);
		}
		return $body;
	}
	static function Context($id) {
		$data = self::Get($id);
		
		$body = self::Cache($data[0]["url"]);

		
		//Patternization
		$str = preg_replace("/\[[0-9]*\]/imu", "", $data[0]["sentence"]);
		$data[0]["sentence"] = $str;
		$str = preg_quote(trim($str));
		$str = str_replace("u", "(u|v)", $str);
		$str = str_replace("i", "(j|i)", $str);
		$options = "imu";
		$body = preg_replace("/" . $str . "/" . $options, "<span style='background-color:yellow; font-weight:bold' id='sentenceFound'>" . $data[0]["sentence"] . "</span>", $body);
		$data[0]["preg"] = "/" . $str . "/" . $options;
		//Replacing the sentence by a stylised one
		//$body = str_replace($data[0]["sentence"], "<span style='background-color:yellow; font-weight:bold'>" . $data[0]["sentence"] . "</span>", $body);
		
		//Return
		return array("body" => $body, "data" => $data[0]);
	}
}