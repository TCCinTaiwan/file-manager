<?php
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
	mysql_query("ALTER TABLE file AUTO_INCREMENT = 1;");//檔案編號歸1
	mysql_query("ALTER TABLE dir AUTO_INCREMENT = 1;");//資料夾編號歸1
	if ($path!='')//假如不是ROOT增加返回上一層選項
	{
		echo "<div class='up_dir' onclick='displayList(\"".preg_replace('/[^\/]*\/$/', '', $path)."\");' oncontextmenu='cencel_event(event);'><i class='fa fa-arrow-up'></i>../</div>";
	}
	$sql = "SELECT * FROM `dir`;";
	$result = mysql_query($sql) or die('MySQL query error');
	while($row = mysql_fetch_array($result))
	{
		if ($row['path']==$path)
		{
			//preg_match('/\/$/',$row['filename'])//資料夾
			echo "<div class='dir' title='".$row['id']."' oncontextmenu='dir_contextmenu(event)' onclick='displayList(\"".$row['path'].$row['filename']."\");'><i class='fa fa-folder-open'></i>".$row['filename']."</div>";/////////////////////////////
		}
	}
	$sql = "SELECT * FROM `file`;";
	$result = mysql_query($sql) or die('MySQL query error');
	while($row = mysql_fetch_array($result))
	{
		if ($row['path']==$path)
		{
			echo "<div class='file' title='".$row['id']."'' oncontextmenu='file_contextmenu(event)'><i class='fa fa-file'></i>".$row['filename']."</div>";
		}
	}
?>