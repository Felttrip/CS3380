<!DOCTYPE html>
<html>
  <head>
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
  include("../secure/database.php");
  $conn = pg_connect(HOST." ".DBNAME." ".USERNAME." ".PASSWORD) or die(pg_last_error());
  if($conn)
  {
    echo "<p>Good connect to DB</p>";
  }
  else
  {
    echo "<p> Failed to connect to DB</p>";
  }
  $value = $_POST['query'];
  switch ($value)
  {
    case 1:
      $query = "SELECT name,region,continent,government_form FROM lab2.country WHERE surface_area > 2000000";
      break;
    case 2:
      break;
    case 3:
      break;
    case 4:
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
    pg_query($query);
  
  
  pg_close($conn);
?>
