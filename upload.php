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
			//print_r(mysqli_fetch_array(mysqli_query($conn, "SELECT `AUTO_INCREMENT`FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'file-manager'AND TABLE_NAME = 'file';")));
			move_uploaded_file($tmp_name, "$uploads_dir".mysqli_fetch_array(mysqli_query($conn, "SELECT `AUTO_INCREMENT`FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'file-manager'AND TABLE_NAME = 'file';"))[0]);
			$sql = "INSERT INTO `file` (`id`, `filename`, `path`) VALUES (NULL, '".$name."', '".$path."');";
			mysqli_query($conn, $sql);
		}
	}
?>
