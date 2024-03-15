<!DOCTYPE html>
<html>
<head>
<title>Logout</title>
</head>
<body>
	<?php session_start();
        if(!isset($_SESSION['id'])){
		$_SESSION['userName'] = "N/A";
        }?>
	<p>You have logged out.</p>
	<form action='home.php' method='POST'>
		<input type='submit' name='return' value='Return to Menu'>
	</form>
</body>
</html>