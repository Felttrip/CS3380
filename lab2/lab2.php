<!DOCTYPE html>
<html>
  <head>
    <meta charset=UTF-8>
    <title>Nathaniel Thompson Lab 2</title>
  </head>

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
    
<?php
  //set up database
  include("../secure/database.php");
  $conn = pg_connect(HOST." ".DBNAME." ".USERNAME." ".PASSWORD) or die('Could not connect:' . pg_last_error());
  
  $value = $_POST['query'];
  $query = null;
  switch ($value)
  {
    case 1:
      $query = 'SELECT name ,region,continent,government_form 
                FROM lab2.country 
                WHERE surface_area > 2000000 
                ORDER BY gnp DESC';
      break;
    case 2:
      $query = 'SELECT name, language, cl.percentage
                FROM lab2.country AS co, lab2.country_language AS cl 
                WHERE(cl.percentage>50) AND (cl.is_official = false) AND  (co.country_code=cl.country_code) 
                ORDER BY cl.percentage DESC';
      break;
    case 3:
      $query = 'SELECT DISTINCT language 
                FROM lab2.country AS co, lab2.country_language AS cl 
                WHERE(cl.percentage<10) AND (cl.is_official = true) 
                ORDER BY language';
      break;
    case 4:
      $query = 'SELECT ci.name AS city, ci.district, co.name as Country
                FROM lab2.country AS co, lab2.city as ci 
                WHERE (co.country_code=ci.country_code) 
                ORDER BY co.name, ci.population DESC, ci.name';
      break;
    case 5:
      $query = 'SELECT co.name, ci.name AS capital, language, round(((percentage/100)*co.population)::numeric,0) AS speakers 
                FROM lab2.country AS co, lab2.city AS ci, lab2.country_language AS cl 
                WHERE (co.capital = ci.id) AND (co.country_code=cl.country_code) AND (is_official=true) 
                ORDER BY co.name, speakers DESC, capital';
      break;
    case 6:
      $query = 'SELECT name AS city, district, population 
                FROM  lab2.city
                WHERE (population > 3500000)
                ORDER BY name';
      break;
    case 7:
      $query = 'SELECT ci.name AS City,ci.district,co.name AS Country
                FROM lab2.city AS ci, lab2.country AS co
                WHERE ci.name LIKE \'S%s\' AND (ci.country_code = co.country_code)
                ORDER BY co.name,ci.name';
      break;
    case 8:
      $query = 'SELECT DISTINCT co.name AS Country
                FROM lab2.country AS co, lab2.country_language AS cl
                WHERE (co.population >10000000) AND (cl.is_official=false) AND (co.country_code = cl.country_code) AND (cl.percentage>20)
                ORDER BY co.name';
      break;
    case 9:
      $query = 'SELECT name, indep_year, region,life_expectancy,gnp,government_form
                FROM lab2.country
                WHERE (indep_year IS NOT NULL)
                ORDER BY indep_year OFFSET 2 LIMIT 5';
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
    </body>
  </head>
</html>
