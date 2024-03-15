<!DOCTYPE html>
<html>
<head>
<title>Easy Parking</title>
<style>
	h1 {text-align: center;}
	p {text-align: center;}
</style>
</head>
<body>
	<?php session_start(); ?>
	<h1>Welcome to Easy Parking!</h1>
	<?php
	if(isset($_POST['admin'])){
		header("Location: adminLogin.php");
	}elseif(isset($_POST['user'])){
		header("Location: userLogin.php");
	}
	?>
	<form action='home.php' method='POST'>
		<p><b>I am a...</b><br></p>
		<p>
		<input type='submit' name='user' value='User'>
		<input type='submit' name='admin' value='Administrator'>
		</p>
	</form>
</body>
</html>