
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
	if (!empty($_POST["id"])) //檔案id
	{
		$id=$_POST['id'];
	}
	else
	{
		$id='';
	}
	if (!empty($_POST["type"])) //檔案id
	{
		$type=$_POST['type'];
	}
	else
	{
		$type='';
	}
	if (!empty($_POST["name"])) //檔案id
	{
		$name=$_POST['name'];
	}
	else
	{
		$name='';
	}
	include_once('connect.php');
	for ($i=0;$i<count($name);$i++)
	{
		$sql = "DELETE FROM `file-manager`.`".$type[$i]."` WHERE `".$type[$i]."`.`id` = ".$id[$i].";";
		mysqli_query($conn, $sql);
		if ($type[$i]==='dir')
		{
			//$sql = "SELECT FROM `file-manager`.`file` WHERE `file`.`path` = ".$path.$name.";";
			echo $path.$name[$i];
			//這邊要把資料夾內的檔案刪掉
			$sql = "SELECT * FROM `file-manager`.`file` WHERE `file`.`path` LIKE \"".$path.$name[$i]."%\";";
		    $result = mysqli_query($conn, $sql) or die('MySQL query error');
		    while($row = mysqli_fetch_array($result)){
		        unlink($uploads_dir.$row['id']);
		    }
	        $sql = "DELETE FROM `file-manager`.`file` WHERE `file`.`path` LIKE \"".$path.$name[$i]."%\";";
	        mysqli_query($conn, $sql);

			$sql = "DELETE FROM `file-manager`.`dir` WHERE `dir`.`path` LIKE \"".$path.$name[$i]."%\";";
			mysqli_query($conn, $sql);
		}
		else
		{
			unlink($uploads_dir.$id[$i]);
		}
	}
	
?>