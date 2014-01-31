<!--Nathaniel Thompson
    CS3380 Lab1
    1/31/14 -->

<!--HTML givin from lab-->
<html>
<head/>
<body>
<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
  <table border="1">
     <tr><td>Number of Rows:</td><td><input type="text" name="rows" /></td></tr>
     <tr><td>Number of Columns:</td><td><select name="columns">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="4">4</option>
    <option value="8">8</option>
    <option value="16">16</option>

  </select>
</td></tr>
   <tr><td>Operation:</td><td><input type="radio" name="operation" value="multiplication" checked="yes">Multiplication</input><br/>
  <input type="radio" name="operation" value="addition">Addition</input>
  </td></tr>
  </tr><td colspan="2" align="center"><input type="submit" name="submit" value="Generate" /></td></tr>
</table>
</form>

<!--PHP written by Nathaniel Thompson-->
<?php
//check for button press
if(isset($_POST["submit"]))
{  
  //collect data from form
  $operation = $_POST['operation'];
  $rows = htmlspecialchars($_POST['rows']);
  $columns = $_POST['columns'];
  
  //check that rows is a number greater than 0
  if(is_numeric($rows)&&$rows>0)
  {
    //print out the table title
    echo "\nThe ".$rows." x ".$columns." ".$operation." table. ";
  
    //create the table
    echo "  <br /><table border=\"1\">\n";

    //populate the table with values using a nested for loop
    for($i=0;$i<=$rows;$i++)
    {
      echo "\t<tr>\n";
      echo "\t\t<td align=\"center\">";
      if(i>0)
      {
        echo $i;
      }
      else if(i==0)
      {
        echo "<strong>".$i."</strong>";
      }
      echo "</td>\n";
      for($j=1;$j<=$columns;$j++)
      {
        echo "\t\t<td align=\"center\">";
        if($i>0&&$operation=="multiplication")
        {
          echo $j*$i;
        }
        if($i>0&&$operation=="addition")
        {
          echo $j+$i;
        }
        if($i==0)
        {
          echo "<strong>".$j."</strong>";
        }
        echo "</td>\n";
      }
      echo "\t</tr>\n";
    }
    //end table
    echo "</table>";
  }
  else
    echo "Invalid rows and/or columns parameters";
}
?>
</body>
</html>
