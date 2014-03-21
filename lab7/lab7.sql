--Nathaniel Thompson
--Lab 7
--CS3380 
--3/21/14

--------------
--Question 1--
--------------

/*
  The query plan shows that an index will be scanned because it will be the only way for us to 
  check each record to see if id=17317.  This index is called banks_pkey and was created when we 
  first created the banks table.
*/

--------------
--Question 2--
--------------

--Query to select all of the banks in Missouri
SELECT * FROM banks WHERE state = 'Missouri';  

--Query plan before index
/*                                              QUERY PLAN                                              
  ------------------------------------------------------------------------------------------------------
  Seq Scan on banks  (cost=0.00..894.98 rows=996 width=33) (actual time=0.289..8.517 rows=996 loops=1)
  Filter: ((state)::text = 'Missouri'::text)
  Total runtime: 8.625 ms
  (3 rows)
*/
  
--Index creating command
CREATE INDEX state_index  ON banks (state);

--Query plan after index
/*
                                                       QUERY PLAN                                                        
  -------------------------------------------------------------------------------------------------------------------------
  Bitmap Heap Scan on banks  (cost=23.97..598.42 rows=996 width=33) (actual time=0.458..1.161 rows=996 loops=1)
  Recheck Cond: ((state)::text = 'Missouri'::text)
  ->  Bitmap Index Scan on state_index  (cost=0.00..23.72 rows=996 width=0) (actual time=0.373..0.373 rows=996 loops=1)
  Index Cond: ((state)::text = 'Missouri'::text)
  Total runtime: 1.275 ms
  (5 rows)
*/

/*With the new index the query runs 7.35ms faster than before which is an 85.21% increse in speed.*/

--------------
--Question 3--
--------------

--Query for banks ordered by names
SELECT * FROM banks ORDER BY (name);

--Query plan before index
/*
                                                   QUERY PLAN                                                    
  -----------------------------------------------------------------------------------------------------------------
  Sort  (cost=4657.15..4726.14 rows=27598 width=124) (actual time=180.867..266.373 rows=27598 loops=1)
  Sort Key: name
  Sort Method: external merge  Disk: 3760kB
  ->  Seq Scan on banks  (cost=0.00..825.98 rows=27598 width=124) (actual time=0.009..3.367 rows=27598 loops=1)
  Total runtime: 269.241 ms
  (5 rows)
*/

--Index creating command
CREATE INDEX name_index on banks(name);

--Query plan after index
/*
                                                           QUERY PLAN                                                           
  --------------------------------------------------------------------------------------------------------------------------------
  Index Scan using name_index on banks  (cost=0.00..3294.27 rows=27598 width=124) (actual time=0.104..16.716 rows=27598 loops=1)
  Total runtime: 18.372 ms
  (2 rows)
*/

/*With the new index the query runs 250.869ms faster! Which is a 93.18% increase!*/

--------------
--Question 4--
--------------

--Index creating command
CREATE INDEX active_index ON banks(is_active);

--------------
--Question 5--
--------------

/* The query "SELECT * FROM banks WHERE is_active = TRUE;" uses the active_index while the 
   query "SELECT * FROM banks WHERE is_active = FALSE;" does not use the active_index.
   By running "SELECT count(*) FROM banks WHERE is_active = TRUE;" and "SELECT count(*) FROM banks WHERE is_active = TRUE;" 
   we find that there are 6776 banks that are active and 20822 banks that are not active.  That means that 75.44% of banks
   in the table are is_active = FALSE. Because of this it is faster to sequentialy read the table than to reference the index.
*/

--------------
--Question 6--
--------------

--Query
SELECT * FROM banks WHERE insured > '20000101';

--Query plan before indexing
/*
                                              QUERY PLAN                                               
  -------------------------------------------------------------------------------------------------------
  Seq Scan on banks  (cost=0.00..894.98 rows=1450 width=8) (actual time=2.174..7.475 rows=1448 loops=1)
  Filter: (insured > '2000-01-01'::date)
   Total runtime: 7.570 ms
  (3 rows)
*/

--Index creating command
CREATE INDEX insured_index ON banks(insured) WHERE insured != '19340101';

--Query plan after index
/*
                                                         QUERY PLAN                                                          
  -----------------------------------------------------------------------------------------------------------------------------
  Index Scan using insured_index on banks  (cost=0.00..573.89 rows=1450 width=8) (actual time=0.084..1.172 rows=1448 loops=1)
  Index Cond: (insured > '2000-01-01'::date)
  Total runtime: 1.308 ms
  (3 rows)
*/

/*Without the index the query ran in 7.570ms and after the query it ran in 1.308ms a reduction of 6.262ms or 82.72% faster*/

--------------
--Question 7--
--------------

--Query
SELECT id, name, city, state, assets, deposits FROM banks WHERE deposits > 0 AND (assets/deposits) < .5;

--Query plan before index
/*
                                               QUERY PLAN                                                
  ---------------------------------------------------------------------------------------------------------
  Seq Scan on banks  (cost=0.00..1032.97 rows=8531 width=63) (actual time=25.110..31.296 rows=46 loops=1)
  Filter: ((deposits > 0::numeric) AND ((assets / deposits) < 0.5))
  Total runtime: 31.332 ms
  (3 rows)
*/

--Index creating command
CREATE INDEX ratio_index ON banks((assets/deposits)) WHERE deposits !=0;

--Query plan after index
/*
                                                        QUERY PLAN                                                        
  --------------------------------------------------------------------------------------------------------------------------
  Bitmap Heap Scan on banks  (cost=215.38..925.79 rows=8531 width=63) (actual time=0.126..0.197 rows=46 loops=1)
  Recheck Cond: (((assets / deposits) < 0.5) AND (deposits <> 0::numeric))
  Filter: (deposits > 0::numeric)
  ->  Bitmap Index Scan on ratio_index  (cost=0.00..213.25 rows=9166 width=0) (actual time=0.104..0.104 rows=46 loops=1)
  Index Cond: ((assets / deposits) < 0.5)
  Total runtime: 0.238 ms
  (6 rows)
*/

/*
  Without the index the query ran ar 31.332ms but after the index it ran at .238ms! That was a decrease 
  by 31.094 or a 99.24% increase in speed. That's crazy!
*/


