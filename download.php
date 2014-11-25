<?
	$uploads_dir = 'file/';//存放上傳檔案資料夾
	if (!empty($_GET["id"])) 
	{
		$id=$_GET['id'];
	}
	else
	{
		$id='';
	}
	if (!empty($_GET["name"])) 
	{
		$name=$_GET['name'];
	}
	else
	{
		$name='';
	}
	header("Content-type: text/html; charset=utf-8");
	header("Content-type: ".filetype("$uploads_dir$id"));
	header("Content-Disposition: attachment; filename=".$name."");
	readfile($uploads_dir.$id);
?>
