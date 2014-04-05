<?php
include('../secure/database.php');
$conn = pg_connect(HOST." ".DBNAME." ".USERNAME." ".PASSWORD) or die('Could not connect:' . pg_last_error());

//query to check for usernames
$query = "SELECT * FROM lab8.user_info where username LIKE $1";
$result = pg_prepare($conn,"check_for_user",'SELECT * FROM lab8.user_info WHERE username LIKE $1');

//query to add users to user info table
$query = 'INSERT INTO lab8.user_info (username) VALUES ($1)';
$result = pg_prepare($conn, "add_user_info", $query);

//query to add users to the auth table must be in user_info table first
$query = 'INSERT INTO lab8.authentication (username, password_hash,salt) VALUES ($1,$2,$3)';
$result = pg_prepare($conn, "add_user_auth",$query);

//query to check username and hash
$query = 'SELECT username FROM lab8.authentication WHERE username LIKE $1 AND password_hash LIKE $2';

//query to return salt
$query = 'SELECT salt FROM lab8.authentication WHERE username LIKE $1';

function check_username_exists($username)
{
	$username = pg_escape_string(htmlspecialchars($username));
	$password = pg_escape_string(htmlspecialchars($password));
	$query = "SELECT * FROM lab8.user_info where username LIKE '".$username."'";
	$result = pg_query($query);
	//$result = pg_execute($conn,"check_for_user",array($username));
	if(pg_num_rows($result)==0)
		return 0;
	else
		return 1;
}

function add_user($username,$password)
{
	$username = pg_escape_string(htmlspecialchars($username));
	$password = pg_escape_string(htmlspecialchars($password));
	$salt = sha1(rand());
	$hash = sha1($password . $salt);
	$query = "INSERT INTO lab8.user_info (username) VALUES ('".$username."')";
	pg_query($query);
	$query = "INSERT INTO lab8.authentication (username, password_hash,salt) VALUES ('".$username."','".$hash."','".$salt."')";
	pg_query($query);
	//pg_execute($conn, "add_user_info",array($username));
	//pg_execute($conn,"add_user_auth",array($username,$hash,$salt));
}

function check_username_and_password($username,$password)
{
	$username = pg_escape_string(htmlspecialchars($username));
	$password = pg_escape_string(htmlspecialchars($password));
	//get salt and hash from db
	$query = "SELECT salt, password_hash FROM lab8.authentication WHERE username LIKE '".$username."'";
	$result = pg_query($query);
	$line = pg_fetch_array($result, null, PGSQL_ASSOC);
	$salt = $line['salt'];
	$db_hash = $line['password_hash'];

	//generate hash with salt and password
	$hash = sha1($password . $salt);
	//check salt and hash
	if($hash == $db_hash)
		return 1;
	else 
		return 0;
}

function registration_date($username)
{
	$username = pg_escape_string(htmlspecialchars($username));
	$query = "SELECT registration_date FROM lab8.user_info where username LIKE '".$username."'";
	$result = pg_query($query);
	$line = pg_fetch_array($result, null, PGSQL_ASSOC);
	return $line['registration_date'];

}
function first_ip($username)
{
	$username = pg_escape_string(htmlspecialchars($username));
	$query = "SELECT ip_address FROM lab8.log where username LIKE '".$username."' ORDER BY log_date desc LIMIT 1";
	$result = pg_query($query);	
	$line = pg_fetch_array($result, null, PGSQL_ASSOC);
	return $line['ip_address'];	
}
function print_logs($username)
{
	$username = pg_escape_string(htmlspecialchars($username));
	$query = "SELECT ip_address, log_date FROM lab8.log where username LIKE '".$username."' ORDER BY log_date desc";
	$result = pg_query($query);
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

function store_login_data($username, $ip, $action)
{
	$username = pg_escape_string(htmlspecialchars($username));
	$ip = pg_escape_string(htmlspecialchars($ip));
	$action = pg_escape_string(htmlspecialchars($action));
	$query = "INSERT INTO lab8.log (username, ip_address,action) VALUES ('".$username."','".$ip."','".$action."')";
	pg_query($query);
}

function update_desc($username, $description)
{
	$username = pg_escape_string(htmlspecialchars($username));
	$description = pg_escape_string(htmlspecialchars($description));
	$query = "UPDATE lab8.user_info SET description ='".$description."' WHERE username LIKE '".$username."'";
	pg_query($query);

}

function print_desc($username)
{
	$username = pg_escape_string(htmlspecialchars($username));
	$query = "SELECT description FROM lab8.user_info where username LIKE '".$username."'";
	$result = pg_query($query);
	$line = pg_fetch_array($result, null, PGSQL_ASSOC);
	echo $line['description'];

}

?>