<?php
function options()
{
	for($i=1;$i<=12;$i++)
    {
        if($_POST['query']==$i)
          echo "\t<option value= \"".$i."\" selected>Query ".$i."</option>\n";
        else
          echo "\t<option value= \"".$i."\">Query ".$i."</option>\n";
    }
}

function get_query($value)
{
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
      $query = 'SELECT name, continent, region, indep_year, government_form,life_expectancy
                FROM lab2.country
                WHERE (continent != \'Africa\') AND (life_expectancy IS NOT NULL)
                ORDER BY life_expectancy LIMIT 20';
      break;
    case 11:
      $query = 'SELECT name, region, government_form, gnp, gnp_old, (gnp-gnp_old) AS delta 
                FROM lab2.country
                WHERE (gnp_old > gnp)
                ORDER BY delta';
      break;
    case 12:
      $query = 'SELECT name, round(((gnp/population)*1000000)::numeric,0) AS per_capita_gnp, life_expectancy, government_form
                FROM lab2.country
                WHERE (name IS NOT NULL) AND (population > 0)
                ORDER BY per_capita_gnp DESC';
      break;
  }
  return $query;
}
?>
