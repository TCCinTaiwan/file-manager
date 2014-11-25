<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
	if (!empty($_POST["newpath"])) 
	{
		$newpath=$_POST['newpath'];
	}
	else
	{
		$newpath='';
	}
	if (!empty($_POST["id"])) //檔案id
	{
		$id=$_POST['id'];
	}
	else
	{
		$id='';
	}
	if (!empty($_POST["type"])) //類型(資料夾或檔案)
	{
		$type=$_POST['type'];
	}
	else
	{
		$type='';
	}
	if (!empty($_POST["name"])) //檔案名字
	{
		$name=$_POST['name'];
	}
	else
	{
		$name='';
	}
	include_once('connect.php');
	////////////////
//	preg_split('/[^\/]*\//', $newpath)[count(preg_split('/[^\/]*\//', $newpath))-1]
	if ($newpath!='')
	{
		$newpath.=((!preg_match('/\/$/', $newpath))?"/":"");
	}
	$result=mysql_query("SELECT * FROM `dir` WHERE `dir`.`filename` = '".preg_split('/\//', $newpath)[count(preg_split('/\//', $newpath))-2]."/' AND `dir`.`path` = '".preg_replace('/[^\/]*\/$/', '', $newpath)."';");//
	//echo "SELECT * FROM `dir` WHERE `dir`.`filename` = '".preg_split('/\//', $newpath)[count(preg_split('/\//', $newpath))-2]."/' AND `dir`.`path` = '".preg_replace('/[^\/]*\/$/', '', $newpath,1)."';";
	if ((mysql_num_rows($result)!=0) || ($newpath==''))
	{
		if ($type==='dir')
		{
			//已知Bug:移動到子資料夾
			echo '!!!';
			$sql = "UPDATE `".$type."` SET `path` = '".$newpath."' WHERE `".$type."`.`id` = ".$id.";";
			mysql_query($sql);


			$sql = "SELECT * FROM `file` WHERE `file`.`path` LIKE \"".$path.$name."%\";";
		    $result = mysql_query($sql) or die('MySQL query error');
		    while($row = mysql_fetch_array($result)){
		        
		        $sql = "UPDATE `file` SET `path` = '".str_replace($path.$name, $newpath.$name, $row['path'])."' WHERE `file`.`id` = '".$row['id']."';";
		        mysql_query($sql);
		    }

		    $sql = "SELECT * FROM `dir` WHERE `dir`.`path` LIKE \"".$path.$name."%\";";
		    $result = mysql_query($sql) or die('MySQL query error');
		    while($row = mysql_fetch_array($result)){
		        $sql = "UPDATE `dir` SET `path` = '".str_replace($path.$name, $newpath.$name, $row['path'])."' WHERE `dir`.`id` = '".$row['id']."';";
		        mysql_query($sql);
		    }
			
		}
		else
		{
			$sql = "UPDATE `".$type."` SET `path` = '".$newpath."' WHERE `".$type."`.`id` = ".$id.";";
			mysql_query($sql);
		}
	}
?>