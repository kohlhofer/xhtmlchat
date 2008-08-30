// XHTML live Chat
// author: alexander kohlhofer
// version: 1.0
// http://www.plasticshore.com
// http://www.plasticshore.com/projects/chat/
// please let the author know if you put any of this to use
// XHTML live Chat (including this script) is published under a creative commons license
// license: http://creativecommons.org/licenses/by-nc-sa/2.0/




var GetChaturl = "getChatData.php";
var SendChaturl = "sendChatData.php";
var lastID = -1; //initial value will be replaced by the latest known id
window.onload = initJavaScript;

function initJavaScript() {
	document.forms['chatForm'].elements['chatbarText'].setAttribute('autocomplete','off'); //this non standard attribute prevents firefox' autofill function to clash with this script
	checkStatus(''); //sets the initial value and state of the input comment
	checkName(); //checks the initial value of the input name
	receiveChatText(); //initiates the first data query
}

//initiates the first data query
function receiveChatText() {
	if (httpReceiveChat.readyState == 4 || httpReceiveChat.readyState == 0) {
  	httpReceiveChat.open("GET",GetChaturl + '?lastID=' + lastID + '&rand='+Math.floor(Math.random() * 1000000), true);
    httpReceiveChat.onreadystatechange = handlehHttpReceiveChat; 
  	httpReceiveChat.send(null);
	}
}

//deals with the servers' reply to requesting new content
function handlehHttpReceiveChat() {
  if (httpReceiveChat.readyState == 4) {
    results = httpReceiveChat.responseText.split('---'); //the fields are seperated by ---
    if (results.length > 2) {
	    for(i=0;i < (results.length-1);i=i+3) { //goes through the result one message at a time
	    	insertNewContent(results[i+1],results[i+2]); //inserts the new content into the page
	    }
	    lastID = results[results.length-4];
    }
    setTimeout('receiveChatText();',4000); //executes the next data query in 4 seconds
  }
}

//inserts the new content into the page
function insertNewContent(liName,liText) {
	insertO = document.getElementById("outputList");
	oLi = document.createElement('li');
	oSpan = document.createElement('span');
	oSpan.setAttribute('className','name'); //for IE's sake
	oSpan.setAttribute('class','name');
	oName = document.createTextNode(liName+': ');
	oText = document.createTextNode(liText);
	oSpan.appendChild(oName);
	oLi.appendChild(oSpan);
	oLi.appendChild(oText);
	insertO.insertBefore(oLi, insertO.firstChild);
}

//stores a new comment on the server
function sendComment() {
	currentChatText = document.forms['chatForm'].elements['chatbarText'].value;
	if (currentChatText != '' & (httpSendChat.readyState == 4 || httpSendChat.readyState == 0)) {
		currentName = document.forms['chatForm'].elements['name'].value;
		param = 'n='+ currentName+'&c='+ currentChatText;	
		httpSendChat.open("POST", SendChaturl, true);
		httpSendChat.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  	httpSendChat.onreadystatechange = handlehHttpSendChat;
  	httpSendChat.send(param);
  	document.forms['chatForm'].elements['chatbarText'].value = '';
	} else {
		setTimeout('sendComment();',1000);
	}
}

//deals with the servers' reply to sending a comment
function handlehHttpSendChat() {
  if (httpSendChat.readyState == 4) {
  	receiveChatText(); //refreshes the chat after a new comment has been added (this makes it more responsive)
  }
}


//does celver things to the input and submit
function checkStatus(focusState) {
	currentChatText = document.forms['chatForm'].elements['chatbarText'];
	oSubmit = document.forms['chatForm'].elements['submit'];
	if (currentChatText.value != '' || focusState == 'active') {
		oSubmit.disabled = false;
	} else {
		oSubmit.disabled = true;
	}
}

//autoasigns a random name to a new user
function checkName() {
	currentName = document.forms['chatForm'].elements['name'];
	if (currentName.value == '') {
		currentName.value = 'guest_'+ Math.floor(Math.random() * 10000);
	}
}


//initiates the XMLHttpRequest object
//as found here: http://www.webpasties.com/xmlHttpRequest
function getHTTPObject() {
  var xmlhttp;
  /*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
  @else
  xmlhttp = false;
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp = new XMLHttpRequest();
    } catch (e) {
      xmlhttp = false;
    }
  }
  return xmlhttp;
}


// initiates the two objects for sending and receiving data
var httpReceiveChat = getHTTPObject();
var httpSendChat = getHTTPObject();
