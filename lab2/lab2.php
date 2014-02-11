<!DOCTYPE html>
<html>
  <head>
    <meta charset=UTF-8>
    <title>Nathaniel Thompson Lab 2</title>

    <body>
      <form method = "POST" action = "<?= $_SERVER['PHP_SELF'] ?>">
      <select name = "query">
        <option value="1" >Query 1</option>
	<option value="2" >Query 2</option>
	<option value="3" >Query 3</option>
	<option value="4" >Query 4</option>
	<option value="5" >Query 5</option>
	<option value="6" >Query 6</option>
	<option value="7" >Query 7</option>
	<option value="8" >Query 8</option>
	<option value="9" >Query 9</option>
	<option value="10" >Query 10</option>
	<option value="11" >Query 11</option>
	<option value="12" >Query 12</option>
      </select>
     <input type="submit" name="submit" value="Execute"/>
    </form>
    </body>
  </head>
</html>
<?php
  //set up database
  include("../secure/database.php");
  $conn = pg_connect(HOST." ".DBNAME." ".USERNAME." ".PASSWORD) or die('Could not connect:' . pg_last_error());
  
  $value = $_POST['query'];
  switch ($value)
  {
    case 1:
      $query = 'SELECT name,region,continent,government_form FROM lab2.country WHERE surface_area > 2000000 ORDER BY name';
      echo "Countrys with a surface area over 2000000";
      break;
    case 2:
      $query = 'SELECT name, language FROM lab2.country AS co, lab2.country_language AS cl WHERE(cl.percentage>50) AND (cl.is_official = false) AND  (co.country_code=cl.country_code) ORDER BY language';
      break;
    case 3:
      $query = 'SELECT DISTINCT language FROM lab2.country AS co, lab2.country_language AS cl WHERE(cl.percentage<10) AND (cl.is_official = true) ORDER BY language';
      break;
    case 4:
      $query = 'SELECT ci.name, ci.district, co.name as CountryName FROM lab2.country AS co, lab2.city as ci WHERE (co.country_code=ci.country_code) ORDER BY co.name, ci.population DESC, ci.name';
      break;
    case 5:
      break;
    case 6:
      break;
    case 7:
      break;
    case 8:
      break;
    case 9:
      break;
    case 10:
      break;
    case 11:
      break;
    case 12:
      break;
    default:
      echo "<strong>Please select a query</strong>";
  }
  $result = pg_query($query) or die('Query failed' . pg_last_error());
    
  //Print results in HTML
  echo "<table border=\"1\">\n";
  while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
  {
    foreach($line as $col_value)
    {
      echo "\t\t<td>$col_value</td>\n";
    }
    echo "\t</tr>\n";
  }
  echo "</table>\n";
  pg_close($conn);
?>
