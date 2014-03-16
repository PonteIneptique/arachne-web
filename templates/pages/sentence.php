<article>
	<div class="row">
		<section class="col-md-8">
		<header>
			<h2 class="sentence-book"><?=$sentence["metadata"]["dc:Title"]?></h2>
			<h3 class="sentence-author"><?=$sentence["metadata"]["dc:Creator"]?></h3>
			<a href="http://www.perseus.tufts.edu/hopper/text?doc=<?=$sentence["document"];?>" target="_blank" class="sentence-link">See on Perseus</a>
		</header>
			<p class="sentence-text" data-id="<?=$sentence["uid"];?>">
				<?=$sentence["processed"];?>
			</p>
		</section>
		<aside class="col-md-4" id="sidebar">
			<?if(count($annotations["sentence-applied"]) > 0):?>
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
										<a class="annotations-thumbs-up" data-target="<?=$anno["id_annotation"];?>" href="#">
											<? if($anno["votes"] > 0 ) { echo $anno["votes"]; } else { echo "0"; } ?> <span class="glyphicon glyphicon-thumbs-up"></span>
										</a> 
										<a class="annotations-thumbs-down" data-target="<?=$anno["id_annotation"];?>" href="#">
											<? if($anno["votes"] < 0 ) { echo $anno["votes"]; } else { echo "0"; }  ?> <span class="glyphicon glyphicon-thumbs-down"></span>
										</a>
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
								<input type="text" name="newlemma" placeholder="Text of new lemma" class="nav-stack-input" />
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
	</div>
</article>

<?if(isset($_SESSION["user"])):?>
<div class="hidden" id="lemma-annotation-source">
	<select class="types nav-stack-select">
		<?foreach ($annotations["lemma"] as $key => $value):?>
			<option value="<?=$value["id"];?>"><?=$value["text"];?></option>
		<?endforeach;?>
	</select>

	<?foreach ($annotations["lemma"] as $value):?>
		<select class="value nav-stack-select" data-target="<?=$value["id"];?>">
			<?foreach ($value["options"] as $items):?>
				<option value="<?=$items["id"];?>"><?=$items["text"];?></option>
			<?endforeach;?>
		</select>
	<?endforeach;?>
</div>
<div class="hidden" id="sentence-annotation-source">
	<?foreach ($annotations["sentence"] as $value):?>
		<select class="value nav-stack-select" data-target="<?=$value["id"];?>">
			<?foreach ($value["options"] as $items):?>
				<option value="<?=$items["id"];?>"><?=$items["text"];?></option>
			<?endforeach;?>
		</select>
	<?endforeach;?>
</div>
<?endif;?>