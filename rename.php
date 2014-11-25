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
	if (!empty($_POST["oldname"])) //檔案名字
	{
		$oldname=$_POST['oldname'];
	}
	else
	{
		$oldname='';
	}
	include_once('connect.php');
	if ($type==='dir')
	{
		//判斷假如結尾不是/自動加一個
		$name.=((!preg_match('/\/$/', $name))?"/":"");
		$sql = "UPDATE `".$type."` SET `filename` = '".$name."' WHERE `".$type."`.`id` = ".$id.";";
		mysql_query($sql);
		//$sql = "SELECT FROM `file` WHERE `file`.`path` = ".$path.$name.";";
		//這邊要把資料夾內的檔案所屬資料夾更改
		
		$sql = "SELECT * FROM `file` WHERE `file`.`path` LIKE \"".$path.$oldname."%\";";
	    $result = mysql_query($sql) or die('MySQL query error');
	    while($row = mysql_fetch_array($result)){
	        
	        $sql = "UPDATE `file` SET `path` = '".str_replace($path.$oldname, $path.$name, $row['path'])."' WHERE `file`.`id` = '".$row['id']."';";
	        mysql_query($sql);
	    }

	    $sql = "SELECT * FROM `dir` WHERE `dir`.`path` LIKE \"".$path.$oldname."%\";";
	    $result = mysql_query($sql) or die('MySQL query error');
	    while($row = mysql_fetch_array($result)){
	        $sql = "UPDATE `dir` SET `path` = '".str_replace($path.$oldname, $path.$name, $row['path'])."' WHERE `dir`.`id` = '".$row['id']."';";
	        mysql_query($sql);
	    }

		// $sql = "UPDATE `dir` SET `path` = '".$path.$name."' WHERE `dir`.`path` = '".$path.$oldname."';";
		// mysql_query($sql);
	}
	else
	{
		$sql = "UPDATE `".$type."` SET `filename` = '".$name."' WHERE `".$type."`.`id` = ".$id.";";
		mysql_query($sql);
	}
?>
