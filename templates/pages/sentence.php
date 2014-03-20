<article>
	<section class="col-md-8">
		<header>
			<a class="pull-right badge" href="/sentence/<?=($sentence["uid"] + 1);?>" style="margin-left:10px;"><span>Next Sentence</span></a>
			<h2 class="sentence-book"><?=$sentence["metadata"]["dc:Title"]?></h2>
			<h3 class="sentence-author"><?=$sentence["metadata"]["dc:Creator"]?></h3>
			<a href="http://www.perseus.tufts.edu/hopper/text?doc=<?=$sentence["document"];?>" target="_blank" class="sentence-link">See on Perseus</a>
		</header>
		<p class="sentence-text" data-id="<?=$sentence["uid"];?>">
			<?=$sentence["processed"];?>
		</p>

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

	</section>
	<aside class="col-md-4" id="sidebar">
		<?if(count($annotations["sentence-applied"]) > 0 || isset($_SESSION["user"])):?>
		<div id="sentence-sidebar">
			<div id="sentence-container" class="sidebar-category">
				<div class="sidebar-category-title nav-stack nav-stack-black full">
					Sentence's Annotations
				</div>
				<div class="sidebar-category-content" style="display:block;">

					<div class="annotations-container">
						<? foreach ($annotations["sentence-applied"] as &$anno): ?>
						
							<div class="nav-stack nav-stack-grey annotation-vote">

								<div class="nav-8"><?=$anno["text_type"];?> : <?=$anno["text_value"];?></div>
								<div class="nav-4">

									<?if(isset($_SESSION["user"])) { $CL = "a";} else { $CL = "span"; } ?>
									<<?=$CL;?> class="annotations-thumbs-up" data-target="<?=$anno["id_annotation"];?>" href="#">
										<? if($anno["votes"] > 0 ) { echo $anno["votes"]; } else { echo "0"; } ?> <span class="glyphicon glyphicon-thumbs-up"></span>
									</<?=$CL;?>> 
									<<?=$CL;?> class="annotations-thumbs-down" data-target="<?=$anno["id_annotation"];?>" href="#">
										<? if($anno["votes"] < 0 ) { echo $anno["votes"]; } else { echo "0"; }  ?> <span class="glyphicon glyphicon-thumbs-down"></span>
									</<?=$CL;?>>
								</div>
							</div>
						<? endforeach; ?>
					</div>

					<?if(isset($_SESSION["user"])):?>
						<div class="nav-stack nav-stack-grey new-annotation" data-table="sentence" data-target="<?=$sentence["uid"];?>">
							<div class="nav-5">
								<select class="types nav-stack-select">
									<?foreach ($annotations["sentence"] as $key => $value):?>
										<? if (!isset($firstchild)) { $firstchild = $key; } ?>
										<option value="<?=$value["id"];?>"><?=$value["text"];?></option>
									<?endforeach;?>
								</select>
							</div>
							<div class="nav-5">
								<div class="target-type">
									<select class="value nav-stack-select" data-target="<?=$value["id"];?>">
										<?foreach ($annotations["sentence"][$firstchild]["options"] as $items):?>
											<option value="<?=$items["id"];?>"><?=$items["text"];?></option>
										<?endforeach;?>
									</select>
								</div>
							</div>
							<div class="nav-2">
								<button class="nav-stack-input submit">
									<span class="glyphicon glyphicon-plus-sign"></span>
								</button>
							</div>
						</div>
					<?endif;?>

				</div>
			</div>	
		</div>
		<?endif;?>
		<div id="lemma-sidebar" style="display:none;">
			<div id="forms-container" class="sidebar-category">
				<div class="sidebar-category-title nav-stack nav-stack-black full">
				</div>
				<div id="forms-lemma" class="sidebar-category-content">
					<div class="append-in">
					</div>
					<?if(isset($_SESSION["user"])):?>
						<div class="nav-stack nav-stack-grey newlemma">
							<div class="nav-6">
								<input type="text" name="newlemma" id="lemmaSearch" placeholder="Text of new lemma" class="nav-stack-input" />
							</div>
							<div class="nav-6">
								<input type="button" name="Send" value="Save" class="nav-stack-input submit" />
							</div>
						</div>
					<?endif;?>
				</div>
			</div>
			<div class="annotations-containers">
				
			</div>
		</div>
	</aside>
</article>

<?if(isset($_SESSION["user"])):?>
<div class="hidden" id="lemma-annotation-source">
	<select class="relationships nav-stack-select">
		<option value="-1">Opposed to</option>
		<option value="0">Neutral to</option>
		<option value="1">Qualifying/ied by</option>
	</select>
	<select class="lemma-query nav-stack-select">
		<?foreach ($forms as &$value):?>
			<?if($value["query_lemma"] == 1):?>
				<option value="<?=$value["id_lemma"];?>"><?=$value["text_form"];?></option>
			<?endif;?>
		<?endforeach;?>
	</select>
	<select class="types nav-stack-select">
		<?foreach ($annotations["lemma"] as &$value):?>
			<option value="<?=$value["id"];?>"><?=$value["text"];?></option>
		<?endforeach;?>
	</select>

	<?foreach ($annotations["lemma"] as &$value):?>
		<select class="value nav-stack-select" data-target="<?=$value["id"];?>">
			<?foreach ($value["options"] as &$items):?>
				<option value="<?=$items["id"];?>"><?=$items["text"];?></option>
			<?endforeach;?>
		</select>
	<?endforeach;?>
</div>
<div class="hidden" id="sentence-annotation-source">
	<?foreach ($annotations["sentence"] as &$value):?>
		<select class="value nav-stack-select" data-target="<?=$value["id"];?>">
			<?foreach ($value["options"] as &$items):?>
				<option value="<?=$items["id"];?>"><?=$items["text"];?></option>
			<?endforeach;?>
		</select>
	<?endforeach;?>
</div>
<?endif;?>