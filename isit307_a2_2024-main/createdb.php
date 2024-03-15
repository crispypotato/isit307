<?php
// create database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Parking";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) { die("Connection failed"); }

$sql = "create database if not exists $dbname";
if ($conn->query($sql) == TRUE)
{ echo "Database exists or created","<br>"; }
else
{ echo "Error creating database","<br>"; }

$conn->close();

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed"); }

// create users table
$dbtable = "users";
$checktable = $conn->query("show tables like '$dbtable'");
$table_exists = $checktable->num_rows >=1;

if (!$table_exists)
{
	$sql = "create table $dbtable (
		id int(6) unsigned auto_increment primary key,
		name varchar(30) not null,
		surname varchar(30) not null,
		phone varchar(10) not null,
		email varchar(50) not null,
		type varchar(15) not null
		)";
	
	if ($conn->query($sql)==TRUE)
	{ echo "Table created","<br>"; }
	else
	{ echo "Error creating table","<br>"; }
}
else
{ echo "Table exists.<br>"; }

// list all user records
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed"); }

$sql = "select id, name, surname, phone, email, type from $dbtable";
$result = $conn->query($sql);
	
if ($result->num_rows > 0)
{
	echo "<table><tr><td>ID</td><td>Name</td><td>Surname</td><td>Phone</td><td>Email</td><td>Type</td></tr>";
	while ($row = $result->fetch_assoc())
	{
		echo "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td><td>".$row["surname"]."</td><td>".$row["phone"]."</td><td>".$row["email"]."</td><td>".$row["type"]."</tr>";
	}
	echo "</table>";
}
else{
	echo "No records found, inserting new records.<br>";
	// add records
	$sql = "insert into $dbtable (name, surname, phone, email, type) values ('Adam','Inone','61234567','adaminone@gmail.com','admin')";
	if ($conn->query($sql)==TRUE)
	{ echo "New record inserted","<br>"; }
	else{ echo "Error inserting record","<br>"; }

	$sql = "insert into $dbtable (name, surname, phone, email, type) values ('Peter','Parker','91234567','veryrealparker@gmail.com','user')";
	if ($conn->query($sql)==TRUE)
	{ echo "New record inserted","<br>"; }
	else{ echo "Error inserting record","<br>"; }
}

// create parking_location table
$dbtable = "parking_location";
$checktable = $conn->query("show tables like '$dbtable'");
$table_exists = $checktable->num_rows >=1;

if (!$table_exists)
{
	$sql = "create table $dbtable (
		id int(6) unsigned auto_increment primary key,
		location varchar(30) not null,
		description varchar(200) not null,
		capacity int not null,
		cost float not null,
		cost_late float not null
		)";
	
	if ($conn->query($sql)==TRUE)
	{ echo "Table created","<br>"; }
	else
	{ echo "Error creating table","<br>"; }
}
else
{ echo "Table exists.<br>"; }

$conn->close();

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed"); }

// list all parking_location records
$sql = "select id, location, description, capacity, cost, cost_late from $dbtable";
$result = $conn->query($sql);
	
if ($result->num_rows > 0)
{
	echo "<table><tr><td>ID</td><td>Location</td><td>Description</td><td>Capacity</td><td>Cost</td><td>Late Cost</td></tr>";
	while ($row = $result->fetch_assoc())
	{
		echo "<tr><td>".$row["id"]."</td><td>".$row["location"]."</td><td>".$row["description"]."</td><td>".$row["capacity"]."</td><td>".$row["cost"]."</td><td>".$row["cost_late"]."</tr>";
	}
	echo "</table>";
}
else{
	echo "No records found, inserting new records.<br>";
	// add records
	$sql = "insert into $dbtable (location, description, capacity, cost, cost_late) values ('Jurong','A parking space in Jurong','99','1.50','3')";
	if ($conn->query($sql)==TRUE)
	{ echo "New record inserted","<br>"; }
	else{ echo "Error inserting record","<br>"; }

	$sql = "insert into $dbtable (location, description, capacity, cost, cost_late) values ('Pasir Ris','A parking space in Pasir Ris','60','1','2.30')";
	if ($conn->query($sql)==TRUE)
	{ echo "New record inserted","<br>"; }
	else{ echo "Error inserting record","<br>"; }
}

$conn->close();

?>