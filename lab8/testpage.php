<!doctype>
<html>
<body>
<?php
session_start();
include("logic.php");
if(check_username_and_password("hashtest","password")==1)
	  {
	  	echo $username;
	  	$_SESSION['username'] = "hashtest";
	  	header("Location: home.php");
	  }
	  else
	  	echo "Bad username or password, try again";
?>
</body>
</html>