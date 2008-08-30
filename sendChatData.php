<?
/* 
XHTML live Chat
author: alexander kohlhofer
version: 1.0
http://www.plasticshore.com
http://www.plasticshore.com/projects/chat/
please let the author know if you put any of this to use
XHTML live Chat (including this code) is published under a creative commons license
license: http://creativecommons.org/licenses/by-nc-sa/2.0/
*/




$name =  $_POST["n"]; //name from the form in index.html
$text =  $_POST["c"];	//comment from the form in index.html

//some weird conversion of the data inputed
$name = str_replace("\'","'",$name);
$name = str_replace("'","\'",$name);
$text = str_replace("\'","'",$text);
$text = str_replace("'","\'",$text);
$text = str_replace("---"," - - ",$text);
$name = str_replace("---"," - - ",$name);

//the message is cut of after 500 letters
if (strlen($text) > 500) {
	$text = substr($text,0,500); 
}

//to allow for linebreaks a space is inserted every 50 letters
$text = preg_replace("/([^\s]{50})/","$1 ",$text);




//the name is shortened to 30 letters
if (strlen($name) > 30) {
	$name = substr($name, 0,30); 
}





//only if a name and a message have been provides the information is added to the db
if ($name != '' && $text != '') {
	addData($name,$text); //adds new data to the database
	getID(50); //some database maintenance
}






//establishes the connection to the database
function getDBConnection () {
	include('db.php'); //contains the given DB setup $db, $server, $user, $pass
	$conn = mysql_connect($server, $user, $pass);
	if (!$conn) {
		// echo "Connection to DB was not possible!";
		end;
	}
	if (!mysql_select_db($db, $conn)) {
		// echo "No DB with that name seems to exist at the server!";
		end;
	}
	return $conn;
}





//adds new data to the database
function addData($name,$text) {	
	$sql = "INSERT INTO chat (time,name,text) VALUES (NOW(),'".$name."','".$text."')";
	$conn = getDBConnection();
	$results = mysql_query($sql, $conn);
	if (!$results || empty($results)) {
		//echo 'There was an error creating the entry';
		end;
	}
}







//returns the id of a message at a certain position
function getID($position) {
	$sql = 	"SELECT * FROM chat ORDER BY id DESC LIMIT ".$position.",1";
	$conn = getDBConnection(); 
	$results = mysql_query($sql, $conn);
	if (!$results || empty($results)) {
		//echo 'There was an error creating the entry';
		end;
	}
	while ($row = mysql_fetch_array($results)) {
		$id = $row[0]; //the result is converted from the db setup (see initDB.php)
	}
	if ($id) {
		deleteEntries($id); //deletes all message prior to a certain id
	}
}







//deletes all message prior to a certain id
function deleteEntries($id) {
	$sql = 	"DELETE FROM chat WHERE id < ".$id;
	$conn = getDBConnection();
	$results = mysql_query($sql, $conn);
	if (!$results || empty($results)) {
		//echo 'There was an error deletig the entries';
		end;
	}
}
?>