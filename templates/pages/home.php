<h1>Welcome to Lasciva Roma</h1>


<div class="text-center">
	<?if(isset($_SESSION["user"])):?>
	<a class="btn btn-success" href="/sentence/<?=Sentence::Random();?>" >
		Start annotating
	</a>
	<?else:?>
	<a class="btn btn-success" href="/account/login" >
		Sign-up or login before starting contributing
	</a>
	<?endif;?>
</div>


<h2>What is Lasciva Roma ?</h2>
<p>This whole project aims to annotate the Latin lexical field of sexuality. One of the problems we have doing that in onomastic is our unability to have a wider point of view, this tries to fill the hole.</p>


<h2>What is my role ?</h2>
	<div class="row">
		<div class="col-sm-4">
			<div class="thumbnail" style="height:100%; border:0; margin:0 auto;">
				<img src="/assets/images/placeholder.svg" alt="...">
				<div class="caption">
					<h3>Lemmatize</h3>
					<p>Lemmatize name of persons and place by clicking on word in sentence</p>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="thumbnail" style="height:100%; border:0; margin:0 auto;">
				<img src="/assets/images/placeholder.svg" alt="...">
				<div class="caption">
					<h3>Annotate</h3>
					<p>Propose new annotation type or directly annotate item</p>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="thumbnail" style="height:100%; border:0; margin:0 auto;">
				<img src="/assets/images/placeholder.svg" alt="...">
				<div class="caption">
					<h3>Rate</h3>
					<p>For each annotation and lemmatization, place your vote to tell if you think they are right or not</p>
				</div>
			</div>
		</div>
	</div>


<p>Your mission in this empire is to annotate forms according to your knowledge in Latin culture or grammar ! As the results shown here are issued by automatic functionnalities, many of them are unsure.</p>
<p>So for them, we need your citizen power : the <b>vote</b>.</p>
<p>But we also don't have a lot of information about each item, so maybe you could use your rhetor power : the <b>annotation</b>.</p>
<p>As we are kind of democratic hipster (We don't count Greek as our predecessor. That would be wrong.), you can actually propose new annotation type and value through the Annotation link on the top</p>
<p>Of course you can only browse the result, but where is the fun in it ?</p>

<div class="text-right">
	<a href="/about" class="btn btn-warning">Learn more about the project</a>
</div>