<h1 class="background-like"><img src="/assets/images/text-black.png" alt="Lasciva Roma" style="height:50px;" /></h1>

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="padding-top: 10px;">
		<div class="shadow-content ">
			<p class="text-center">
				Find name of persons and places next to a member of the Latin semantic field of sexuality, annotate and lemmatize them, tell us about their relationship with this member.
			</p>


			<div class="text-center">
				<?if(isset($_SESSION["user"])):?>
				<a  style="margin-bottom: 10px;" class="btn btn-success" href="/sentence/<?=Sentence::Random();?>" >
					Start annotating
				</a>
				<?else:?>
				<a style="margin-bottom: 10px;" class="btn btn-success" href="/account/login" >
					Sign-up or login before starting contributing
				</a>
				<?endif;?>
			</div>
		</div>
	</div>
</div>

<div class="background-like">
	<a href="https://twitter.com/lascivaroma" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @lascivaroma</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</div>

<div class="shadow-content">
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
					<p class="text-muted"><span class="glyphicon glyphicon-question-sign"></span> In sentence, words in red have more than one lemma, green have only one.</p>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<hr />
			<div class="gamification text-center">
				<table style="margin:0 auto;">
					<tbody>
						<tr>
							<td rowspan="2" valign="middle" style="padding-right:20px;">
								<h2 style="margin:0; padding:0;">Evolve</h2>
								By participating, reach the highest rank in the community !
							</td>
							<td class="image-container">
								<img alt="Student" title="Student" src="/assets/images/badges/student.png">
							</td>
							<td>
								<span class="glyphicon glyphicon-chevron-right"></span>
							</td>
							<td class="image-container">
								<img alt="Citizen" title="Citizen" src="/assets/images/badges/citizen.png">
							</td>
							<td>
								<span class="glyphicon glyphicon-chevron-right"></span>
							</td>
							<td class="image-container">
								<img alt="Rhetor" title="Rhetor" src="/assets/images/badges/rhetor.png">
							</td>
							<td>
								<span class="glyphicon glyphicon-chevron-right"></span>
							</td>
							<td class="image-container">
								<img alt="Aedile" title="Aedile" src="/assets/images/badges/aedile.png">
							</td>
							<td>
								<span class="glyphicon glyphicon-chevron-right"></span>
							</td>
							<td class="image-container">
								<img alt="Tribune" title="Tribune" src="/assets/images/badges/tribune.png">
							</td>
							<td>
								<span class="glyphicon glyphicon-chevron-right"></span>
							</td>
							<td class="image-container">
								<img alt="Consul" title="Consul" src="/assets/images/badges/consul.png">
							</td>
						</tr>
						<tr>
							<td class="image-container">
								Student
							</td>
							<td>
							</td>
							<td class="image-container">
								Citizen
							</td>
							<td>
								
							</td>
							<td class="image-container">
								Rhetor
							</td>
							<td>
							</td>
							<td class="image-container">
								Aedile
							</td>
							<td>
							</td>
							<td class="image-container">
								Tribune
							</td>
							<td>
							</td>
							<td class="image-container">
								Consul
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="background-like" style="padding-top:20px; padding-bottom:20px;">
	<div class="text-center">
		<a href="/about" class="btn btn-warning">Learn more about the project</a>
	</div>
</div>