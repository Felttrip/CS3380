--Nathaniel Thompson
--CS 3380 Lab 5
--3/3/14
SET search_path = lab5;

--cardinals VIEW part 1
DROP VIEW IF EXISTS cardinals CASCADE;
CREATE VIEW cardinals AS 
SELECT pid, fname, lname 
FROM player INNER JOIN baseball_team 
ON baseball_team.tid = player.tid AND baseball_team.name LIKE 'Cardinals';

--cardinals_statistics VIEW part 2
DROP VIEW IF EXISTS cardinals_statistics;

CREATE VIEW cardinals_statistics AS
SELECT fname, lname, ROUND(CAST(hits AS numeric)/CAST(ab AS numeric),3) AS batting_average 
FROM statistics INNER JOIN cardinals 
ON cardinals.pid=statistics.pid;

--using EXISTS
SELECT name, city, league 
FROM baseball_team 
WHERE NOT EXISTS (SELECT player.tid 
		  FROM player 
		  WHERE player.tid=baseball_team.tid);

--using IN
SELECT fname,lname 
FROM player 
WHERE tid IN (SELECT tid 
	      FROM baseball_team 
              WHERE league LIKE 'NL');

--using NOT IN
SELECT * FROM position 
WHERE pos NOT IN(SELECT pos 
                 FROM played_by);
--using UNION
SELECT pid 
FROM played_by 
WHERE pos LIKE '1B' 
UNION 
SELECT pid 
FROM played_by 
WHERE pos LIKE 'RF';

--using INTERSECT
SELECT fname, lname 
FROM player INNER JOIN  played_by 
ON player.pid = played_by.pid AND pos LIKE '1B' 
INTERSECT 
SELECT fname, lname 
FROM player INNER JOIN  played_by 
ON player.pid = played_by.pid AND pos LIKE 'C';

--using WITH part 1
WITH royals AS (
	SELECT pid, fname, lname 
	FROM player INNER JOIN baseball_team 
	ON player.tid = baseball_team.tid AND baseball_team.name LIKE 'Royals') 
SELECT * 
FROM statistics INNER JOIN royals 
ON statistics.pid = royals.pid;

--using WITH part 2
WITH one_dude AS (
        SELECT DISTINCT player.pid
        FROM player
        WHERE player.pid  NOT IN (SELECT pid FROM cardinals)
        INTERSECT
        SELECT pid
        FROM statistics
        WHERE statistics.hits>=165
        INTERSECT
        SELECT pid
        FROM played_by
        WHERE pos LIKE 'LF'),
card_tid AS (
        SELECT tid
        FROM baseball_team
        WHERE name LIKE 'Cardinals')
UPDATE player SET tid = (SELECT * FROM card_tid) WHERE player.pid = (SELECT * FROM one_dude) ;
--uses card_tid for bonus

