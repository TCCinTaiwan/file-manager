<?
	$uploads_dir = 'file/';//存放上傳檔案資料夾
	if (!empty($_POST["path"])) 
	{
		$path=$_POST['path'];
	}
	else
	{
		$path='';
	}
	include_once('connect.php');
	foreach ($_FILES["files"]["error"] as $key => $error) 
	{
		if ($error == UPLOAD_ERR_OK) 
		{
			$tmp_name = $_FILES["files"]["tmp_name"][$key];
			$name = $_FILES["files"]["name"][$key];
			//print_r(mysql_fetch_array(mysql_query("SELECT `AUTO_INCREMENT`FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'contest'AND TABLE_NAME = 'file';")));
			move_uploaded_file($tmp_name, "$uploads_dir".mysql_fetch_array(mysql_query("SELECT `AUTO_INCREMENT`FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'contest'AND TABLE_NAME = 'file';"))[0]);
			$sql = "INSERT INTO `contest`.`file` (`id`, `filename`, `path`) VALUES (NULL, '".$name."', '".$path."');";
			mysql_query($sql);
		}
	}
?>
