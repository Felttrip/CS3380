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
  
  pg_close($conn);
?>
