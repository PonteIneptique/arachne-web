<div class="row">
	<div class="col-md-6">
		<h1 class="text-center">Login</h1>
		<p class="text-center">
		<a href="/oAuth/Facebook">Login via Facebook</a>
		<br />or
		</p>

		<form action="/account/signin" method="POST" class="login-form signup-form" role="form">
			<div class="form-group  field">
				<label for="email" class="control-label">Email</label>
				<div class="input">
					<input class="form-control" placeholder=" Email" type="email" id="email" name="email" value="" >
				</div>
			</div>
			<div class="form-group  field">
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
			<div class="form-group  field">
				<label for="name" class="control-label">Name</label>
				<div class="input">
					<input class="form-control" placeholder=" Name" type="text" id="name" name="name" value="" >
				</div>
			</div>
			<div class="form-group  field">
				<label for="email" class="control-label">Email</label>
				<div class="input">
					<input class="form-control" placeholder=" Email" type="email" id="email" name="email" value="" >
				</div>
			</div>
			<div class="form-group  field">
				<label for="password" class="control-label">Password</label>
				<div class="input">
					<input class="form-control" placeholder=" Password" type="password" id="password" name="password" >
				</div>
			</div>
			<div class="form-group  field">
				<label for="confirm" class="control-label">Confirm Password</label>
				<div class="input">
					<input class="form-control" placeholder=" Confirm Password" type="password" id="confirm" name="confirm" >
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