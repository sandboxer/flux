<?php

include('API_common.php');

$username = $_GET['username'];
$password = $_GET['password'];

$result = create_account($username,$password);
require_once("print_formatted_result.php");
print_formatted_result($result,$format,$callback);

/**
 *  This only puts the data in the database, but you still have to confirm
 *  in order to activate the account.
 *  
 */
function create_account($username,$password) {

	require_once('execute_query.php');
	$db = db_connect("flux_changer");

	$username = mysql_real_escape_string($username);
	$hash = md5(md5($password).md5($username));

	$query = "INSERT INTO users SET ".
			" username='$username', hash = '$hash';";

	$result = mysql_query($query,$db);
    if(!$result) {
        //query failed
		//TODO do something here
        die("query failed, query: ".$query."\n error:".mysql_error());
    }

	//create the users'flux, this flux represents the user account, it only accumulates money
	//it cannot have receivers
	
	//this returns the last autoincrement id, that is the id of the just inserted user
	//are we sure this works and returns the new user's id 100% of the times??
	$id = mysql_insert_id();
	$query = "INSERT INTO fluxes SET owner=$id, userflux=1, ".
		"name='$username', description=''";

	$result = mysql_query($query,$db);
    if(!$result) {
        //query failed
		//TODO do something here
        die("query failed, query: ".$query."\n error:".mysql_error());
    }

	return $result;
}
		
