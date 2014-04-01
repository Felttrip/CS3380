<!DOCTYPE html>
<html>
<head>
	<title>Lab 7</title>
	<meta http-equiv="Content-Type" content="text/plain; charset=utf-8">
</head>
<body>
	<h3>Nate Thompson Lab 7|<a href="../index.php">Home</a></h3>
	<?php
	$contents= file_get_contents("lab7.sql");
	echo "<pre>$contents</pre>";
	?>
</body>
</html>
