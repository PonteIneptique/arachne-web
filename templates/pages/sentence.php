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
		<aside class="col-md-4" id="lemma-sidebar" style="display:none;">
			<div id="forms-container" class="sidebar-category">
				<div class="sidebar-category-title nav-stack nav-stack-black full">
				</div>
				<div id="forms-lemma" class="sidebar-category-content">
					<div class="append-in">
					</div>
					<div class="nav-stack nav-stack-grey newlemma">
						<div class="nav-6">
							<input type="text" name="newlemma" placeholder="Text of new lemma" class="nav-stack-input" />
						</div>
						<div class="nav-6">
							<input type="button" name="Send" value="Save" class="nav-stack-input submit" />
						</div>
					</div>
				</div>
			</div>
			<div class="annotations-containers">
				
			</div>
		</aside>
	</div>
</article>

<div class="hidden" id="lemma-annotation">
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