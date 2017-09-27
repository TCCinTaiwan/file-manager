<?
	$dbhost = '127.0.0.1';
	$dbuser = 'admin';
	$dbpass = 'password';
	$dbname = 'file-manager';
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
	mysqli_query($conn, "SET NAMES 'utf8'");
	mysqli_select_db($conn, $dbname);
?>