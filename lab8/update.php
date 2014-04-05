<?php session_start();
include('logic.php');
	if(isset($_POST['Save']))
	{
		update_desc($_SESSION['username'],$_POST['description']);
		header("Location: home.php");
	}
?>
<!doctype>
<html>
<head></head>
<body>
	<h3>Update Profile</h3>
	<div>Username: <?php echo $_SESSION['username']; ?></div>
	<div>
		Description:
		<form id='update' action="<?= $_SERVER['PHP_SELF'] ?>" method='post'>
			<textarea rows="10" cols="30" name = "description"><?php print_desc($_SESSION['username']); ?></textarea>
			<br>
			<input type='submit' name='Save' value='Save' />

		</form>

	</div>
	<a href = "home.php">Home</a>
</body>
</html>
