<?php
/*
 *            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
 *                    Version 2, December 2004
 * 
 * Copyright (C) 2011 Alessandro Preziosi <lsnpreziosi+f@gmail.com>
 * Everyone is permitted to copy and distribute verbatim or modified
 * copies of this document, and changing it is allowed as long as the 
 * name is changed.
 *
 *            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
 *   TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
 * 
 *  0. You just DO WHAT THE FUCK YOU WANT TO.
 *
 * ===================================================================
 *  "I can't claim ownership over this document.
 *   I did not invent the language I'm speaking, the lamp lighting my 
 *   desk, or the computer I'm typing this on.
 *   We are dwarfs sitting on the shoulders of giants.
 *   It's our turn now. BE THE GIANT."
 */

#FOR DEBUG: (enable error reporting)
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);


$email = $_GET['email'];
$password = $_GET['password'];
$callback = $_GET['callback'];

$result = create_account($email,$password);
require_once("print_formatted_result.php");
$format='json';
print_formatted_result($result,$format,$callback);
/**
 *  This only puts the data in the database, but you still have to confirm
 *  in order to activate the account.
 *  
 */
function create_account($email,$password) {

	require_once('execute_query.php');
	$db = db_connect("flux_changer");

	$email = mysql_real_escape_string($email);
	$hash = md5(md5($password).md5($email));
	$cookie = md5($hash);

	$query = "INSERT INTO users SET ".
			" email = '$email',password = '$hash',cookie='$cookie';";

	$result = mysql_query($query,$db);
    if(!$result) {
        //query failed
		//TODO do something here
        die("query failed, query: ".$query."\n error:".mysql_error());
    }
	return $result;
}

