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
	$result=mysqli_query($conn, "SELECT * FROM `dir` WHERE `dir`.`filename` = '".$name."' AND `dir`.`path` = '".$path."';");
	//echo mysqli_num_rows($result);
	if (mysqli_num_rows($result)==0)
	{
		$sql = "INSERT INTO `dir` (`id`, `filename`, `path`) VALUES (NULL, '".$name."', '".$path."');";
		mysqli_query($conn, $sql);
	}
?>
