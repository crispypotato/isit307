<!DOCTYPE html>
<?php
session_start();
if(is_null($_SESSION)){
	$_SESSION = array();
}
?>

<html>
<head>
<title>Easy Parking | Login/Register</title>
</head>
<body>
	<h3>Administrator Login</h3>
	<form method="post" action="verifyAdmin.php" >
		<p>Enter your e-mail address: <input type="text" name="email" /></p>
		<p>Enter your phone number: <input type="text" name="phone" /></p>
		<input type="reset" name="reset" value="Reset Login Form" />
		<input type="submit" name="login" value="Log In" />
	</form>
	<hr />
	<h3>New Administrator Registration</h3>
	<form method="post" action="registerAdmin.php" >
		<p>
			Name: <input type="text" name="nm" />
			Surname: <input type="text" name="surname" />
		</p>
		<p>Enter your e-mail address: <input type="text" name="email" /></p>
		<p>Enter your phone number: <input type="text" name="phone" /></p>
		<input type="reset" name="reset" value="Reset Registration Form" />
		<input type="submit" name="register" value="Register" />
	</form>

</body>
</html>