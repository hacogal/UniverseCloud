<?php

include './inc/header.php';
require './inc/database.php';
require './inc/loggedin.php';

		//set up query
		$sql = "SELECT * FROM users";
		//run the query and store the results
		$result = $conn->query($sql);
		//start our table

        echo '<section class="masthead">';
       
		echo '<section class="person-row">';
		echo '<table class="table table-hover table-dark">
						<tr>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Username</th>
						</tr>';
		foreach ($result as $row) {
			echo '<tr>
							<td>' . $row['fname']  . '</td>
							<td>' . $row['lname']  . '</td>
							<td>' . $row['username']  . '</td>
					</tr>';
			}
		//close the table
		echo '</table>';
		echo '<a class="btn btn-warning" href="logout.php">Logout</a>';
		echo '</section>';
       
        echo '</section>';
		//disconnect
		$conn = null;
	
	
?>


<?php require './inc/footer.php';?>