<!doctype>
<html>
<head>
	<title>Register</title>
</head>
<body>
	<form id='register' action="<?= $_SERVER['PHP_SELF'] ?>" method='post'>
		<fieldset >
			<legend>Register</legend>
			<label for='username' >UserName*:</label>
			<input type='text' name='username' id='username' maxlength="50" /> 
			<label for='password' >Password*:</label>
			<input type='password' name='password' id='password' maxlength="50" />
			<input type='submit' name='Submit' value='Submit' />
		</fieldset>
	</form>


<?php
	session_start();
  include("logic.php");
  if ( isset( $_POST['Submit'] ) )
  {
		//collect username and password
		$username = htmlspecialchars($_POST['username']);
		$password = htmlspecialchars($_POST['password']);

		//check if the name exists
		if(check_username_exists($username)==0)
		{
			//create user if username is unique
			add_user($username,$password);
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;
			header("Location: home.php");
		}
		else
			echo "Already taken bro.";
	}
  ?>
  <br><a href="index.php">Back to login</a> 
</body>
</html>