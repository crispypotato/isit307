<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Easy Parking</title>
</head>
<body>
	<?php
	echo "<table><tr><td><h3>What do you want to do today, ".$_SESSION['userName']."?</h3></td>";
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

	if(isset($_POST['searchP'])){
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
			$sql .= "latecost like '%";
			$sql .= $_POST['latecost'];
			$sql .= "%'";
		}
	}else{
		$sql = "select * from $dbtable where capacity>0";
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

	echo "</form></p><p><form action='parkingUser.php' method='POST'><b>Search for a parking location:</b><br>";
	echo "<table><tr><td>Location:</td><td><input type='textfield' name='location' /></td>";
	echo "<td>Description:</td><td><input type='textfield' name='description' /></td></tr>";
	echo "<tr><td>Capacity:</td><td><input type='textfield' name='capacity' /></td>";
	echo "<td>Cost:</td><td><input type='textfield' name='cost' /></td></tr>";
	echo "<tr><td>Late Cost:</td><td><input type='textfield' name='latecost' /></td></tr></table>";
	echo "<input type='submit' name='searchP' value='Search' />";
	echo "</form></p><hr />";

	// check in/out
	if(is_null($_SESSION['checkin'])){
		$_SESSION['checkin'] = array();
	}
	if(is_null($_SESSION['checkout'])){
		$_SESSION['checkout'] = array();
	}
	echo "<p><b>My checked-in parkings</b></p>";
	echo "<p><table border=1><tr><td>User ID</td><td>Parking Lot ID</td><td>Date</td><td>Check-in Time</td><td>Duration (in hours)</td></tr>";
	foreach($_SESSION['checkin'] as $cEntry){
		echo "<tr>";
		if($cEntry[0] == $_SESSION['id']){
			foreach($cEntry as $c){
				echo "<td>$c</td>";
			}
		}
		echo "</tr>";
	}
	echo "</table></p>";
	if(isset($_POST['checkin'])){
		if($_POST['hrs']>24){
			echo "Check-in duration cannot be more than 24 hours.";
		}else{
			$sql = "select * from $dbtable where id=".$_POST['id'];
			$result = $conn->query($sql);
			if ($result->num_rows > 0){
				while ($row = $result->fetch_assoc()){
					if($row['capacity']==0){
						echo "This parking lot is full, please try again.<br>";
					}else{
						$tmp = array($_SESSION['id'],$_POST['id'],$_POST['date'],$_POST['time'],$_POST['hrs']);
						array_push($_SESSION['checkin'],$tmp);
						$time = (int)$_POST['time'] + (((int)$_POST['hrs'])*100);
						if($time>2400 and (int) $_POST['hrs']<=24){
							$time -= 2400;
							if($time<100){
								$time = (string)$time;
								$time = "00".$time;
							}elseif($time<1000){
								$time = (string)$time;
								$time = "0".$time;
							}
							$dmy = explode("/", $_POST['date']);
							$newD = ((int)$dmy[0])+1;
							$date = (string)$newD."/".$dmy[1]."/".$dmy[2];
						}else{
							$date = $_POST['date'];
						}
						$cost = ((int)$_POST['hrs']) * ($row['cost']);
						echo "Remember to check out of parking lot ".$_POST['id']." on ".$date." ".$time."<br>Total cost: $".$cost."<br>Late cost(/h): $".$row['cost_late'];
						$capacity = $row['capacity']-1;
						$sql = "update $dbtable set capacity=" .$capacity." where id=".$_POST['id'];
					}
				}
			}
			$result = $conn->query($sql);
		}
	}
	if(isset($_POST['checkout'])){
		$sql = "select * from $dbtable where id=".$_POST['id'];
		$result = $conn->query($sql);
		if ($result->num_rows > 0){
			while ($row = $result->fetch_assoc()){
				foreach($_SESSION['checkin'] as $cEntry){
					if(($cEntry[0] == $_SESSION['id']) and ($cEntry[1] == $_POST['id'])){
						$tmp = array_search($cEntry,$_SESSION['checkin']);
						array_push($_SESSION['checkout'],$_SESSION['checkin'][$tmp]);
						unset($_SESSION['checkin'][$tmp]);
						$time = (int)$cEntry[3] + (((int)$cEntry[4])*100);
						$capacity = $row['capacity']+1;
						$cost = ((int)$cEntry[4]) * ($row['cost']);
						date_default_timezone_set('Singapore');
						$datenow = date("d/m/Y"); 
						$timenow = (int)date("Hi");
						if($datenow==$cEntry[2]){
							if($timenow<$time){
								$latecost = 0;
							}else{
								$latecost = ((int)($timenow/100-$time/100) * $row['cost_late']);
							}
						}else{ // compare by date-month-year
							$dmy = explode("/", $cEntry[2]);
							$dmynow = explode("/", $datenow);
							if($dmy[2]==$dmynow[2]){
								if($dmy[1]==$dmynow[1]){
									if($timenow<$time){
										$latecost = ((int)(24-$time/100)+($timenow/100)) * $row['cost_late'];
									}else{
										$latecost = (24+(int)($timenow/100-$time/100)) * $row['cost_late'];
									}
								}
							}else{
								$latecost = 100; // flat charge
							}
						}
						$sql = "update $dbtable set capacity=" .$capacity." where id=".$_POST['id'];
					}
				}
			}
		}
		$result = $conn->query($sql);
		echo "Check-out successful. Total cost = $".$cost." + late cost $".$latecost;
	}
	echo "<p><form action='parkingUser.php' method='POST'><b>Check-in/out a parking lot</b><br>";
	echo "Only Lot ID is needed for check-out.<br>";
	echo "<table><tr><td>Lot ID:</td><td><input type='textfield' name='id' /></td>";
	echo "<td>Check-in date (DD/MM/YYYY):</td><td><input type='textfield' name='date' /></td></tr>";
	echo "<tr><td>Time (24h format):</td><td><input type='textfield' name='time' /></td>";
	echo "<td>Duration of use (in hours):</td><td><input type='textfield' name='hrs' /></td></tr></table>";
	echo "<input type='submit' name='checkin' value='Check in' />";
	echo "<input type='submit' name='checkout' value='Check out' />";
	echo "</form></p>";
	echo "<p><b>My checked-out parkings</b></p>";
	echo "<p><table border=1><tr><td>User ID</td><td>Parking Lot ID</td><td>Date</td><td>Check-in Time</td><td>Duration (in hours)</td></tr>";
	foreach($_SESSION['checkout'] as $cEntry){
		echo "<tr>";
		if($cEntry[0] == $_SESSION['id']){
			foreach($cEntry as $c){
				echo "<td>$c</td>";
			}
		}
		echo "</tr>";
	}
	?>
</body>
</html>
