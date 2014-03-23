<!DOCTYPE html>
<html>
    <head>
    	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Lasciva Roma : A crowdsourcing project | <?=$title;?></title>
        <base href="http://<?=$_SERVER['HTTP_HOST']?>/">
        <link href='http://fonts.googleapis.com/css?family=Gafata' rel='stylesheet' type='text/css'>
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
							<li><a href="/lemma">Lemma</a></li>
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

										<table class="gamification pull-right">
											<tr>
												<td valign="middle" class="image-container">
													<a class="image-container"><?=$_SESSION["user"]["game"]["image"];?></a>
												</td>
											</tr>
										</table>
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
							</div>
							<!--<h1 style="color:white; font-variant: small-caps; font-size:30px; font-weight: bold; margin-bottom:20px;"><?=$title;?></h1>
							-->
						</div>
					</div>
				</div>
		</header>
		<div class="container" id="main">
			<?=$content;?>
		</div>

        <script src="./assets/js/jquery.min.js" type="text/javascript"></script>
        <script src="./assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="./assets/js/home.js" type="text/javascript"></script>
        <script src="./assets/js/knob.js" type="text/javascript"></script>
        <script src="./assets/js/jquery.cookie.js" type="text/javascript"></script>
        <script src="//cdn.jsdelivr.net/d3js/3.4.3/d3.min.js" type="text/javascript"></script>
        <?foreach($scripts as $script):?>
        	<script src="./assets/js/<?=$script;?>.js" type="text/javascript"></script>
        <?endforeach;?>


    </body>
</html>
