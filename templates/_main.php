<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Cicero's Network Admin Interface</title>
		<meta charset="UTF-8" />
		<base href="http://www.cicerosnetwork.com/" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" />
		<link href="./assets/css/fontello.css" rel="stylesheet" />
		<link href="./assets/css/typeahead.css" rel="stylesheet" />
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet" />

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
		<script src="./assets/js/jquery.tablesorter.min.js"></script>
		<script src="./assets/js/jquery.typeahead.min.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-3"> 
					<h1><a href="/">Ciceros Network</a></h1>  
					<ul class="nav nav-pills nav-stacked">
						<li><a href="/">Home</a></li>
						<li><a href="/login">Login</a></li>
						<? if($signedup == true):?>
						<li><a href="/word">Words</a></li>
						<li><a href="/sentence">Sentences</a></li>
						<li><a href="/metadata">Metadata</a></li>
						<li><a href="/lemma">Lemma</a></li>
						<?endif; ?>
					</ul>
					<div id="data-show">
						
					</div>
				</div>
			
				<div class="col-md-9"> 
					<div class="well">
						<?=$content; ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
