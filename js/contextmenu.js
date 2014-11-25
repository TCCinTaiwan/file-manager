
function dir_contextmenu(evt)
{
	evt.preventDefault();
	_x=evt.clientX;
	_y=evt.clientY;
	document.getElementById("contextmenu").style.left=_x+"px";
	document.getElementById("contextmenu").style.top=_y+"px";
	document.getElementById("contextmenu").style.visibility="visible";
}
function file_contextmenu(evt)
{
	evt.preventDefault();
	_x=evt.clientX;
	_y=evt.clientY;
	document.getElementById("contextmenu").style.left=_x+"px";
	document.getElementById("contextmenu").style.top=_y+"px";
	document.getElementById("contextmenu").style.visibility="visible";
}
function addnewdir()
{

}