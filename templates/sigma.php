<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Visualisation</title>

		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" />

		
		<style type="text/css">
			html, body {  
				height: 100%;
				margin: 0;
				padding: 0;
				width: 100%;
			}
			body {
				background-attachment:fixed;
				background-repeat:no-repeat;
			}
			#attributepane {
				display: block;	
				display: none;
				position:absolute;
				height:auto;
				bottom:50px;
				top:50px;
				right:0;
				width: 440px;
				background-color: #fff;
				margin: 0;
				word-wrap: break-word;
				background-color:rgba(255,255,255,0.8);
				border-left: 1px solid #ccc;
				padding: 0px 18px 0px 18px;	
				z-index: 1;
				margin:0;
			}
			#attributepane .text {
				height: 100%;
			}
			#attributepane .header {
				height: 18%;
				padding-top:2%;
			}
			#attributepane .text h1 {
				margin:0!important;
				padding:0!important;
			}
			#attributepane .nodeattributes {
				display:block;
				height:80%;
				overflow-y: scroll;
				overflow-x: hidden;
				border-bottom:1px solid #999;
			}
			
			#identitypane {
				display: block;	
				position:absolute;
				height:auto;
				bottom:150px;
				top:150px;
				left:10px;
				width: 340px;
				background-color: #fff;
				margin: 0;
				word-wrap: break-word;
				background-color:rgba(255,255,255,0.8);
				border: 1px solid #ccc;
				padding: 10px;	
				z-index: 1;
				margin:0;
			}
			#identitypane .content {
				overflow:auto;
				max-height:85%;
			}
			#identitypane h1 {
				margin:0;
				padding:0;
				font-size:26px;
				height:15%;
			}
			#identitypane h4.panel-title {
				background-color:none;
				font-weight:bold;
			
			}
			#identitypane h5 {
				font-weight:bold;
			
			}
			#identitypane p {
				text-align:justify;
			}
			#legend {
				position:fixed;
				bottom:55px;
				height:80px;
				width:340px;
				left:10px;
			}
		</style>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
		<script src="./assets/js/sigma.min.js" charset="utf-8"></script>
		<script src="./assets/js/sigma.parseJSON.js" charset="utf-8"></script>
		<script src="./assets/js/sigma.forceatlas2.js" charset="utf-8"></script>
		<script src="./assets/js/sigma.FruchtermanReingold.js" charset="utf-8"></script>
		<script src="./assets/js/cicerosnetwork.js" charset="utf-8"></script>
	</head>
	<body>
		<div id="attributepane">
			<div class="text">
				<div class="header">
					<span class="text-right"><button type="button" class="close" aria-hidden="true">&times;</button></span>
					<h1>Information Pane  </h1>
					<a class="btn btn-success" href="#" id="item-layout">Scale to node</a>
					<a class="btn btn-warning clean-focus" href="#">Clean focus</a>
				</div>
				<div class="nodeattributes" id="data-show" style="display: block;">
				</div>
			</div>
		</div>
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<a class="navbar-brand" href="#">Cicero's Network</a>
			</div>
			<ul class="nav navbar-nav">
				<li><a href="#" id="stop-layout" data-toggle="popover" data-placement="bottom" data-content="Click here when the graph is stable">Stop Layout</a></li>
				<li><a href="#" id="rescale-graph">Rescale</a></li>
				<li><a href="#" id="identitypaneopen">About</a></li>
			</ul>
		</nav>

		<div id="svg-container" style="height: 100%; background-color:white;">
		</div>


	</body>
</html>
