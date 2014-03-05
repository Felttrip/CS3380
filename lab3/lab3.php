<!DOCTYPE html>
<html>
<head>
	<title>Lab 3</title>
	<meta http-equiv="Content-Type" content="text/plain; charset=utf-8">
</head>
<body>
	<h3>Nate Thompson Lab 3|<a href="../index.php">Home</a></h3>
	<?php
	$contents= file_get_contents("lab3.sql");
	echo "<pre>$contents</pre>";
	?>
</body>
</html>
