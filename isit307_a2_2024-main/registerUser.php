<?php
session_start();
$body = "";
$errors = 0;
$email = "";

if (empty($_POST['email'])) {
	++$errors;
	$body .= "<p>You need to enter an e-mail address.</p>\n";
	}
else {
	$email = stripslashes($_POST['email']);
	if (preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)*(\.[a-z]{2,3})$/i", $email) == 0) {
		
		++$errors;
		$body .= "<p>You need to enter a valid " . "e-mail address.</p>\n";
		$email = "";
	}
}

if (empty($_POST['phone'])) {
	++$errors;
	$body .= "<p>You need to enter a phone number.</p>\n"; 
	$phone = "";
}
else
	$phone = stripslashes($_POST['phone']);

if (!(empty($phone))) {
	if (strlen($phone) < 8) {
		++$errors;
		$body .= "<p>The phone number is too short.</p>\n";
		$phone = "";
	}
}

if ($errors == 0) {
    try {
        $conn = mysqli_connect ("localhost", "root", "");

	$dbname = "Parking";
	$result = mysqli_select_db($conn,$dbname);
        $table = "users";
        $sql = "SELECT count(*) FROM $table" . " where email='" . $email . "'";
        $qRes = mysqli_query($conn, $sql);
		$row = mysqli_fetch_row($qRes);
		if ($row[0]>0) {
			$body .= "<p>The email address entered (" . htmlentities($email) . ") is already registered.</p>\n";
			++$errors;
		}
	}
    catch (mysqli_sql_exception $e) {
        $body .= "<p>Unable to connect to the database </p>\n";
        ++$errors;
   }
}
if ($errors > 0) {
	$body .= "<p>Please use your browser's BACK button to return" . " to the form and fix the errors indicated.</p>\n";
}

if ($errors == 0) {
	$nm = stripslashes($_POST['nm']);
	$surname = stripslashes($_POST['surname']);
    try {
        $sql = "INSERT INTO $table " . " (name, surname, email, phone, type) " . " VALUES( '$nm', '$surname', '$email', '$phone', 'user'".")";
        mysqli_query($conn, $sql);
		$id = mysqli_insert_id($conn);
		$_SESSION['id'] = $id;
        mysqli_close($conn);
    }
    catch (mysqli_sql_exception $e) {
        $body .= "<p>Unable to insert record</p>";
        ++$errors;
    }
}
if ($errors == 0) {
	$name = $nm . " " . $surname;
	$body .= "<p>Thank you, $name. ";
	$body .= "Your new ID is <strong>" . $_SESSION['id'] . "</strong>.</p>\n";
}

if ($errors == 0) {
	$body .= "<form method='post' " . 	" action='parkingUser.php?PHPSESSID=" . session_id() . "'>\n";
	$body .= "<input type='submit' name='submit' " . " value='Continue'>\n";
	$body .= "</form>\n";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Easy Parking | Register</title>
</head>
<body>
<?php
echo $body;
?>
</body>
</html>
