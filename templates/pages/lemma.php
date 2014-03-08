<div class="row">
	<article class="col-md-6" data-target="<?=$lemma["uid"]?>" style="padding:10px;" id="lemma">
		<h1><?=$lemma["lemma"]?></h1>
		<ul class="list-unstyled">
			<?foreach($sentences as $sentence):?>
				<li><a href="/sentence/<?=$sentence["id_sentence"];?>"><?=$sentence["text_sentence"];?></a></li>
			<?endforeach;?>
		</ul>
	</article>
	<div class="col-md-6">
		<div id="svg-container" style="min-height: 500px; background-color:white;">

		</div>
	</div>
</div>