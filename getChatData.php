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



//Headers are sent to prevent browsers from caching.. IE is still resistent sometimes
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
header( "Cache-Control: no-cache, must-revalidate" ); 
header( "Pragma: no-cache" );
header("Content-Type: text/html; charset=utf-8");





//if the request does not provide the id of the last know message the id is set to 0
$lastID = $_GET["lastID"];

if (!$lastID) {
	$lastID = 0;
}




// retrieves all messages with an id greater than $lastID
getData($lastID);




// establishes a connection to a mySQL Database accroding to the details specified in db.php
function getDBConnection () {
	include("db.php"); //contains the given DB setup $db, $server, $user, $pass
	$conn = mysql_connect($server, $user, $pass);
	if (!$conn) {
			//echo "Connection to DB was not possible!";
			end;
		}
		if (!mysql_select_db($db, $conn)) {
			//echo "No DB with that name seems to exist at the server!";
			end;
		}
		return $conn;
}






// retrieves all messages with an id greater than $lastID
function getData($lastID) {
	$sql = 	"SELECT * FROM chat WHERE id > ".$lastID." ORDER BY id ASC LIMIT 60";
	$conn = getDBConnection();
	$results = mysql_query($sql, $conn);
	if (!$results || empty($results)) {
		//echo 'There was an error creating the entry';
		end;
	}
	while ($row = mysql_fetch_array($results)) {
		//the result is converted from the db setup (see initDB.php)
		$name = $row[2];
		$text = $row[3];
		$id = $row[0];
		if ($name == '') {
			$name = 'no name';
		}
		if ($text == '') {
			$name = 'no message';
		}
		echo $id." ---".$name." ---".$text." ---"; // --- is being used to separete the fields in the output
	}
	echo "end";
}
?>