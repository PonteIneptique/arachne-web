<div class="row">
	<article class="col-md-6" data-target="<?=$lemma["uid"]?>" id="lemma">
		<div  style="padding:10px;">
			<h1><?=$lemma["lemma"]?></h1>


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
							<td></td>
							<td><?=Gamification::Message($logs["user"], $logs["total"], $logs["max"]);?></td>
						</tr>
					</table>
				</div>
			<?endif;?>
			
			<h2>Annotation</h2>

			<? foreach ($annotations as &$anno): ?>

				<div class="col-md-8"><?=$anno["text_type"];?> : <?=$anno["text_value"];?></div>
				<div class="col-md-4">

					<?if(isset($_SESSION["user"])) { $CL = "span";} else { $CL = "span"; } ?>
					<<?=$CL;?> class="annotations-thumbs-up" data-target="<?=$anno["id_annotation"];?>" href="#">
						<? if($anno["votes"] > 0 ) { echo $anno["votes"]; } else { echo "0"; } ?> <span class="glyphicon glyphicon-thumbs-up"></span>
					</<?=$CL;?>> 
					<<?=$CL;?> class="annotations-thumbs-down" data-target="<?=$anno["id_annotation"];?>" href="#">
						<? if($anno["votes"] < 0 ) { echo $anno["votes"]; } else { echo "0"; }  ?> <span class="glyphicon glyphicon-thumbs-down"></span>
					</<?=$CL;?>>
				</div>
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
		</div>
	</article>
	<div class="col-md-6">
		<div id="svg-container" style="min-height: 500px; background-color:white;">

		</div>
	</div>
</div>