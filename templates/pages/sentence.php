<h2 class="sentence-book"><?=$sentence["metadata"]["dc:Title"]?></h2>
<h3 class="sentence-author"><?=$sentence["metadata"]["dc:Creator"]?></h3>
<a href="#" class="sentence-link">Link</a>


<p class="sentence-text" data-id="<?=$sentence["uid"];?>">
	<?=$sentence["processed"];?>
</p>



<div id="popover" style="display:none;">
	<div class="popover-category popover-category-form popover-category-lemmas">
		<div class="append-in">
		</div>
		<ul class="nav nav-pills nav-ehri nav-ehri-grey nav-justified">
			<li>
				<button class="nav-ehri nav-ehri-grey nav-ehri-input">New</button>
			</li>
			<li>
				<input type="text" name="Test" placeholder="Text of lemma" class="nav-ehri nav-ehri-grey nav-ehri-input" />
			</li>
			<li class="col-xs-3">
				<input type="button" name="Send" value="Save" class="nav-ehri nav-ehri-grey nav-ehri-input" />
			</li>

			</li>
		</ul>
	</div>
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