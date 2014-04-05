<!doctype>
<html>
<body>
<?php
include('logic.php');
	session_start();
	if($_SESSION['username']==NULL)
	{
		header("Location: index.php");
	}
	else
	{
		echo "<h3>Welcome ".$_SESSION['username']."</h3><br>";
		echo "Description: ";
		print_desc($_SESSION['username']);
		echo "<br>";
	}
?>
	You registered on <?php echo registration_date($_SESSION['username']); ?><br>
	Using the ip address <?php echo first_ip($_SESSION['username']); ?> <br>
<div>Past logins<?php print_logs($_SESSION['username']); ?></div>
<div><a href = "update.php">Update</a> <a href = "logout.php">Logout</a></div>
</body>
</html>