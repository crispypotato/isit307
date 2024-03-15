<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Easy Parking | Verify login</title>
</head>
<body>
	<h2>Verify Login</h2>

	<?php
	$errors = 0;
	$dbname = "Parking";
	try {
		$conn = mysqli_connect("localhost", "root", "",$dbname);
		$table = "users";
		$SQLstring = "SELECT id, name, surname, type FROM $table" . " where email='" . stripslashes($_POST['email']) ."' and phone='" . stripslashes($_POST['phone']) . "'";
		$qRes = mysqli_query($conn, $SQLstring);
		$row = mysqli_fetch_assoc($qRes);
		if (mysqli_num_rows($qRes)==0) {
			echo "<p>The e-mail address/phone number " . " combination entered is not valid. </p>\n";
			++$errors;
		}elseif($row['type']=="user"){
			echo "<p>You are not authorized to log in here.</p>";
			++$errors;
		}else{
			$id = $row['id'];
			$adminName = $row['name'] . " " . $row['surname'];
			echo "<p>Welcome back, $adminName!</p>\n";
			$_SESSION['id'] = $id;
			$_SESSION['adminName'] = $adminName;
		}
	}catch(mysqli_sql_exception $e){
		echo "<p>Error: unable to connect/insert record in the database.</p>";
		++$errors;
	}
	if ($errors > 0) {
		echo "<p>Please use your browser's BACK button to return " . " to the form and fix the errors indicated.</p>\n";
	}
	if ($errors == 0) {
		echo "<form method='post' " . " action='parkingAdmin.php?" . SID . "'>\n";
		echo "<input type='submit' name='submit' " . " value='Continue'>\n";
		echo "</form>\n"; 
	}
	?>
</body>
</html>
