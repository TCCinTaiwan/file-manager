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
			var move_path='';
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
				choose = [];
			}
			function displayDirList(new_path)
			{
				var fd = new FormData();//要傳過去給upload.php的資料
				move_path=new_path;
				fd.append('path', new_path);
				xhr.open('POST','listDir.php',true);//傳資料給upload.php
				xhr.onreadystatechange=function()
				{
					if (xhr.readyState == 4)
					{ // 確認 readyState
						if (xhr.status == 200)
						{ // 確認 status
							var list = document.getElementById("dirlist"); // 取得顯示位置
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
			var choose = [];
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
				choose_dir(evt);
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
				choose_file(evt);
				document.getElementById("contextmenu").style.left=_x+"px";
				document.getElementById("contextmenu").style.top=_y+"px";
				document.getElementById("contextmenu").style.visibility="visible";
				document.getElementById("contextmenu_download").style.color="#000000";
			}
			function delete_file()//刪除檔案
			{
				if (confirm("刪除"+(type==='file'?"檔案":"資料夾")))
				{
					var fd_delete_file = new FormData();
					fd_delete_file.append('path', path);
					for (i=0;i<choose.length;i++)
					{
						fd_delete_file.append('id[]',choose[i][0]);
						fd_delete_file.append('name[]',choose[i][1]);
						fd_delete_file.append('type[]',choose[i][2]);
					}
					xhr2.open('POST','del.php');
					xhr2.onload = function() 
					{
						//完成
						displayList(path);
					};
					xhr2.send(fd_delete_file);
				}
			}
			function download_file()//下載檔案
			{
				for (i=0;i<choose.length;i++)
				{
					if (choose[i][2]==='file')
					{
						if (confirm("下載 \""+choose[i][1]+"\"?"))
						{
							window.open('download.php?id='+choose[i][0]+'&name='+choose[i][1]);
						}
					}
					else
					{
						//資料夾下載
					}
				}
				
			}
			function rename_file()//重新命名檔案(單一檔案)
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
			function move_file(new_path)//移動檔案(多檔案)
			{
				//var new_path=prompt("移動"+(type==='file'?"檔案":"資料夾")+"到\n必須是已存在的路徑",path.replace(/\/$/, ""));
				if (new_path==null)
				{
					$("#Dialog").html("移動到<br/><div id='dirlist'></div>");
					displayDirList(path);
					//$("#Dialog").html("移動"+(type==='file'?"檔案":"資料夾")+"到<br/>必須是已存在的路徑<br/><input type='text' id='DialogText' value='"+path.replace(/\/$/, "")+"'/>");
				    $("#Dialog").dialog({
				    	dialogClass: "no-close",
				        resizable: false,
				        modal: true,
				        title: '移動',
				        height: 400,
				        width: 600,
				        buttons: {
				            "確定": function () {
								$(this).dialog('close');
								var fd_move_file = new FormData();
								fd_move_file.append('path',path);
								fd_move_file.append('newpath',move_path);

								for (i=0;i<choose.length;i++)
								{
									//choose[i][3].classList.remove('choose');
									fd_move_file.append('id[]',choose[i][0]);
									fd_move_file.append('name[]',choose[i][1]);
									fd_move_file.append('type[]',choose[i][2]);
									//判斷重複位置
									if  ((path+choose[i][1])==new_path)
									{
										alert('不能移到自己');
										return;
									}
								}
								xhr2.open('POST','move.php');
								xhr2.onload = function() 
								{
									displayList(path);
								};
								xhr2.send(fd_move_file);
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
				else
				{
					var i;
					var fd_move_file = new FormData();
					fd_move_file.append('path',path);
					fd_move_file.append('newpath',new_path);
					for (i=0;i<choose.length;i++)
					{
						//choose[i][3].classList.remove('choose');
						fd_move_file.append('id[]',choose[i][0]);
						fd_move_file.append('name[]',choose[i][1]);
						fd_move_file.append('type[]',choose[i][2]);
						//判斷重複位置
						if  ((path+choose[i][1])==new_path)
						{
							alert('不能移到自己');
							return;
						}
					}
					xhr2.open('POST','move.php');
					xhr2.onload = function() 
					{
						displayList(path);
					};
					xhr2.send(fd_move_file);
					choose = [];
				}
				
				
			}
			function new_dir()
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
			function cancelEvent(evt)
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
							displayList(path);
						};
						xhr_upload.send(fd_upload);//開始上傳
	                }
	            });
        	});

			function choose_file(evt) {
				type='file';
				id=evt.target.title;
				name=evt.target.innerText;
				for (i=0;i<choose.length;i++)
				{
					choose[i][3].classList.remove("select");
				}
				if (!evt.target.classList.contains("choose"))
				{
					for (i=0;i<choose.length;i++)
					{
						choose[i][3].classList.remove("choose");
					}
					choose=[];
					choose.push([evt.target.title,evt.target.innerText,'file',evt.target]);
				}
				evt.target.classList.add("select");
				evt.target.classList.add("choose");
			}
			function choose_dir(evt) {
				type='dir';
				id=evt.target.title;
				name=evt.target.innerText;
				for (i=0;i<choose.length;i++)
				{
					choose[i][3].classList.remove("select");
				}
				if (!evt.target.classList.contains("choose"))
				{
					for (i=0;i<choose.length;i++)
					{
						choose[i][3].classList.remove("choose");
					}
					choose=[];
					choose.push([evt.target.title,evt.target.innerText,'file',evt.target]);
				}
				evt.target.classList.add("select");
				evt.target.classList.add("choose");
			}

			function select_file(evt) {
				type='file';
				id=evt.target.title;
				name=evt.target.innerText;
				if (evt.ctrlKey)
				{
					if (evt.target.classList.contains("choose"))
					{
						for (i=0;i<choose.length;i++)
						{
							if (choose[i][0]==evt.target.title)
							{
								if (choose[i][2]=='file')
								{
									choose.splice(i,1);//移除項目
								}
							}
						}
						evt.target.classList.remove("select");
						evt.target.classList.remove("choose");
					}
					else
					{
						for (i=0;i<choose.length;i++)
						{
							choose[i][3].classList.remove("select");
						}
						evt.target.classList.add("select");
						evt.target.classList.add("choose");
						choose.push([evt.target.title,evt.target.innerText,'file',evt.target]);
					}
				}
				else
				{
					for (i=0;i<choose.length;i++)
					{
						choose[i][3].classList.remove("select");
					}
					for (i=0;i<choose.length;i++)
					{
						choose[i][3].classList.remove("choose");
					}
					choose=[];
					choose.push([evt.target.title,evt.target.innerText,'dir',evt.target]);
					evt.target.classList.add("select");
					evt.target.classList.add("choose");
				}
			}
			function select_dir(evt) {
				type='dir';
				id=evt.target.title;
				name=evt.target.innerText;
				if (evt.ctrlKey)
				{
					if (evt.target.classList.contains("choose"))
					{
						for (i=0;i<choose.length;i++)
						{
							if (choose[i][0]==evt.target.title)
							{
								if (choose[i][2]=='dir')
								{
									choose.splice(i,1);//移除項目
								}
							}
						}
						evt.target.classList.remove("select");
						evt.target.classList.remove("choose");
					}
					else
					{
						for (i=0;i<choose.length;i++)
						{
							choose[i][3].classList.remove("select");
						}
						evt.target.classList.add("select");
						evt.target.classList.add("choose");
						choose.push([evt.target.title,evt.target.innerText,'dir',evt.target]);
					}
				}
				else
				{
					for (i=0;i<choose.length;i++)
					{
						choose[i][3].classList.remove("select");
						choose[i][3].classList.remove("choose");
					}
					choose=[];
					choose.push([evt.target.title,evt.target.innerText,'file',evt.target]);
					evt.target.classList.add("select");
					evt.target.classList.add("choose");
				}
			}

			function select_all() {
				choose=[];
				var filelist=document.getElementById('file');
				var i;
    			for (i = 0;i < filelist.children.length;i++) {
    				if (!filelist.children[i].classList.contains('up_dir'))
    				{
    					choose.push([filelist.children[i].title,filelist.children[i].innerText,filelist.children[i].classList.contains('file')?'file':'dir',filelist.children[i]]);
    					filelist.children[i].classList.add("choose");
    				}
    			}
			}
			function select_reverse() {
				var filelist=document.getElementById('file');
				var i;
    			for (i = 0;i < filelist.children.length;i++) {
    				if (!filelist.children[i].classList.contains('up_dir'))
    				{
    					if (filelist.children[i].classList.contains('choose'))
    					{
    						for (j=0;j<choose.length;j++)
							{
								if (choose[j][3]==filelist.children[i])
								{
									choose.splice(j,1);//移除項目
								}
							}
							filelist.children[i].classList.remove("select");
							filelist.children[i].classList.remove("choose");
    					}
    					else
    					{
    						choose.push([filelist.children[i].title,filelist.children[i].innerText,filelist.children[i].classList.contains('file')?'file':'dir',filelist.children[i]]);
    						filelist.children[i].classList.add("choose");
    					}
    				}
    			}
			}
		</script>
		<script type="text/javascript">

			function keyFunction() {
				if ((event.ctrlKey) && (event.keyCode!=17)) {
					switch(event.keyCode){
						case 65://Ctrl+A
							select_all();
							return false;
							break;
						default:
					}
				}
			}
			document.onkeydown=keyFunction;

		</script>
	</head>
	<body ondragover='cancelEvent(event);' ondrop='cancelEvent(event);'>
		<div id="main" oncontextmenu='cancelEvent(event);'>
			<div id="menu" oncontextmenu='cancelEvent(event);'>
				<div onclick="displayList('')" oncontextmenu='cancelEvent(event);'><i class="fa fa-home"></i>回主目錄</div>
				<div onclick="new_dir()" oncontextmenu='cancelEvent(event);'><i class="fa fa-plus"></i>新增資料夾</div>
				<div onclick="$('#selectFile').click()" oncontextmenu='cancelEvent(event);'><i class="fa fa-upload"></i>上傳檔案</div>
				<div onclick="select_all()" oncontextmenu='cancelEvent(event);'><i class="fa fa-arrows-alt"></i>全選(Ctrl+A)</div>
				<div onclick="select_reverse()" oncontextmenu='cancelEvent(event);'><i class="fa fa-arrows-alt"></i>反向選取</div>
				<!-- <div>+新增空白檔案</div> -->
			</div>
			<div id='file' oncontextmenu='cancelEvent(event);'></div><!-- ondragover='dragOverHandler(event)' ondrop='dropHandler(event)' -->
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