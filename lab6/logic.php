<?php
function options()
{
	for($i=1;$i<=10;$i++)
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
      $query = 'SELECT MIN(surface_area), MAX(surface_area),AVG(surface_area) 
                FROM lab6.country';
      break;
    case 2:
      $query = 'SELECT region, SUM(population) AS Total_Population, SUM(surface_area) AS Total_Surface_Area, SUM(gnp) AS Total_GNP
                FROM lab6.country
                GROUP BY region
                ORDER BY Total_GNP DESC';
      break;
    case 3:
      $query = 'SELECT country.government_form, COUNT(*), MAX(indep_year) AS most_recent_indep_year
                FROM lab6.country
                WHERE indep_year IS NOT NULL
                GROUP BY country.government_form
                ORDER BY COUNT(*) DESC';
      break;
    case 4:
      $query = 'SELECT  country.name, COUNT(*) AS num_citys
                FROM lab6.country JOIN lab6.city USING (country_code)
                GROUP BY(country.name)
                HAVING COUNT(*)>100 
                ORDER BY COUNT(*)';
      break;
    case 5:
      $query = 'SELECT co.name, country_population, urban_population, CAST(((urban_population/country_population)*100) AS FLOAT) AS percentage
                FROM 
                (SELECT country.name as name, CAST(MAX(country.population) AS FLOAT) AS country_population, CAST(SUM(city.population) AS FLOAT) AS urban_population
                FROM lab6.country JOIN lab6.city USING (country_code)
                GROUP BY(country.name)
                )AS pops, lab6.country AS co
                WHERE pops.name = co.name
                ORDER BY percentage';
      break;
    case 6:
      $query = 'SELECT pops.name, ci.name as cityName, pops.population 
                FROM 
                (SELECT country.name as name, MAX(city.population) AS population
                FROM lab6.country JOIN lab6.city USING (country_code)
                GROUP BY (country.name)
                )AS pops, lab6.city as ci
                WHERE ci.population = pops.population
                ORDER BY pops.population DESC';
      break;
    case 7:
      $query = 'SELECT  country.name, COUNT(*) AS num_citys
                FROM lab6.country JOIN lab6.city USING (country_code)
                GROUP BY(country.name) 
                ORDER BY COUNT(*) DESC, country.name';
      break;
    case 8:
      $query = 'SELECT country.name, capitals.name AS capital, cont_lang.lang_num 
                FROM
                    ( SELECT ci.name AS name, ci.country_code AS country_code
                      FROM lab6.city as ci, lab6.country AS co
                      WHERE ci.id = co.capital
                    ) AS capitals
                    JOIN
                    (
                      SELECT COUNT(*) AS lang_num, country.country_code AS country_code
                      FROM lab6.country_language JOIN lab6.country USING 
                      (country_code)
                      GROUP BY country.country_code
                      HAVING COUNT(*)>=8 AND COUNT(*) <= 12
                    ) AS cont_lang
                    USING(country_code)
                    JOIN
                    lab6.country
                    USING(country_code)
                    ORDER BY lang_num DESC, capitals.name DESC';
      break;
    case 9:
      $query = 'SELECT country.name, tmp.city, tmp.population, tmp.running_total FROM (
                  SELECT city.name as city, city.country_code, city.population,
                  SUM(population) OVER (PARTITION BY city.country_code ORDER BY city.population DESC
                    ) AS running_total
                  FROM lab6.city
                  ) AS tmp
                JOIN
                lab6.country USING (country_code)
                ORDER BY country.name, tmp.running_total';
      break;
    case 10:
      $query = 'SELECT country.name, tmp.language, tmp.rank_in_region FROM (
                SELECT country_code, language, 
                rank() OVER (
                  PARTITION BY country_code 
                  ORDER BY percentage DESC
                  ) AS rank_in_region 
                  FROM lab6.country_language
                  ) AS tmp
                  JOIN lab6.country USING (country_code)
                  ORDER BY country.name, tmp.rank_in_region';
      break;
  }
  return $query;
}
?>
