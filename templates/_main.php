<!DOCTYPE html>
<html>
    <head>
    	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Lasciva Roma : A crowdsourcing project | <?=$title;?></title>
        <base href="http://<?=$_SERVER['HTTP_HOST']?>/"></base>
        <link rel="stylesheet" media="screen" href="./assets/css/bootstrap.css">
        <link rel="stylesheet" media="screen" href="./assets/css/theme.css">
    </head>
    <body>
		<header id="top">
			<div class="container">
				<div class="row top-menu">
					<div class="col-md-3 text-center">
						<a href="/"><img src="./assets/images/text-logo.png" class="img-responsive" alt="Lasciva Roma" /></a>
						
					</div>
					
					<div class="col-md-9">
						<ul class="nav nav-pills nav-ehri nav-ehri-black nav-justified">
							<li><a href="/sentence/<?=Sentence::Random();?>">
								<?if(isset($_SESSION["user"])):?>
									Start annotating !
								<?else:?>
									Random sentence
								<?endif;?>
							</a></li>
							<li><a href="/annotations">Annotations</a></li>
							<li><a href="/lemma">Lemma</a></li>
							<li><a href="/sentence/">Sentences</a></li>
						</ul>
					</div>
				</div>

			</div>
			<div id="page-title" style="background-color:#360f4f;">
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-push-8 text-right">
							<?if(isset($_SESSION["user"])):?>
								<div class="login-link">
									<a href="/account/profile"><?=$_SESSION["user"]["name"]?></a>
									<a href="/account/signout">Sign out</a>
								</div>
							<?else:?>
								<a href="/account/login" class="login-link">Login</a>
							<?endif;?>
						</div>
						<div class="col-md-8 col-md-pull-4">
							<h1 style="color:white; font-variant: small-caps; font-size:30px; font-weight: bold; margin-bottom:20px;"><?=$title;?></h1>
						</div>
					</div>
				</div>
			</div>
		</header>
		<div class="container">
			<div class="row">
				<div class="col-md-12" id="main">
					<?=$content;?>
				</div>
			</div>
		</div>

        <script src="./assets/js/jquery.min.js" type="text/javascript"></script>
        <script src="./assets/js/bootstrap.min.js" type="text/javascript"></script>
        <?foreach($scripts as $script):?>
        	<script src="./assets/js/<?=$script;?>.js" type="text/javascript"></script>
        <?endforeach;?>


    </body>
</html>
