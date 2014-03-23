<?
	$author = "";
	$book = "";
	$ended = true;
?>
<input class="form-control pull-right" id="quicksearch" placeholder="Filter this list" />
<h1>List of authors and books </h1>
<div id="sentence-list">
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
			<li><a data-toggle="tooltip" title="<?=$sentence['annotations'];?> Annotations, <?=$sentence['votes'];?> form votes, " href="/sentence/<?=$sentence["id_sentence"]?>"><?=$sentence["text_sentence"]?></a></li>
		<?

	}
?>
</div>