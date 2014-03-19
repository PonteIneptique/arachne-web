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
							<li><a href="/annotations">Annotations</a></li>
							<li><a href="/lemma">Lemma</a></li>
							<li><a href="/sentence/">Sentences</a></li>
						</ul>
					</div>
				</div>

			</div>
			<div id="page-title">
				<div class="container">
					<div class="row">
						<div class="account">
							<?if(isset($_SESSION["user"])):?>
								<div class="login-link">
									<a href="/account/profile">Profile</a>
									<a href="/account/history">History</a>
									<a href="/account/signout">Sign out</a>
								</div>
							<?else:?>
								<a href="/account/login" class="login-link">Login</a>
							<?endif;?>
						</div>
						<div class="badges">
							<?if(isset($_SESSION["user"])):?>
								<table class="gamification pull-right">
									<tr>
										<td valign="middle" style="color:white;">
											Your Stats
										</td>
										<td valign="middle" class="image-container">
											<a class="image-container"><?=$_SESSION["user"]["game"]["image"];?></a>
										</td>
										<td valign="middle" class="image-container">
											<input type="text" class="dial" data-width="50" data-height="50" data-min="0" value="<?=$_SESSION["actions"]["user"];?>" data-max="<?=$_SESSION["actions"]["total"]?>" data-readOnly="true">
										</td>
									</tr>
								</table>
							<?endif;?>
							<table class="gamification">
								<tr>
									<td valign="middle" style="color:white;">
										Shortcuts
									</td>
									<td valign="middle" class="image-container">
										<a class="image-container" href="/sentence/random" title="Random sentence"><img src="/assets/images/random.png" alt="Random sentence" /></a>
									</td>
									<?if(isset($_SESSION["user"])):?>
									<td valign="middle" class="image-container">
										<a class="image-container" href="/sentence/last" title="Last sentence"><img src="/assets/images/last.png" alt="Last sentence" /></a>
									</td>
									<?endif;?>
								</tr>
							</table>
							<!--<h1 style="color:white; font-variant: small-caps; font-size:30px; font-weight: bold; margin-bottom:20px;"><?=$title;?></h1>
							-->
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
        <script src="./assets/js/home.js" type="text/javascript"></script>
        <script src="./assets/js/knob.js" type="text/javascript"></script>
        <?foreach($scripts as $script):?>
        	<script src="./assets/js/<?=$script;?>.js" type="text/javascript"></script>
        <?endforeach;?>


    </body>
</html>
