<!--Nathaniel Thompson
    Lab 2 2/12/14 -->
<?php 
  include('../secure/database.php');
  include('logic.php');
  ?>
<!DOCTYPE html>
<html>
<head>
<meta charset=UTF-8>
<title>Lab 6</title>
</head>
<body>
  <h3>Nate Thompson Lab 6|<a href="../index.php">Home</a></h3>
  <!--Set up form -->
  <form method = "POST" action = "<?= $_SERVER['PHP_SELF'] ?>">
    <select name = "query">
      <?php options()?>
    </select>
    <input type="submit" name="submit" value="Execute"/>
  </form>
    
<?php
  //set up database
  $conn = pg_connect(HOST." ".DBNAME." ".USERNAME." ".PASSWORD) or die('Could not connect:' . pg_last_error());
  $query = null;

  //switch case for querys
  $value = $_POST['query'];
  $query = get_query($value);
  echo "<strong>Please select a query</strong>";
  //check for a query
  if($query)
  {
    //send query to db
    $result = pg_query($query) or die('Query failed' . pg_last_error());
    //print out number of rows
    echo "\n<br/>\n<hr/>\n<br/>\n\nThere were <em>".pg_num_rows($result)."</em> rows returned<br/><br/>";
    //Print table
    echo "\n<table border=\"1\">\n\t<tr>\n";
    //print field names
    $i = 0;
    $numFields = pg_num_fields($result);
    while ($i < $numFields)
    {
      $fieldName = pg_field_name($result, $i);
      echo "\t\t<th>" . $fieldName . "</th>\n";
      $i ++;
    }
    echo "\t</tr>\n";
    //print rows and columns
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
    {
      echo "\t<tr>\n";
      foreach($line as $col_value)
      {
        echo "\t\t<td>$col_value</td>\n";
      }
      echo "\t</tr>\n";
    }
    echo "</table>\n";
  }
  //close connetion with db
  pg_close($conn);
?>
<!--close body and html-->
</body>
</html>
