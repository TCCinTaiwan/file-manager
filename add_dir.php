<?
	if (!empty($_POST["path"])) 
	{
		$path=$_POST['path'];
	}
	else
	{
		$path='';
	}
	if (!empty($_POST["name"])) 
	{
		$name=$_POST['name'];
	}
	else
	{
		$name='';
	}
	$name.=((!preg_match('/\/$/', $name))?"/":"");
	include_once('connect.php');
	$result=mysql_query("SELECT * FROM `contest`.`dir` WHERE `dir`.`filename` = '".$name."' AND `dir`.`path` = '".$path."';");
	//echo mysql_num_rows($result);
	if (mysql_num_rows($result)==0)
	{
		$sql = "INSERT INTO `contest`.`dir` (`id`, `filename`, `path`) VALUES (NULL, '".$name."', '".$path."');";
		mysql_query($sql);
	}
?>
