<h2 class="sentence-book"><?=$sentence["metadata"]["dc:Title"]?></h2>
<h3 class="sentence-author"><?=$sentence["metadata"]["dc:Creator"]?></h3>
<a href="http://www.perseus.tufts.edu/hopper/text?doc=<?=$sentence["document"];?>" target="_blank" class="sentence-link">Link</a>


<p class="sentence-text" data-id="<?=$sentence["uid"];?>">
	<?=$sentence["processed"];?>
</p>



<div id="popover" style="display:none;">
	<div class="popover-category popover-category-form popover-category-lemmas">
		<div class="append-in">
		</div>
		<ul class="nav nav-pills nav-ehri nav-ehri-grey newlemma">
			<li>
				<input type="text" name="Test" placeholder="Text of new lemma" class="nav-ehri nav-ehri-grey nav-ehri-input" />
			</li>
			<li>
				<input type="button" name="Send" value="Save" class="nav-ehri nav-ehri-grey nav-ehri-input submit" />
			</li>
		</ul>
	</div>
	<div class="annotations-containers">
		<div class="popover-category">
			<ul class="nav nav-pills nav-ehri nav-ehri-black nav-justified">
				<li><a class="popover-category-title" href="#">New lemma annotation</a>
				</li>
			</ul>
			<ul class="nav nav-pills nav-ehri nav-ehri-grey nav-justified popover-category-form">
				<li>
					<button class="nav-ehri nav-ehri-grey nav-ehri-input" type="button">Annotations</button>
				</li>
				<li>
					<input type="text" name="Test" placeholder="Text" class="nav-ehri nav-ehri-grey nav-ehri-input" />
				</li>
			</ul>
		</div>
	</div>
</div>

<div class="hidden" id="lemma-annotation">
	<select class="types nav-ehri nav-ehri-grey nav-ehri-input nav-ehri-select">
		<?foreach ($annotations["lemma"] as $key => $value):?>
			<option value="<?=$value["id"];?>"><?=$value["text"];?></option>
		<?endforeach;?>
	</select>

	<?foreach ($annotations["lemma"] as $value):?>
		<select class="value" data-target="<?=$value["id"];?>">
			<?foreach ($value["options"] as $items):?>
				<option value="<?=$items["id"];?>"><?=$items["text"];?></option>
			<?endforeach;?>
		</select>
	<?endforeach;?>
</div>