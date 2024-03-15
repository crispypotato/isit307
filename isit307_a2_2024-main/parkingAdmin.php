<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Easy Parking | Administrator</title>
</head>
<body>
	<?php
	echo "<table><tr><td><h3>What do you want to do today, ".$_SESSION['adminName']."?</h3></td>";
	echo "<td><form action='logout.php' method='POST'><input type='submit' value='Log out' /></form></td></tr></table>";

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "Parking";
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) { die("Connection failed"); }
	$dbtable = "parking_location";

	// parking locations table
	echo "<p><h4>Parking Locations</h4>";
	if(isset($_GET['available'])){
		$sql = "select * from $dbtable where capacity>0";
	}elseif(isset($_GET['full'])){
		$sql = "select * from $dbtable where capacity=0";
	}elseif(isset($_POST['searchP'])){
		if(!empty($_POST['id'])){
			echo "ID input is ignored.<br>";
		}
		$sql = "select * from $dbtable where ";
		if($_POST['location']!=''){
			$sql .= "location like '%";
			$sql .= $_POST['location'];
			$sql .= "%'";
		}
		if($_POST['description']!=''){
			if(strpos($sql,"like")!==false){
				$sql .= "and ";
			}
			$sql .= "description like '%";
			$sql .= $_POST['description'];
			$sql .= "%'";
		}
		if($_POST['capacity']!=''){
			if(strpos($sql,"like")!==false){
				$sql .= "and ";
			}
			$sql .= "capacity like '%";
			$sql .= $_POST['capacity'];
			$sql .= "%'";
		}
		if($_POST['cost']!=''){
			if(strpos($sql,"like")!==false){
				$sql .= "and ";
			}
			$sql .= "cost like '%";
			$sql .= $_POST['cost'];
			$sql .= "%'";
		}
		if($_POST['latecost']!=''){
			if(strpos($sql,"like")!==false){
				$sql .= "and ";
			}
			$sql .= "cost_late like '%";
			$sql .= $_POST['latecost'];
			$sql .= "%'";
		}
	}elseif(isset($_POST['add'])){
		if(!empty($_POST['id'])){
			echo "ID input is ignored.<br>";
		}
		$sql = "insert into $dbtable (location, description, capacity, cost, cost_late) values('".$_POST['location']."','".$_POST['description']."','".$_POST['capacity']."','".$_POST['cost']."','".$_POST['latecost']."')";
	}elseif(isset($_POST['edit'])){
		$sql = "update $dbtable set ";
		if($_POST['location']!=''){
			$sql .= "location='";
			$sql .= $_POST['location'];
			$sql .= "'";
		}
		if($_POST['description']!=''){
			if(strpos($sql,"=")!==false){
				$sql .= ", ";
			}
			$sql .= "description='";
			$sql .= $_POST['description'];
			$sql .= "'";
		}
		if($_POST['capacity']!=''){
			if(strpos($sql,"=")!==false){
				$sql .= ", ";
			}
			$sql .= "capacity='";
			$sql .= $_POST['capacity'];
			$sql .= "'";
		}
		if($_POST['cost']!=''){
			if(strpos($sql,"=")!==false){
				$sql .= ", ";
			}
			$sql .= "cost='";
			$sql .= $_POST['cost'];
			$sql .= "'";
		}
		if($_POST['latecost']!=''){
			if(strpos($sql,"=")!==false){
				$sql .= ", ";
			}
			$sql .= "cost_late='";
			$sql .= $_POST['latecost'];
			$sql .= "'";
		}
		$sql .= " where id=";
		$sql .= $_POST['id'];
	}else{
		$sql = "select * from $dbtable";
	}
	$result = $conn->query($sql);
	if(strpos($sql,"select")!==false){
		if ($result->num_rows > 0){
			echo "<table border='1'><tr><td>ID</td><td>Location</td><td>Description</td><td>Capacity</td><td>Cost ($)</td><td>Late Cost ($)</td></tr>";
			while ($row = $result->fetch_assoc()){
				echo "<tr><td>".$row["id"]."</td><td>".$row["location"]."</td><td>".$row["description"]."</td><td>".$row["capacity"]."</td><td>".$row["cost"]."</td><td>".$row["cost_late"]."</tr>";
			}
			echo "</table></p>";
		}else{
			echo "No records found.</p>";
		}
	}else{
		echo "Changes made successfully.";
	}

	echo "<p><form action='parkingAdmin.php' method='GET'>";
	echo "<input type='submit' name='allP' value='List all locations' />";
	echo "<input type='submit' name='available' value='List locations with available spaces' />";
	echo "<input type='submit' name='full' value='List locations that are full' />";

	echo "</form></p><p><form action='parkingAdmin.php' method='POST'><b>Search/Add/Edit a parking location:</b><br>";
	echo "<table><tr><td>Location:</td><td><input type='textfield' name='location' /></td>";
	echo "<td>Description:</td><td><input type='textfield' name='description' /></td></tr>";
	echo "<tr><td>Capacity:</td><td><input type='textfield' name='capacity' /></td>";
	echo "<td>Cost:</td><td><input type='textfield' name='cost' /></td></tr>";
	echo "<tr><td>Late Cost:</td><td><input type='textfield' name='latecost' /></td>";
	echo "<td>ID (for editing only):</td><td><input type='textfield' name='id' /></td></tr></table>";
	echo "<input type='submit' name='searchP' value='Search' />";
	echo "<input type='submit' name='add' value='Add location' />";
	echo "<input type='submit' name='edit' value='Edit location' />";
	echo "</form></p><hr />";

	//users table
	echo "<p><h4>List of Users</h4>";
	$dbtable = "users";
	$sql = "select * from $dbtable";
	if(isset($_POST['searchU'])){
		$sql = "select * from $dbtable where ";
		if($_POST['name']!=''){
			$sql .= "name like '%";
			$sql .= $_POST['name'];
			$sql .= "%'";
		}
		if($_POST['surname']!=''){
			if(strpos($sql,"like")!==false){
				$sql .= "and ";
			}
			$sql .= "surname like '%";
			$sql .= $_POST['surname'];
			$sql .= "%'";
		}
		if($_POST['email']!=''){
			if(strpos($sql,"like")!==false){
				$sql .= "and ";
			}
			$sql .= "email like '%";
			$sql .= $_POST['email'];
			$sql .= "%'";
		}
		if($_POST['phone']!=''){
			if(strpos($sql,"like")!==false){
				$sql .= "and ";
			}
			$sql .= "phone like '%";
			$sql .= $_POST['phone'];
			$sql .= "%'";
		}
		if($_POST['type']!=''){
			if(strpos($sql,"like")!==false){
				$sql .= "and ";
			}
			$sql .= "type like '%";
			$sql .= $_POST['type'];
			$sql .= "%'";
		}
	}else{
		$sql = "select * from $dbtable";
	}
	$result = $conn->query($sql);
	if ($result->num_rows > 0){
		echo "<table border='1'><tr><td>ID</td><td>Name</td><td>Surname</td><td>Email</td><td>Phone</td><td>Type</td></tr>";
		while ($row = $result->fetch_assoc()){
			echo "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td><td>".$row["surname"]."</td><td>".$row["email"]."</td><td>".$row["phone"]."</td><td>".$row["type"]."</tr>";
		}
		echo "</table></p>";
	}else{
		echo "No records found.</p>";
	}

	echo "<p><form action='parkingAdmin.php' method='GET'>";
	echo "<input type='submit' name='allU' value='List all users' />";
	echo "</form></p><p><form action='parkingAdmin.php' method='POST'><b>Search for a user:</b><br>";
	echo "<table><tr><td>Name:</td><td><input type='textfield' name='name' /></td>";
	echo "<td>Surname:</td><td><input type='textfield' name='surname' /></td></tr>";
	echo "<tr><td>Email:</td><td><input type='textfield' name='email' /></td>";
	echo "<td>Phone:</td><td><input type='textfield' name='phone' /></td></tr>";
	echo "<tr><td>Type:</td><td><input type='textfield' name='type' /></td></tr></table>";
	echo "<input type='submit' name='searchU' value='Search' />";
	echo "</form></p><hr />";

	echo "<p><b>Check-in/out</b><br>";
	if(is_null($_SESSION['checkin'])){
		$_SESSION['checkin'] = array();
	}
	echo "<form action='parkingAdmin.php' method='POST'>Enter the Parking Lot ID you wish to view: <input type='textfield' name='PLID' /><input type='submit' name='checkinlist' /></form></p>";
	if(isset($_POST['checkinlist'])){
		echo "<p><table border=1><tr><td>User ID</td><td>Parking Lot ID</td><td>Date</td><td>Check-in Time</td><td>Duration (in hours)</td></tr>";
		foreach($_SESSION['checkin'] as $cEntry){
			echo "<tr>";
			if($cEntry[1] == $_POST['PLID']){
				foreach($cEntry as $c){
					echo "<td>$c</td>";
				}
			}
			echo "</tr>";
		}
		echo "</table></p>";
	}else{
		echo "<p><b>List of checked-in parkings</b></p>";
		echo "<p><table border=1><tr><td>User ID</td><td>Parking Lot ID</td><td>Date</td><td>Check-in Time</td><td>Duration (in hours)</td></tr>";
		foreach($_SESSION['checkin'] as $cEntry){
			echo "<tr>";
			foreach($cEntry as $c){
				echo "<td>$c</td>";
			}
			echo "</tr>";
		}
	}
	echo "</table></p>";
	echo "<p><form action='parkingAdmin.php' method='POST'><b>Check-in/out a parking lot</b><br>";
	echo "Only User ID and Lot ID are needed for check-out.<br>";
	$dbtable = 'parking_location';
	if(isset($_POST['checkin'])){
		if(is_null($_SESSION['checkin'])){
			$_SESSION['checkin'] = array();
		}
		$sql = "select * from $dbtable where id=".$_POST['lid'];
		$result = $conn->query($sql);
		if ($result->num_rows > 0){
			while ($row = $result->fetch_assoc()){
				$tmp = array($_POST['uid'],$_POST['lid'],$_POST['date'],$_POST['time'],$_POST['hrs']);
				array_push($_SESSION['checkin'],$tmp);
				echo "Check-in successful.";
				$capacity = $row['capacity']-1;
				$sql = "update $dbtable set capacity=" .$capacity." where id=".$_POST['lid'];
			}
		}
		$result = $conn->query($sql);
	}
	if(isset($_POST['checkout'])){
		$sql = "select * from $dbtable where id=".$_POST['lid'];
		$result = $conn->query($sql);
		if ($result->num_rows > 0){
			while ($row = $result->fetch_assoc()){
				foreach($_SESSION['checkin'] as $cEntry){
					if(($cEntry[0] == $_POST['uid']) and ($cEntry[1] == $_POST['lid'])){
						$tmp = array_search($cEntry,$_SESSION['checkin']);
						array_push($_SESSION['checkout'],$_SESSION['checkin'][$tmp]);
						unset($_SESSION['checkin'][$tmp]);
						$capacity = $row['capacity']+1;
						$sql = "update $dbtable set capacity=" .$capacity." where id=".$_POST['lid'];
					}
				}
			}
		}
		$result = $conn->query($sql);
		echo "Check-out successful.";
	}

	echo "<table><tr><td>User ID:</td><td><input type='textfield' name='uid' /></td>";
	echo "<td>Lot ID:</td><td><input type='textfield' name='lid' /></td></tr>";
	echo "<tr><td>Check-in date (DD/MM/YYYY):</td><td><input type='textfield' name='date' /></td>";
	echo "<td>Time (24h format):</td><td><input type='textfield' name='time' /></td></tr>";
	echo "<tr><td>Duration of use (in hours):</td><td><input type='textfield' name='hrs' /></td></tr></table>";
	echo "<input type='submit' name='checkin' value='Check in' />";
	echo "<input type='submit' name='checkout' value='Check out' />";
	echo "</form></p>";

	?>
</body>
</html>
