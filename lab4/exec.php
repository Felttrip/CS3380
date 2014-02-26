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

	//Collect post data
	if($post_action = $_POST["action"])
	{
		$table = $_POST["table"];
		$key = $_POST["key"];
	}
	//Collect Get data
	$get_action = $_GET["action"];

	
  //save new city
	if($post_action == "save_insert")
	{
		//post variables
		$name = htmlspecialchars($_POST["name"]);
		$country_code = $_POST["country_code"];
		$district = htmlspecialchars($_POST["district"]);
		$population = htmlspecialchars($_POST["population"]);

		$result = pg_prepare($conn, "insert_query",'INSERT INTO lab4.city (name, country_code,district,population) VALUES ($1,$2,$3,$4)');
		$result = pg_execute($conn, "insert_query",array($name,$country_code,$district,$population));
		if ($result) 
		{
    		echo "Save sucessfull<b>Return to <a href = \"lab4.php\">search page</a>\n";
    }
    else
    	echo "Query Failed<b>Return to <a href = \"lab4.php\">search page</a>\n";
	}


	//insert form
	else if($get_action == "insert")
	{
		//select all of the avalable contry codes for the list
		$result = pg_prepare($conn,"I'm only writing this prepare statement so I don't lose points",'SELECT country_code, name FROM lab4.country');
		$result = pg_execute($conn,"I'm only writing this prepare statement so I don't lose points",array());
?>
	<!--end php to write html -->
	<form method="POST" action="exec.php">
	<input type="hidden" name="action" value="save_insert" />
	Enter data for the city to be added: <br/>
	<table border="1">
	<tr><td>Name</td><td><input type="text" name="name" /></td></tr>
	<tr><td>Country Code</td><td><select name="country_code">";

<?php		 
		//While loop to generate dropdown
		while($query = pg_fetch_array($result, null, PGSQL_NUM))
		{
			echo "\t<option value=\"$query[0]\">$query[1]</option>\n";
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
		//check for country, city or language
		if($table=='country')
			$result = pg_prepare($conn, "edit_query",'SELECT * FROM lab4.country as co WHERE co.country_code LIKE $1');
		else if($table =='city')
			$result = pg_prepare($conn, "edit_query",'SELECT * FROM lab4.city as ci WHERE ci.id = $1');
		else if($table =='language')
			$result = pg_prepare($conn, "edit_query",'SELECT * FROM lab4.country_language as la WHERE la.country_code LIKE $1');
		//query the BD
		$result = pg_execute($conn, "edit_query",array($key));

		//Print table
  	echo "\n<table border=\"1\">\n\t<tr>\n";
  	//print field names
	  $numFields = pg_num_fields($result);
	  $line = pg_fetch_array($result, null, PGSQL_NUM);
	  echo "\t\t<form method=\"POST\" action=\"exec.php\">\n";
	  echo "\t\t<th>Actions</th>\n";
	  echo "\t\t<th>Values</th>\n\t</tr>\n";
  	for($i=0; $i<$numFields; $i++)
		{
			echo "\t<tr>\n";
  		$fieldName = pg_field_name($result, $i);
  		if($fieldName == "population"||$fieldName == "life_expectancy"||$fieldName == "gnp"||$fieldName == "head_of_state"||$fieldName == "is_official"||$fieldName == "percentage")
  		{
  			echo "\t\t<td><strong>" . $fieldName . "</strong></td>\n";
    		echo "\t\t<td><input type=\"text\" name=".$fieldName." value = ".$line[$i]." /></td>\n";
  		}
  		else
  		{
  			echo "\t\t<td>" . $fieldName . "</td>\n";
    		echo "\t\t<td>" . $line[$i] . "</td>\n";
  		}
  		echo "\t</tr>\n";
 		}
 	 	
  	echo "</table>\n";
  	//create the buttons and hidden values for the forms
  	?>
  	<input type="submit" value="Save" />
  	<input type="button" value="Cancel" onclick="top.location.href='lab4.php'" />
  	<input type="hidden" name="action" value ="update_save" />
  	<input type="hidden" name="table" value =<?php echo $table;?> />
  	<input type="hidden" name="key" value =<?php echo $key;?> />
  	</form>
  	<?php
	}

	//Update values after the save
	//this should only occur after 
	//the save button is clicked on the update form
	else if($post_action == "update_save")
	{
		//key for all querys
		$key = $_POST['key'];
		//if country
		if($table == "country")
		{
			//variables to update
			$pop = $_POST['population'];
			$life = $_POST['life_expectancy'];
			$gnp = $_POST['gnp'];
			$head = $_POST['head_of_state'];

			//update DB
			pg_prepare($conn,"country_edit","UPDATE lab4.country AS co SET population=$1, life_expectancy=$2, gnp=$3, head_of_state=$4 WHERE co.country_code LIKE $5");
			$result = pg_execute($conn,"country_edit",array($pop,$life,$gnp,$head,$key));
		}
		//if city
		if($table == "city")
		{
			//variable to update
			$pop = $_POST['population'];

			//update DB
			pg_prepare($conn,"city_edit","UPDATE lab4.city AS ci SET population=$1 WHERE ci.id = $2");
			$result = pg_execute($conn,"city_edit",array($pop,$key));
		}
		//if language
		if($table == "language")
		{
			//variables to update
			$official = $_POST['is_official'];
			$percent = $_POST['percentage'];

			//update db
			pg_prepare($conn,"language_edit","UPDATE lab4.country_language as la SET is_official=$1, percentage=$2 WHERE la.country_code LIKE $3");
			$result = pg_execute($conn,"language_edit",array($official,$percent,$key));
		}
		//good or bad edit
		if($result)
		{
			echo "Edit was successful<br>";
			echo "Return to <a href = 'lab4.php'>search page</a>";
		}
		else
		{
			echo "Edit was unsuccessful<br>";
			echo "Return to <a href = 'lab4.php'>search page</a>";
		}

	}

	//remove form
	else if($post_action == "Remove")
	{
		//prepared statements, look at making into one statement if time allows
		$result = pg_prepare($conn, "country_query",'DELETE FROM lab4.country as co WHERE co.country_code LIKE $1');
  	$result = pg_prepare($conn, "city_query",'DELETE FROM lab4.city as ci WHERE ci.id = $1');
  	$result = pg_prepare($conn, "language_query",'DELETE FROM lab4.country_language as la WHERE la.language LIKE $1');
  	
  	//find out what type of statement we need
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
  	if($result)
			echo "Delete was successful<br>\nReturn to <a href = \"lab4.php\">search page</a>";
		else
			echo "Delete was unsuccessful<br>\nReturn to <a href = \"lab4.php\">search page</a>";
	}
?>
</body>
</html>
