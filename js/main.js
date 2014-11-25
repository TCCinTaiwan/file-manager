var path='';
try
{
	var xhr = new XMLHttpRequest();
}
catch (tryMS) 
{
	try 
	{
		var xhr = new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch (otherMS) 
	{
		try 
		{
			var xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (failed)
		{
			var xhr = null;
		}
	}
}
window.onload=init();
function init()//初始化
{
	displayList('');
}
function dragOverHandler(evt)//拖曳事件
{
	evt.preventDefault();//取消預設處理方式
}
function dropHandler(evt)//放開事件
{
	try
	{
		var xhr_upload = new XMLHttpRequest();
	}
	catch (tryMS) 
	{
		try 
		{
			var xhr_upload = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (otherMS) 
		{
			try 
			{
				var xhr_upload = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (failed)
			{
				var xhr_upload = null;
			}
		}
	}
	evt.preventDefault();//取消預設處理方式
	var fd_upload = new FormData();//要傳過去給upload.php的資料
	var list = document.getElementById('file');//顯示檔案清單位置
	var upload_files = evt.dataTransfer.files;//要上傳的檔案
	fd_upload.append('path', path);
	for (var i in upload_files) 
	{
		if (typeof(upload_files[i].type) != "undefined")// 判斷是檔案
		{
			fd_upload.append('files[]', upload_files[i]);
		}
	}
	xhr_upload.open('POST','upload.php');//傳資料給upload.php
	xhr_upload.onload = function() 
	{
		//上傳完成
		displayList(path);
	};
	xhr_upload.upload.onprogress = function (evt) 
	{
		//上傳進度
		if (evt.lengthComputable) {
			var complete = (evt.loaded / evt.total * 100 | 0);
			if(100==complete){complete=99.9;}
			//complete+' %';
		}
	}
	xhr_upload.send(fd_upload);//開始上傳
}
function displayList(new_path)
{
	var fd = new FormData();//要傳過去給upload.php的資料
	path=new_path;
	fd.append('path', new_path);
	xhr.open('POST','list.php',true);//傳資料給upload.php
	xhr.onreadystatechange=function()
	{
		if (xhr.readyState == 4)
		{ // 確認 readyState
			if (xhr.status == 200)
			{ // 確認 status
				var list = document.getElementById("file"); // 取得顯示位置
				list.innerHTML = xhr.responseText;
			}
		}
	};
	xhr.send(fd);//開始傳資料給upload.php
}