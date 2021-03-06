
<div class="shadow-content row">
	<article class="col-md-6" data-target="<?=$lemma["uid"]?>" id="lemma">
		<div  style="padding:10px;">
			<h1><?=$lemma["lemma"]?></h1>
			
			<h2>Annotation</h2>

			<? foreach ($annotations as &$anno): ?>
				<div><?=$anno["text_type"];?> : <?=$anno["text_value"];?></div>
			<? endforeach; ?>


			<h2>Saved forms</h2>
				<ul class="list-unstyled">
					<? foreach ($forms as &$form): ?>
						<li><?=$form["text_form"];?> <small>( <?=$form["sentences"];?> Sentence(s),  <?=$form["votes"];?> Vote(s) )</small></li>
					<? endforeach; ?>
				</ul>

			<h2>Sentences</h2>
			<ul class="list-unstyled sentence-list">
				<?foreach($sentences as &$sentence):?>
					<li><a href="/sentence/<?=$sentence["id_sentence"];?>"><?=$sentence["text_sentence"];?></a></li>
				<?endforeach;?>
			</ul>


			<?if(isset($_SESSION["user"])):?>
				<hr />
				<div class="gamification">
					<table>
						<tr>
							<td valign="middle">
								<input type="text" class="dial" data-width="50" data-height="50" data-min="0" value="<?=$logs["user"]?>" data-max="<?=$logs["total"]?>" data-readOnly="true">
							</td>
							<td valign="middle" style="text-indent:10px;">
								actions made by you here !
							</td>
						</tr>
						<tr>
							<td class="image-container"><?=Gamification::Image($logs["user"], $logs["total"], $logs["max"]);?></td>
							<td style="text-indent:10px;"><?=Gamification::Message($logs["user"], $logs["total"], $logs["max"]);?></td>
						</tr>
					</table>
				</div>
			<?endif;?>
		</div>
	</article>
	<div class="col-md-6">
		<div id="svg-container" style="min-height: 600px; background-color:white;">

		</div>
	</div>
</div>