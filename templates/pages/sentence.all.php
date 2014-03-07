<?
	$author = "";
	$book = "";
	$ended = true;
?>
<div>
<?
	foreach($sentences as $index => $sentence) {
		
		if ($sentence["author"] != $author) {
			$author = $sentence["author"];

			if($ended == false) { ?></ul><? }

		?>
			</div>
			<div class="sentence-stack">
			<h2 class="sentence-author"><?=$sentence["author"]?></h2>
		<?
		}
		if ($sentence["book"] != $book) {

			if($ended == false) { ?></ul><? }

			$book = $sentence["book"];
			$ended = false;
		?>
			<h3 class="sentence-book"><?=$sentence["book"]?></h3>
			<ul class="sentence-list">
		<?
		}

		?>
			<li><a href="/sentence/<?=$sentence["id_sentence"]?>"><?=$sentence["id_sentence"]?></a></li>
		<?

	}
?>
</div>