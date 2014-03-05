<html>
<head>
	<title>Lab 5</title>
</head>
<body>
	<h3>Nate Thompson Lab 5|<a href="../index.php">Home</a></h3>
	<?php
	$contents= file_get_contents("lab5.sql");
	echo "<pre>$contents</pre>";
	?>
</body>
</html>