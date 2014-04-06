<!doctype>
<html>
<head>
	<title>Lab 8</title>
</head>
<body>
<h3>Nate Thompson Lab 8|<a href="../index.php">Home</a></h3>
<form id='login' action="<?= $_SERVER['PHP_SELF'] ?>" method='post'>
		<fieldset >
			<legend>Login</legend>
			<label for='username' >UserName:</label>
			<input type='text' name='username' id='username' maxlength="50" required/> 
			<label for='password' >Password:</label>
			<input type='password' name='password' id='password' maxlength="50" required/>
			<input type='submit' name='Submit' value='Submit' />
		</fieldset>
	</form>

<?php
	include('logic.php');
	session_start();
	//if a session exists redirect to home
	if(isset($_SESSION['username']))
		header("Location: home.php");
	
	//on form submission
	if (isset( $_POST['Submit'] ) )
	{
		//collect username and password from post
		$username = htmlspecialchars($_POST['username']);
		$password = htmlspecialchars($_POST['password']);
		//get ip
		$ipaddress = $_SERVER["REMOTE_ADDR"];
		//set action
		$action = "login";
		//check credentials		
	  if(check_username_and_password($username,$password,$conn)==1)
	  {
	  	//set username
	  	$_SESSION['username'] = $username;
	  	//log the login
	  	store_login_data($username, $ipaddress, $action);
	  	header("Location: home.php");
	  }
	  else
	  	echo "Bad username or password, try again";
		
  }
?>
<br>
<a href = "registration.php">Register</a>
</body>
</html>