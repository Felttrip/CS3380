<!--Nathaniel Thompson
    Lab 2 2/12/14 -->
<!DOCTYPE html>
<html>
<head>
<meta charset=UTF-8>
<title>Nathaniel Thompson Lab 6|<a href="../index.php">Home</a></title>
</head>
<body>
  <!--Set up form -->
  <form method = "POST" action = "<?= $_SERVER['PHP_SELF'] ?>">
    <select name = "query">
      <!--a little php to keep the selection on page refresh-->
      <?php
        for($i=1;$i<=12;$i++)
        {
          if($_POST['query']==$i)
            echo "\t<option value= \"".$i."\" selected>Query ".$i."</option>\n";
          else
            echo "\t<option value= \"".$i."\">Query ".$i."</option>\n";
        }
      ?>
    </select>
    <input type="submit" name="submit" value="Execute"/>
  </form>
    
<?php
  //set up database
  include("../secure/database.php");
  $conn = pg_connect(HOST." ".DBNAME." ".USERNAME." ".PASSWORD) or die('Could not connect:' . pg_last_error());
  $query = null;

  //switch case for querys
  $value = $_POST['query'];
  switch ($value)
  {
    case 1:
      $query = 'SELECT MIN(surface_area), MAX(surface_area),AVG(surface_area) 
                FROM lab6.country';
      break;
    case 2:
      $query = 'SELECT name, language, cl.percentage
                FROM lab6.country AS co, lab6.country_language AS cl 
                WHERE(cl.percentage>50) AND (cl.is_official = false) AND  (co.country_code=cl.country_code) 
                ORDER BY cl.percentage DESC';
      break;
    case 3:
      $query = 'SELECT DISTINCT language 
                FROM lab6.country AS co, lab6.country_language AS cl 
                WHERE(cl.percentage<10) AND (cl.is_official = true) 
                ORDER BY language';
      break;
    case 4:
      $query = 'SELECT ci.name AS city, ci.district, co.name as Country
                FROM lab6.country AS co, lab6.city as ci 
                WHERE (co.country_code=ci.country_code) 
                ORDER BY co.name, ci.population DESC, ci.name';
      break;
    case 5:
      $query = 'SELECT co.name, ci.name AS capital, language, round(((percentage/100)*co.population)::numeric,0) AS speakers 
                FROM lab6.country AS co, lab6.city AS ci, lab2.country_language AS cl 
                WHERE (co.capital = ci.id) AND (co.country_code=cl.country_code) AND (is_official=true) 
                ORDER BY co.name, speakers DESC, capital';
      break;
    case 6:
      $query = 'SELECT name AS city, district, population 
                FROM  lab6.city
                WHERE (population > 3500000)
                ORDER BY name';
      break;
    case 7:
      $query = 'SELECT ci.name AS City,ci.district,co.name AS Country
                FROM lab6.city AS ci, lab6.country AS co
                WHERE ci.name LIKE \'S%s\' AND (ci.country_code = co.country_code)
                ORDER BY co.name,ci.name';
      break;
    case 8:
      $query = 'SELECT DISTINCT co.name AS Country
                FROM lab6.country AS co, lab6.country_language AS cl
                WHERE (co.population >10000000) AND (cl.is_official=false) AND (co.country_code = cl.country_code) AND (cl.percentage>20)
                ORDER BY co.name';
      break;
    case 9:
      $query = 'SELECT name, indep_year, region,life_expectancy,gnp,government_form
                FROM lab6.country
                WHERE (indep_year IS NOT NULL)
                ORDER BY indep_year OFFSET 2 LIMIT 5';
      break;
    case 10:
      $query = 'SELECT name, continent, region, indep_year, government_form,life_expectancy
                FROM lab6.country
                WHERE (continent != \'Africa\') AND (life_expectancy IS NOT NULL)
                ORDER BY life_expectancy LIMIT 20';
      break;
    case 11:
      $query = 'SELECT name, region, government_form, gnp, gnp_old, (gnp-gnp_old) AS delta 
                FROM lab6.country
                WHERE (gnp_old > gnp)
                ORDER BY delta';
      break;
    case 12:
      $query = 'SELECT name, round(((gnp/population)*1000000)::numeric,0) AS per_capita_gnp, life_expectancy, government_form
                FROM lab6.country
                WHERE (name IS NOT NULL) AND (population > 0)
                ORDER BY per_capita_gnp DESC';
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
<!--close body and html-->
</body>
</html>
