<?php session_start(); ?>
<?php require_once("dbstart.php"); ?>



<html lang="en">
<head>
    <title>Confirm Rental</title>
</head>
<body>

<?php 

	$size = $_SESSION['size'];
	$startdate = $_SESSION['startdate'];
	$enddate = $_SESSION['enddate'];
	$vehicleId = $_SESSION['vehicleId'];
	$price = $_SESSION['cost'];
	$firstname = $_REQUEST['firstname'];
	$lastname = $_REQUEST['lastname'];
	$birthdate = $_REQUEST['birthdate'];
	$streetaddress = $_REQUEST['streetaddress'];
	$city = $_REQUEST['city'];
	$state = $_REQUEST['state'];
	$zip = $_REQUEST['zip'];
	$email = $_REQUEST['email'];
	if($_REQUEST['company'] != null){
		$company = $_REQUEST['company'];
		}
	else{
		$company= null;
	}
	$startout = date("m/d/Y", strtotime($startdate));
	$endout = date("m/d/Y", strtotime($enddate));
	
	//duplicate check
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, "SELECT * FROM customer WHERE firstname = ? AND lastname = ? AND birthdate = ?");
	mysqli_stmt_bind_param($stmt, 'sss', $firstname, $lastname, $birthdate);
	//insert customer info
	$stmt1 = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt1, "INSERT INTO customer (firstname, lastname, birthdate, streetaddress, city, state, zip, email, company) VALUES (?,?,?,?,?,?,?,?,?)");
	mysqli_stmt_bind_param($stmt1, 'sssssssss', $firstname, $lastname, $birthdate, $streetaddress, $city, $state, $zip, $email, $company);
	//insert rental info
	$stmt2 = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt2, "INSERT INTO rental (customerId, vehicleId, startdate, enddate, cost) VALUES (?,?,?,?,?)");
	mysqli_stmt_bind_param($stmt2, 'iisss', $customerId, $vehicleId, $startdate, $enddate, $cost);
	

	
	echo"<p></p>";
	echo "You are renting Vehicle Number <strong>$vehicleId</strong> which is a ";
	if($size === 'van'){
		echo "<strong>van.</strong>";
	}
	else{
		echo  "<strong>$size</strong> foot truck.";
	}
	echo"<p></p>";
	echo "You're rental is from ";
	echo "<strong>$startout</strong>";
	echo " to ";
	echo "<strong> $endout </strong>";
	echo ".";
	echo "<p></p>";
	echo("<table border='1'>");
	echo
	"<tr><th>First Name</th><td>" .$firstname  . "</td></tr>"
    ."<tr><th>Last Name</th><td>"  .$lastname  . "</td></tr>"
	."<tr><th>Birth Date</th><td>"  .date("m/d/Y", strtotime($birthdate)) . "</td></tr>"
	."<tr><th>Street Address</th><td>"  .$streetaddress . "</td></tr>"
	."<tr><th>City</th><td>"  .$city  . "</td></tr>"
	."<tr><th>State</th><td>"  .$state  . "</td></tr>"
	."<tr><th>Zip Code</th><td>"  .$zip  . "</td></tr>"
	."<tr><th>Company</th><td>"  .$company . "</td></tr>";
	echo '</table>';
	echo"<p></p>";
	echo"<p></p>";
	echo "<p>Confirm that all the above information is correct and then click the submit button to finish.</p>" ;

	?>	

		<form action= "confirm.php" method="post">
		<input type="hidden" name= "hidden">
		<input type="submit" value="Submit" name="submit">
		</form>

			<?php
		
			if(isset($_POST['submit']))
			{
				mysqli_stmt_execute($stmt);
				mysqli_stmt_store_result($stmt);
				printf("Error: %s.\n", mysqli_stmt_error($stmt));
				if(mysqli_stmt_num_rows($stmt)===0){
					mysqli_stmt_execute($stmt1);
					$customerId = mysqli_insert_id($conn);
					printf("Error: %s.\n", mysqli_stmt_error($stmt1));
					mysqli_stmt_close($stmt1);
					mysqli_stmt_execute($stmt2);
					printf("Error: %s.\n", mysqli_stmt_error($stmt2));
					
				
				
				}
				 else{
					echo "<p> Have you rented from us before?</p>"; 
			?>
					<form  method="post" action= " 
				<?php
					if($_POST['repeat']=no)
					{	
						mysqli_stmt_execute($stmt1);
						$customerId = mysqli_insert_id($conn);
						printf("Error: %s.\n", mysqli_stmt_error($stmt1));
						mysqli_stmt_close($stmt1);
						mysqli_stmt_execute($stmt2);
						printf("Error: %s.\n", mysqli_stmt_error($stmt2));
				    }
					else
					{
					$customerId = mysqli_insert_id($conn);
					mysqli_stmt_execute($stmt2);
					printf("Error: %s.\n", mysqli_stmt_error($stmt2));
					
					} 
				?> 
				
				" >
				
				<input type="radio" name="repeat" 
				
				<?php
				
				if(isset($_POST['repeat']) && $_POST['repeat']=="yes"){ echo "checked"; 
				?>
				    value="yes"> Yes
				<?php 
				} 
				?>
				<?php 
				if(isset($_POST['repeat']) && $_POST['repeat']=="no") { echo "checked";
				?>
					value="no"> No
				<?php 
				} 
				?>	
				<input type="submit" value="submit" name="Confirm">
				</form>
				<?php
		}
			
		
						$rentId = mysqli_insert_id($conn);
						if($rentId <> 0 or NULL){
						echo "<p>Rental Registered</p>";}
						else{ echo "<p>Registration failed. </p>"; }
			 
			}
			
			mysqli_stmt_close($stmt);
			mysqli_stmt_close($stmt2);
			
		
		
		mysqli_close($conn);
		echo "<a href 'insert.php'>Home</a>";
		
		?>
	
</body>
</html>