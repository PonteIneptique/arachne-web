<h1>Welcome to Lasciva Roma</h1>


<div class="text-center">
	<?if(isset($_SESSION["user"])):?>
	<a  style="margin-top: 20px; margin-bottom: 20px;" class="btn btn-success" href="/sentence/<?=Sentence::Random();?>" >
		Start annotating
	</a>
	<?else:?>
	<a style="margin-top: 20px; margin-bottom: 20px;" class="btn btn-success" href="/account/login" >
		Sign-up or login before starting contributing
	</a>
	<?endif;?>
</div>


<h2>What is Lasciva Roma ?</h2>
<p>This whole project aims to annotate the Latin lexical field of sexuality. One of the problems we have doing that in onomastic is our unability to have a wider point of view, this tries to fill the hole.</p>

	<div class="row">
		<div class="col-sm-4">
			<div class="thumbnail" style="height:100%; border:0; margin:0 auto;">
				<img class="img-circle" src="/assets/images/lemmatization.png" alt="...">
				<div class="caption">
					<h3>Lemmatize</h3>
					<p>Lemmatize name of persons and place by clicking on word in sentence</p>
					<p class="text-muted"><span class="glyphicon glyphicon-question-sign"></span> Words in black have no lemma attached to them. They are your targets !</p>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="thumbnail" style="height:100%; border:0; margin:0 auto;">
				<img src="/assets/images/annotate.png" class="img-circle" alt="...">
				<div class="caption">
					<h3>Annotate</h3>
					<p>Propose new annotation type or directly annotate item</p>
					<p class="text-muted"><span class="glyphicon glyphicon-question-sign"></span> Click "Annotation" in the menu bar to see the list of available annotations and propose your own. Browse sentence and click lemma to annotate them !</p>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="thumbnail" style="height:100%; border:0; margin:0 auto;">
				<img src="/assets/images/rate.png" class="img-circle" alt="...">
				<div class="caption">
					<h3>Rate</h3>
					<p>For each annotation and lemmatization, place your vote to tell if you think they are right or not by clicking <span class="glyphicon glyphicon-thumbs-up"></span> or <span class="glyphicon glyphicon-thumbs-down"></span></p>
					<p class="text-muted"><span class="glyphicon glyphicon-question-sign"></span> In sentence, words in red have not enough votes and green have enough.</p>
				</div>
			</div>
		</div>
	</div>

<div class="text-right">
	<a href="/about" class="btn btn-warning">Learn more about the project</a>
</div>