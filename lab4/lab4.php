<!DOCTYPE HML>
<html>
<head>
<meta charset=UTF-8>
<title>Nathaniel Thompson Lab 3</title>
</head>
<body>

<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
    Search for a :
    <input type="radio" name="search_by" value="country"  />Country 
    <input type="radio" name="search_by" value="city"  />City
    <input type="radio" name="search_by" value="language"  />Language <br /><br />
    That begins with: <input type="text" name="query_string" value="" /> <br /><br />
    <input type="submit" name="submit" value="Submit" />
</form>
<hr />
Or insert a new city by clicking this <a href="exec.php?action=insert">link</a>

<?php
  //set up database
  include("../secure/database.php");
  $conn = pg_connect(HOST." ".DBNAME." ".USERNAME." ".PASSWORD) or die('Could not connect:' . pg_last_error());

  //grab variables from post
  $search_by = htmlspecialchars($_POST['search_by']);
  $query_part = $_POST['query_string'];

  //using pg_prepare
  $result = pg_prepare($conn, "country_query",'SELECT * FROM lab4.country as co WHERE co.name ILIKE $1 ORDER BY co.name');
  $result = pg_prepare($conn, "city_query",'SELECT * FROM lab4.city as ci WHERE ci.name ILIKE $1 ORDER BY ci.name');
  $result = pg_prepare($conn, "language_query",'SELECT * FROM lab4.country_language as la WHERE la.language ILIKE $1 ORDER BY la.language');

  //using pg_execute
  if($search_by=="country")
  {
  	$result = pg_execute($conn, "country_query", array($query_part."%"));
  }
  else if($search_by=="city")
  {
  	$result = pg_execute($conn, "city_query", array($query_part."%"));
  }
  else if($search_by=="language")
  {
  	$result = pg_execute($conn, "language_query", array($query_part."%"));

  }
  else
  {
  	$result = NULL;
  }
  if($result!=NULL)
	{
  	//print out number of rows
  	echo "\n<br/>\n<hr/>\n<br/>\n\nThere were <em>".pg_num_rows($result)."</em> rows returned<br/><br/>";
  
  	//Print table
  	echo "\n<table border=\"1\">\n\t<tr>\n";
  	//print field names
	  $numFields = pg_num_fields($result);
	  echo "\t\t<th>Actions</th>\n";
  	for($i=0; $i<$numFields; $i++)
		{
  	  $fieldName = pg_field_name($result, $i);
    	echo "\t\t<th>" . $fieldName . "</th>\n";
 	 	}
 	 	echo "\t</tr>\n";
  	//print rows and columns
  	while ($line = pg_fetch_array($result, null, PGSQL_NUM))
  	{
  		?>
    	<tr>
    	<td>
          <form method = "POST" action = "exec.php">
          <input type="submit" name="action" value="Edit" />
          <input type="submit" name="action" value="Remove" />
          <input type="hidden" name="table" value=<?php echo $search_by;?> />
          <input type="hidden" name="key" value=<?php echo $line[0];?> />
          </form>
      </td>
      <?php
    	foreach($line as $col_value)
    	{
      	echo "\t\t<td>$col_value</td>\n";
    	}
    	echo "\t</tr>\n";
  	}
  	echo "</table>\n";
  }
?>
</body
</html>
