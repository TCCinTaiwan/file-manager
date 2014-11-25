<?
	$dbhost = '127.0.0.1';
	$dbuser = 'root';
	$dbpass = 'csie110210';
	$dbname = 'contest';
	$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
	mysql_query("SET NAMES 'utf8'");
	mysql_select_db($dbname);
?>