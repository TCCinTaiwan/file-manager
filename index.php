<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>檔案管理系統</title>

		<link rel="stylesheet" type="text/css" href="css/contextmenu.css" /><!-- 右鍵選單樣式 -->
		<link rel="stylesheet" type="text/css" href="css/style.css" /><!-- 主要樣式 -->
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.structure.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.theme.css" />
		<link rel="stylesheet" type="text/css" href="css/font-awesome.css" />

		<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script><!-- jquery -->
		<script type="text/javascript" src="js/jquery-ui.js"></script><!-- jquery UI -->
		<!-- <script type="text/javascript" src="js/contextmenu.js"></script>
		<script type="text/javascript" src="js/main.js"></script> -->

		<!-- 主要-->
		<script type="text/javascript">
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

				evt.preventDefault();//取消預設處理方式
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
				var fd_upload = new FormData();//要傳過去給upload.php的資料
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
					if (evt.lengthComputable)
					{
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
			function allowDrop(evt) {
			    evt.preventDefault();
			}
		</script>
		<!-- 右鍵選單 -->
		<script type="text/javascript">
			try
			{
				var xhr2 = new XMLHttpRequest();
			}
			catch (tryMS) 
			{
				try 
				{
					var xhr2 = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch (otherMS) 
				{
					try 
					{
						var xhr2 = new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch (failed)
					{
						var xhr2 = null;
					}
				}
			}
			var id='';
			var type='';
			var name='';
			document.addEventListener('click', function(e) 
			{
				document.getElementById("contextmenu").style.visibility="hidden";
			}, false);
			function dir_contextmenu(evt)//顯示右鍵選單(資料夾)
			{
				evt.preventDefault();
				_x=evt.clientX;
				_y=evt.clientY;
				type='dir';
				id=evt.target.title;
				name=evt.target.innerText;
				document.getElementById("contextmenu").style.left=_x+"px";
				document.getElementById("contextmenu").style.top=_y+"px";
				document.getElementById("contextmenu").style.visibility="visible";
				document.getElementById("contextmenu_download").style.color="#CCCCCC";
			}
			function file_contextmenu(evt)//顯示右鍵選單(檔案)
			{
				evt.preventDefault();
				_x=evt.clientX;
				_y=evt.clientY;
				type='file';
				id=evt.target.title;
				name=evt.target.innerText;
				document.getElementById("contextmenu").style.left=_x+"px";
				document.getElementById("contextmenu").style.top=_y+"px";
				document.getElementById("contextmenu").style.visibility="visible";
				document.getElementById("contextmenu_download").style.color="#000000";
			}
			function delete_file()
			{
				if (confirm("刪除"+(type==='file'?"檔案":"資料夾")))
				{
					var fd_delete_file = new FormData();
					fd_delete_file.append('path', path);
					fd_delete_file.append('id', id);
					fd_delete_file.append('type', type);
					fd_delete_file.append('name', name);
					xhr2.open('POST','del.php');
					xhr2.onload = function() 
					{
						//完成
						displayList(path);
					};
					xhr2.send(fd_delete_file);
				}
			}
			function download_file()
			{
				if (type==='file')
				{
					if (confirm("下載檔案?"))
					{
						window.open('download.php?id='+id+'&name='+name);
 						//newwin.location= ;
					}
				}
			}
			function rename_file()
			{
				//var newname=prompt("重新命名"+(type==='file'?"檔案":"資料夾")+"為",name.replace(/\/$/, ""));
				$("#Dialog").html("重新命名"+(type==='file'?"檔案":"資料夾")+"為<br/><input type='text' id='DialogText' value='"+name.replace(/\/$/, "")+"'/>");
			    $("#Dialog").dialog({
			    	dialogClass: "no-close",
			        resizable: false,
			        modal: true,
			        title: '重新命名'+(type==='file'?"檔案":"資料夾"),
			        height: 250,
			        width: 400,
			        buttons: {
			            "確定": function () {
							$(this).dialog('close');
							var newname=$('#DialogText').val();
							var fd_rename_file = new FormData();
							fd_rename_file.append('path',path);
							fd_rename_file.append('id',id);
							fd_rename_file.append('name',newname);
							fd_rename_file.append('oldname',name);
							fd_rename_file.append('type',type);
							xhr2.open('POST','rename.php');
							xhr2.onload = function() 
							{
								//完成
								displayList(path);
							};
							xhr2.send(fd_rename_file);
							
			            },
			            "取消": function () {
			                $(this).dialog('close');
			                var newname= null;
			            }
			        },
			        open: function() {
						$("#Dialog").keypress(function(e) {
							if (e.keyCode == $.ui.keyCode.ENTER) {
								$(this).parent().find("button:eq(1)").trigger("click");
							}
						});
					}
			    });
			}
			function move_file()
			{
				//var new_path=prompt("移動"+(type==='file'?"檔案":"資料夾")+"到\n必須是已存在的路徑",path.replace(/\/$/, ""));
				$("#Dialog").html("移動"+(type==='file'?"檔案":"資料夾")+"到<br/>必須是已存在的路徑<br/><input type='text' id='DialogText' value='"+path.replace(/\/$/, "")+"'/>");
			    $("#Dialog").dialog({
			    	dialogClass: "no-close",
			        resizable: false,
			        modal: true,
			        title: '移動'+(type==='file'?"檔案":"資料夾"),
			        height: 250,
			        width: 400,
			        buttons: {
			            "確定": function () {
							$(this).dialog('close');
							var new_path=$('#DialogText').val();
							if  ((path+name)==new_path)
							{
								alert('不能移到自己');
							} 
							else if (new_path!=null)
							{
								var fd_rename_file = new FormData();
								fd_rename_file.append('path',path);
								fd_rename_file.append('newpath',new_path);
								fd_rename_file.append('id',id);
								fd_rename_file.append('name',name);
								fd_rename_file.append('type',type);
								xhr2.open('POST','move.php');
								xhr2.onload = function() 
								{
									//完成
									displayList(path);
								};
								xhr2.send(fd_rename_file);
							}
			            },
			            "取消": function () {
			                $(this).dialog('close');
			                var newname= null;
			            }
			        },
			        open: function() {
						$("#Dialog").keypress(function(e) {
							if (e.keyCode == $.ui.keyCode.ENTER) {
								$(this).parent().find("button:eq(1)").trigger("click");
							}
						});
					}
			    });

				
			}
			function addnewdir()
			{
				//var newname=prompt("新增資料夾命名為","新資料夾");

    			$("#Dialog").html("新增資料夾命名為<br/><input type='text' id='DialogText' value='新資料夾'/>");
			    $("#Dialog").dialog({
			    	dialogClass: "no-close",
			        resizable: false,
			        modal: true,
			        title: '新增資料夾',
			        height: 250,
			        width: 400,
			        buttons: {
			            "確定": function () {
							$(this).dialog('close');
							var newname=$('#DialogText').val();
							if (newname=='')
							{
								alert('名稱不能為空白!!');
							}
							else if (newname!=null)
							{
								var fd_addnewdir = new FormData();
								fd_addnewdir.append('path', path);
								fd_addnewdir.append('name', newname);
								xhr2.open('POST','add_dir.php');//add_dir.php
								xhr2.onload = function() 
								{
									//完成
									displayList(path);
								};
								xhr2.send(fd_addnewdir);
							}
			            },
			            "取消": function () {
			                $(this).dialog('close');
			                var newname= null;
			            }
			        },
			        open: function() {
						$("#Dialog").keypress(function(e) {
							if (e.keyCode == $.ui.keyCode.ENTER) {
								$(this).parent().find("button:eq(1)").trigger("click");
							}
						});
					}
			    });
			}
			function cancelEvent(evt)//缺重複名稱判斷
			{
				evt.preventDefault();
				//window.event.returnValue=false;
			}
			$(function () 
			{
	            $("input[type='file']").bind("change", function (event) 
	            {

	                if (event.target.files.length > 0) //判斷選擇檔案大於一
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
						var fd_upload = new FormData();//要傳過去給upload.php的資料
						fd_upload.append('path', path);
						for (var i = 0; i < event.target.files.length; i++) 
	                    {
	                    	if (typeof(event.target.files[i].type) != "undefined")// 判斷是檔案
							{
	 							fd_upload.append('files[]', event.target.files[i]);
	 						}
	                    }
						xhr_upload.open('POST','upload.php');//傳資料給upload.php
						xhr_upload.onload = function() 
						{
							//上傳完成
							displayList(path);
						};
						xhr_upload.send(fd_upload);//開始上傳
	                }
	            });
        	});
		</script>
	</head>
	<body ondragover='cancelEvent(event);' ondrop='cancelEvent(event);'>
		<div id="main" oncontextmenu='cancelEvent(event);'>
			<div id="menu" oncontextmenu='cancelEvent(event);'>
				<div onclick="displayList('')" oncontextmenu='cancelEvent(event);'><i class="fa fa-home"></i>回主目錄</div>
				<div onclick="addnewdir()" oncontextmenu='cancelEvent(event);'><i class="fa fa-plus"></i>新增資料夾</div>
				<div onclick="$('#selectFile').click()" oncontextmenu='cancelEvent(event);'><i class="fa fa-upload"></i>上傳檔案</div>
				<!-- <div>+新增空白檔案</div> -->
			</div>
			<div id="file" ondragover='dragOverHandler(event)' ondrop='dropHandler(event)' oncontextmenu='cancelEvent(event);'></div>
		</div>
		<div id='contextmenu' oncontextmenu='cancelEvent(event);'>
			<div id='contextmenu_delete' onclick='delete_file()' oncontextmenu='cancelEvent(event);'>刪除</div>
			<div id='contextmenu_rename' onclick='rename_file()' oncontextmenu='cancelEvent(event);'>重新命名</div>
			<div id='contextmenu_move' onclick='move_file()' oncontextmenu='cancelEvent(event);'>移動到...</div>
			<div id='contextmenu_download' onclick='download_file()' oncontextmenu='cancelEvent(event);'>下載</div>
			<!-- <div id='contextmenu_property'>屬性</div> -->
		</div>
		<input id="selectFile" type="file" multiple="multiple" data-id="fileUpload" style="display:none"/>
		<div id="Dialog"></div><!-- 使用者輸入 -->

	</body>
</html>