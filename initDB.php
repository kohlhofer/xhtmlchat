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




init(); //creates a new table in the databse





// establishes a connection to a mySQL Database
function getDBConnection () {
include("db.php"); //contains the given DB setup $db, $server, $user, $pass
$conn = mysql_connect($server, $user, $pass);
if (!$conn) {
		echo "Connection to DB was not possible!";
		end;
	}
if (!mysql_select_db($db, $conn)) {
		echo "No DB with that name seems to exist at the server!";
		end;
	}
return $conn;
}







//creates a new table in the databse
function init() {
	$sql = 	"
	CREATE TABLE chat (
  id mediumint(9) NOT NULL auto_increment,
  time timestamp(14) NOT NULL,
  name tinytext NOT NULL,
  text text NOT NULL,
  UNIQUE KEY id (id)
) TYPE=MyISAM
	";
	$conn = getDBConnection();
	$results = mysql_query($sql, $conn);		
	if (!$results || empty($results)) {
		echo 'There was an error creating the entry';
		end;
	}
}
?>