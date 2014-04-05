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
	}
?>
	You registered on <?php echo registration_date($_SESSION['username']); ?>
<div>Past logins<?php print_logs($_SESSION['username']); ?></div>
<div><a href = "logout.php">Logout</a></div>
</body>
</html>