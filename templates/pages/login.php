<div class="row">
	<div class="col-md-6">
		<h1 class="text-center">Login</h1>
		<p class="text-center">
			<a href="/oAuth/Facebook">Login via Facebook</a>
			<br />or
		</p>

		<form action="/account/signin" method="POST" class="login-form" role="form">

			<?if(isset($error["signin"]) && isset($error["signin"]["message"]) ):?>
				<div class="alert alert-warning">
					<?=$error["signin"]["message"];?>
				</div>
			<?endif;?>

			<div class="form-group field">
				<label for="mail" class="control-label">Email</label>
				<div class="input">
					<input class="form-control" placeholder=" Email" type="email" id="mail" name="mail" value="" >
				</div>
			</div>
			<div class="form-group field">
				<label for="password" class="control-label">Password</label>
				<div class="input">
					<input class="form-control" placeholder=" Password" type="password" id="password" name="password" >
				</div>
			</div>
			<div class="form-group field">
				<div class="input">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-6">
		<form action="/account/signup" method="POST" class="login-form signup-form" role="form">
			<h1 class="text-center">Create an Account</h1>
			<?if(isset($error["signup"]) && isset($error["signup"]["message"]) ):?>
				<div class="alert alert-warning">
					<?=$error["signup"]["message"];?>
				</div>
			<?endif;?>
			<div class="checkPolicies">
				<p><input type="checkbox" id="checkPolicies" />By checking this box and suscribing you agree with the policies of the website (<a target="_blank" href="/policies">learn more</a>)</p>
			</div>
			<div class="form-group  field">
				<label for="name" class="control-label">Name</label>
				<div class="input">
					<input class="form-control" placeholder=" Name" type="text" id="name" name="name" value="" >
       				<?if(isset($error["signup"]) && isset($error["signup"]["name"])):?><span class="help-block text-muted"><?=$error["signup"]["name"]?></span><?endif;?>
				</div>
			</div>
			<div class="form-group  field">
				<label for="mail" class="control-label">Email</label>
				<div class="input">
					<input class="form-control" placeholder=" Email" type="email" id="mail" name="mail" value="" >
       				<?if(isset($error["signup"]) && isset($error["signup"]["mail"])):?><span class="help-block text-muted"><?=$error["signup"]["mail"]?></span><?endif;?>
				</div>
			</div>
			<div class="form-group  field">
				<label for="password" class="control-label">Password</label>
				<div class="input">
					<input class="form-control" placeholder=" Password" type="password" id="password" name="password" >
       				<?if(isset($error["signup"]) && isset($error["signup"]["password"])):?><span class="help-block text-muted"><?=$error["signup"]["password"]?></span><?endif;?>
				</div>
			</div>
			<div class="form-group  field">
				<label for="confirm" class="control-label">Confirm Password</label>
				<div class="input">
					<input class="form-control" placeholder=" Confirm Password" type="password" id="confirm" name="confirm" >
       				<?if(isset($error["signup"]) && isset($error["signup"]["confirm"])):?><span class="help-block text-muted"><?=$error["signup"]["confirm"]?></span><?endif;?>
				</div>
			</div>
			<div class="form-group field">
				<div class="input">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>