<!DOCTYPE html>
<html>
    <head>
    	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Lasciva Roma : A crowdsourcing project | <?=$title;?></title>
        <base href="http://<?=$_SERVER['HTTP_HOST']?>/">
        <!--<link href='http://fonts.googleapis.com/css?family=Gafata' rel='stylesheet' type='text/css'>-->
        <link rel="stylesheet" media="screen" href="./assets/css/bootstrap.css">
        <link rel="stylesheet" media="screen" href="./assets/css/theme.css">
    </head>
    <body class="<?=preg_replace('/\W+/','',strtolower(strip_tags($title)));;?>">
		<header id="top">
			<div class="container">
				<div class="row top-menu">
					<div class="col-md-3 text-center">
						<a href="/"><img src="./assets/images/text-logo.png" class="img-responsive" alt="Lasciva Roma" /></a>
						
					</div>
					
					<div class="col-md-9">
						<ul class="nav nav-pills nav-ehri nav-ehri-black nav-justified">
							<li><a href="/annotations">Annotations</a></li>
							<li><a href="/sentence/">Sentences</a></li>
							<li><a href="/about">About</a></li>
						</ul>
					</div>
				</div>

			</div>
			<div id="page-title">
				<div class="container">
					<div class="row">
						<div class="account">
							<div class="login-link">
								<?if(isset($_SESSION["user"])):?>
										<a href="/account/profile">Profile</a>
										<a href="/account/history">History</a>
										<a href="/account/signout">Sign out</a>
										<?if(isset($_SESSION["user"]["game"])):?>
										<table class="gamification pull-right">
											<tr>
												<td valign="middle" class="image-container">
													<a class="image-container"><?=$_SESSION["user"]["game"]["image"];?></a>
												</td>
											</tr>
										</table>
										<?endif;?>
								<?else:?>
									<a href="/account/login" class="login-link">Login</a>
								<?endif;?>
							</div>
						</div>
						<div class="badges">
							<div class="shortcuts">
								<a href="/sentence/random" title="Random sentence">Random sentence</a>
								<?if(isset($_SESSION["user"])):?>
									<a href="/sentence/last" title="Last sentence">Last sentence</a>
								<?endif;?>
								<a href="/lemma">Browse Lemma</a>
							</div>
						</div>
					</div>
				</div>
		</header>
		<div id="sharing">
			<span class='st_facebook' displayText='Facebook'></span>
			<span class='st_twitter' displayText='Tweet'></span>
			<span class='st_email' displayText='Email'></span>
		</div>
		<div class="container" id="main">
			<?=$content;?>
		</div>
		<footer class="navbar navbar-inverse">
			<p class="navbar-text"><a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/4.0/80x15.png" /></a> <span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">Lasciva Roma</span> by <span xmlns:cc="http://creativecommons.org/ns#" property="cc:attributionName">Thibault Clérice</span> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>. Based on a work at <a xmlns:dct="http://purl.org/dc/terms/" href="http://github.com/PonteIneptique/clotho-web" rel="dct:source">http://github.com/PonteIneptique/clotho-web</a>.</p>
			<p class="navbar-text navbar-right"><a href="/policies">Policy</a></p>
		</footer>
        <script src="./assets/js/jquery.min.js" type="text/javascript"></script>
        <script src="./assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="./assets/js/home.js" type="text/javascript"></script>
        <script src="./assets/js/knob.js" type="text/javascript"></script>
        <script src="./assets/js/jquery.cookie.js" type="text/javascript"></script>
        <!--
        	<script src="//cdn.jsdelivr.net/d3js/3.4.3/d3.min.js" type="text/javascript"></script>
			<script type="text/javascript">var switchTo5x=true;</script>
			<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
			<script type="text/javascript">stLight.options({publisher: "8e5574e7-39ce-4eca-9a79-a86908641eb5", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
		-->

        <?foreach($scripts as $script):?>
        	<script src="./assets/js/<?=$script;?>.js" type="text/javascript"></script>
        <?endforeach;?>


    </body>
</html>
