//Create HTML5 elements for IE < 9 (see also css.php)

document.createElement("header");
document.createElement("footer");
document.createElement("aside");
document.createElement("mark");

//global variables

var hlpWinH = 400, hlpWinW = 800, hlpWinT = 100; //help window height, width, top
var evtWinH = 200, evtWinW = 490, evtWinT = 100; //event window height, width, top
var logWinH = 200, logWinW = 490, logWinT = 180; //login window height, width, top

//shortcut functions

function $I(el) { return document.getElementById(el); }
function $N(el) { return document.getElementsByName(el); }
function $T(el) { return document.getElementsByTagName(el); }

// functions used in headers

function login(){ //open login dialogue
	var calWinL = (!window.screenLeft) ? window.screenX : window.screenLeft; //cal left edge
	var calWinT = (!window.screenTop) ? window.screenY : window.screenTop; //cal top edge
	var calWinW = (!window.innerWidth) ? document.documentElement.clientWidth : window.innerWidth; //cal width
	logWinX = calWinL+(calWinW-logWinW)/2;
	logWinY = calWinT+logWinT;
	window.open('index.php?xP=20','','height=' + logWinH +',width=' + logWinW + ',left=' + logWinX + ',top=' + logWinY + ', scrollbars=0');
}

function logout(){ //user logout
	location.href='index.php?cP=0&logout=y';
}

function help(){ //show user guide
	var hlpWinL = (screen.width-hlpWinW)/2;
	window.open('index.php?xP=22','','height=' + hlpWinH +',width=' + hlpWinW + ',top=' + hlpWinT + ',left=' + hlpWinL + ', scrollbars');
}

// functions used on view pages

function goMonth(dt){ //go to month view
	location.href='index.php?cP=2&cD='+dt;
	return false;
}

function goWeek(dt){ //go to week view
	location.href='index.php?cP='+((mode.substr(0,1) == 'w') ? 5 : 4)+'&cD='+dt;
	return false;
}

function goDay(dt){ //go to day view
	location.href='index.php?cP=6&cD='+dt;
	return false;
}

function newE(dt,st,et){ //new event - date / times optional
	var date = (arguments[0]) ? '&evD='+arguments[0] : '';
	var time = (arguments[1]) ? '&evTs='+arguments[1]+'&evTe='+arguments[2] : '';
	var evtWinL = (screen.width-evtWinW)/2;
	window.open('index.php?xP=10&mode=add'+date+time,'','height=' + evtWinH +',width=' + evtWinW + ',top=' + evtWinT + ',left=' + evtWinL + ', scrollbars=0');
	return false;
}
function newEleve(){ //new event - date / times optional
	var evtWinL = 490;
	window.open('index.php?cP=97','','height=400,width=750,top=' + evtWinT + ',left=' + evtWinL + ', scrollbars=0');
	return false;
}

function editE(id,date){ //edit event
	var evtWinL = (screen.width-evtWinW)/2;
	window.open('index.php?xP=10&mode=edit&eid='+id+'&evD='+date,'','height=' + evtWinH + ',width=' + evtWinW + ',top=' + evtWinT + ',left=' + evtWinL + ', scrollbars=0');
	return false;
}

function checkE(id,date,path){ //check event
	path = path || '';
	var evtWinL = (screen.width-evtWinW)/2;
	window.open(path+'index.php?xP=11&eid='+id+'&evD='+date,'','height=' + evtWinH +',width=' + evtWinW + ',top=' + evtWinT + ',left=' + evtWinL + ', scrollbars=0');
	return false;
}

function winFit(maxH) { //fit window height to its content size
  neededIH = Math.min(maxH,document.body.offsetHeight + 6);
  currentIH = window.innerHeight || document.documentElement.clientHeight;
	if (window.resizeBy) {
		window.resizeBy(0,neededIH-currentIH);
	} else {
		currentOW = window.outerWidth;
		currentOH = window.outerHeight;
		window.resizeTo(currentOW,neededIH+currentOH-currentIH);
	}
}

//general functions

function done(close,refresh,url) { //close window and refresh calendar (1: true; 0: false)
	if (refresh) {
		if (url) {
			window.opener.location.href=url;
		} else {
			window.opener.location = window.opener.location.href;
		}
	}
	if (close) window.close();
}

function jumpMenu(myList) {
	var gotoUrl = myList.options[myList.selectedIndex].value;
	if (gotoUrl != '#') { window.location.href = gotoUrl; }
	return false;
}

function check1(boxName,checked) { //check 1 of N checkboxes
	var chBoxes = $N(boxName);
	for (var i=(chBoxes.length-1);i >= 0;i--) {
		chBoxes[i].checked = false;
	}
	checked.checked = true;
}

function checkA(boxName,checked) { //check "all" of N checkboxes
	var chBoxes = $N(boxName+'[]');
	for (var i=(chBoxes.length-1);i >= 0;i--) {
		chBoxes[i].checked = false;
	}
	$I(boxName+'0').checked = true;
}

function checkN(boxName,checked) { //check any of N checkboxes
	var chBoxes = $N(boxName+'[]');
	var check = false;
	for (var i=(chBoxes.length-1);i >= 0;i--) {
		if (chBoxes[i].checked) { check = true; }
	}
	$I(boxName+'0').checked = (check == true) ? false : true;
}

function toggleIHtml(button,text1,text2) {
	button.innerHTML = (button.innerHTML == text1) ? text2 : text1;
}

function show(elem,formName) { //display/hide element; if form & hide: submit
	var overlay = $I(elem);
	overlay.style.display = (overlay.style.display == "block") ? "none" : "block";
	if (formName && overlay.style.display == "none") { document.forms[formName].submit(); }
	return false;
}

function dragme(obj,e) { //drag object with mouse
	objParent = obj.parentNode;
	if (objParent.onmousemove == null) {
		if (!e) var e = window.event; //if ie
		var xdif = e.clientX - objParent.offsetLeft; //mouse-X - parent-L
		var ydif = e.clientY - objParent.offsetTop;
		objParent.onmousemove = function(e) {
			if (!e) var e = window.event; //if ie
			objParent.style.left = e.clientX - xdif + "px";
			objParent.style.top = e.clientY - ydif + "px";
		};
	} else {
		objParent.onmousemove = null;
	}
}

function printNice() {
	var regex = /noPrint/;
	var els = $T("*");
	for (var i=(els.length-1);i >= 0;i--) {
		if (regex.test(els[i].className)) { els[i].style.display = "none"; }
	}
	document.body.style.backgroundColor = "white";
	regex = /scrollBox/;
	for (var i=(els.length-1);i >= 0;i--) {
		if (regex.test(els[i].className)) {
			els[i].style.position = "static";
			els[i].style.overflow = "visible";
		}
	}
	window.print();
	window.location = window.location.href; //reload the page
	return false;
}

//==== text box popup function ====

function pop(puContent, puClass, puMaxChar){
	if (typeof puContent != 'string') { //pop off
		$I("htmlPop").style.visibility = "hidden";
		document.onmousemove = null;
		return;
	}
	
	var offsetX=-60, offsetY=16; //x, y offset of box
	var maxLineLen=80; //default max line length in box
	var popobj;

	var maxBoxWidth = "auto";
	if (typeof puMaxChar == 'number') maxLineLen = puMaxChar;
	var lines = puContent.split("<br>") //split on <br>
	for (var i=0,len=lines.length; i<len; i++) {
		if (lines[i].replace(/(<([^>]+)>)/ig,"").length > maxLineLen) maxBoxWidth = (5 * maxLineLen) + "px";
	}
	if (!$I("htmlPop")) { //box object not yet existing
		var details = document.createElement("more");
		details.setAttribute("id", "htmlPop");
		document.body.appendChild(details);
	}
	popobj = $I("htmlPop");
	popobj.style.width = maxBoxWidth;
	popobj.className = puClass;
	popobj.innerHTML = puContent;
	popobj.style.visibility = "visible";
	document.onmousemove = function(e) {
		if (!e) var e = window.event; //if ie
		//e.clientX, e.clientY: mouse coords relative to window
		var rightedge = (!window.innerWidth) ? document.documentElement.clientWidth : window.innerWidth-20; //window edge
		var bottomedge = (!window.innerHeight) ? document.documentElement.clientHeight : window.innerHeight-10;

		if (rightedge-e.clientX-offsetX < popobj.offsetWidth) { //if popup hits the right edge
			popobj.style.left = rightedge-popobj.offsetWidth-5+"px"; //don't move it
		} else {
			popobj.style.left = (e.clientX < (-offsetX)) ? "5px" : e.clientX+offsetX+"px"; //move it with mouse
		}
		if (bottomedge-e.clientY-offsetY < popobj.offsetHeight) { //if popup hits the bottom edge
			popobj.style.top = e.clientY-popobj.offsetHeight-(offsetY/2)+"px"; //flip it up
		} else {
			popobj.style.top = e.clientY+offsetY+"px"; //move it with mouse
		}
	};
	return;
}
function selectedEleve( id , elevename ) {
	//alert(id);
	opener.document.getElementById('tit').value = elevename;
	opener.document.getElementById('eleve_id').value = id;
	
   
}