<!DOCTYPE HTML>
<html>
<head>
<title>Nathaniel Thompson Lab 3</title>
</head>
<body>
<?php
	//set up database
 	include("../secure/database.php");
	$conn = pg_connect(HOST." ".DBNAME." ".USERNAME." ".PASSWORD) or die('Could not connect:' . pg_last_error());

	//grab post data
	if($post_action = $_POST["action"])
	{
		$table = $_POST["table"];
		$key = $_POST["key"];
	}
	$get_action = $_GET["action"];
	
  //save new city
	if($post_action == "save_insert")
	{
		 
		$name = htmlspecialchars($_POST["name"]);
		$country_code = htmlspecialchars($_POST["country_code"]);
		$district = htmlspecialchars($_POST["district"]);
		$population = htmlspecialchars($_POST["population"]);

		echo "saving".$name.$country_code.$district.$population."Return to <a href = \"lab4.php\">search page</a>\n";
		$result = pg_prepare($conn, "insert_query",'INSERT INTO lab4.city (name,country_code,district,population) VALUES ($1,$2,$3,$4)');
		$result = pg_execute($conn, "insert_query",array($name,$country,$district,$population));
		if ($result === false) {
    print pg_last_error();
    die('Query failed');
    }
	}

	//insert form
	else if($get_action == "insert")
	{
		//select all of the avalable contry codes for the list
		$result = pg_query("SELECT country_code, name FROM lab4.country");
?>
		<form method="POST" action="exec.php">
		<input type="hidden" name="action" value="save_insert" />
		Enter data for the city to be added: <br />
		<table border="1">
		<tr><td>Name</td><td><input type="text" name="name" /></td></tr>
		<tr><td>Country Code</td><td><select name="country_code">";

<?php		 //While loop to generate dropdown
					while($query = pg_fetch_array($result, null, PGSQL_NUM))
					{
						echo "<option value=\"$query[0]\">$query[1]</option>";
					}		
?>
	  <!--Remainder of form-->
		</select></td></tr>
		<tr><td>District</td><td><input type="text" name="district" /></td></tr>
		<tr><td>Population</td><td><input type="text" name="population" /></td></tr>
		</table>
		<input type="submit" value="Save" />
		<input type="button" value="Cancel" onclick="top.location.href='lab4.php'" />
		</form>

<?php
	}

	
	//edit form
	else if($post_action == "Edit")
	{
		echo "edit form";
	}

	//remove form
	else if($post_action == "Remove")
	{
		echo "table = ".$table;
		echo "key = ".$key;
		$result = pg_prepare($conn, "country_query",'DELETE FROM lab4.country as co WHERE co.country_code LIKE $1');
  	$result = pg_prepare($conn, "city_query",'DELETE FROM lab4.city as ci WHERE ci.name LIKE $1');
  	$result = pg_prepare($conn, "language_query",'DELETE FROM lab4.country_language as la WHERE la.language LIKE $1');
  	
  	if($table=="country")
  	{
  		$result = pg_execute($conn, "country_query", array($key));
  	}
  	else if($table=="city")
  	{
  		$result = pg_execute($conn, "city_query", array($key));
  	}
  	else if($table=="language")
  	{
  		$result = pg_execute($conn, "language_query", array($key));
  	}
  	else
  	{
  		$result = NULL;
  	}

			echo "Delete was successful\nReturn to <a href = \"lab4.php\">search page</a>";
	}
?>
</body>
</html>
