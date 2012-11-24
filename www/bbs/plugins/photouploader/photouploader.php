<?php
  $photoUploaderBaseUrl = $boardurl."plugins/photouploader";
?>

<a href="javascript:showGadget('search');"><img src="<?=$photoUploaderBaseUrl?>/uploader-button.gif"/></a>

<script language="JavaScript" type="text/javascript">

function photoSelected(imageUrls) {
  var text = "";
  for (var i = 0; i < imageUrls.length; ++i) {
    if (wysiwyg) {
      var img = '<img src="' + imageUrls[i] + '" border="0"><br/>';
    } else {
      var img = '[img]' + imageUrls[i] + '[/img]\n';
    }
    text = text + img;
  }
  if (wysiwyg) {
    checkFocus();
    if (is_moz || is_opera) {
      insertText(text, 0, 0, null);
      var parsedtext = getEditorContents();
      parsedtext = bbcode2html(parsedtext);
      newEditor(mode, parsedtext);
      editwin.focus();
      setCaretAtEnd();
    } else {    
      var sel = editdoc.selection.createRange();
      sel.pasteHTML(text);
    }
  } else {
    $('postform').message.value += text;
  }
}

function getScrollHeight() {
  if (typeof(window.pageYOffset) == 'number') {
    return window.pageYOffset;    
  } else if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {
    return document.body.scrollTop;
  } else if(document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {
    return document.documentElement.scrollTop;
  }
  return 0;
}

function analytics() {
  if (_gat) {
    var pageTracker = _gat._getTracker("UA-5362712-1");
    if (pageTracker) {
      pageTracker._setDomainName("none");
      pageTracker._setAllowLinker(true);
      pageTracker._initData();
      var page = "/" + document.location.protocol + document.location.hostname + document.location.pathname + document.location.search;
      pageTracker._trackPageview(page);
    }
  }
}

function showGadget(tab) {
  analytics();
  var popup = document.getElementById("popup");
  
  var windowWidth = (window.innerWidth)?window.innerWidth:document.documentElement.clientWidth;
  var windowHeight = (window.innerWidth)?window.innerHeight:document.documentElement.clientHeight;
  var left = (windowWidth - 1000) / 2; 
  var top = (windowHeight - 1300) / 2; 
  popup.style.left = left + "px";
  popup.style.top = (getScrollHeight() + top) + "px"; 
  
  var disablePage = document.getElementById("disablePage");
  
  disablePage.style.width = windowWidth + "px";
  disablePage.style.height = windowHeight + "px"; 

  popup.innerHTML = UPLOADER_HTML;  

  var gadget = document.getElementById("picasaGadget");

  gadget.style.display = "block";
}

function hideGadget() {
  var gadget = document.getElementById("picasaGadget");
  gadget.style.display = "none";
}

var UPLOADER_HTML =
'<object id="PhotoUploader" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"' + 
'      codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab" height="100%" width="100%">' + 
'      <param name="src" value="<?=$photoUploaderBaseUrl?>/PhotoUploader.swf"/>' +
'      <param id="picasaparam1" name="flashVars" value="baseHostUrl=<?=$photoUploaderBaseUrl?>&firstTab=search"/>' + 
'      <embed id="picasaparam2" name="PhotoUploader" src="<?=$photoUploaderBaseUrl?>/PhotoUploader.swf" bgcolor="#FFFFFF"' +
'        flashVars="baseHostUrl=<?=$photoUploaderBaseUrl?>&firstTab=search"' +
'        width="100%" height="100%" name="PhotoUploader" align="middle"' +
'        play="true"' +
'        loop="false"' +
'        quality="high"' +
'        allowScriptAccess="always"' +
'        type="application/x-shockwave-flash"' +
'        pluginspage="http://www.adobe.com/go/getflashplayer"/>' +
'</object>';
</script>

<div id="picasaGadget" style="display:none">
<div id="disablePage" style="position:absolute;left: 0px;top: 0px;background-color: #FFFFFF;opacity: 0.5;filter: alpha(opacity = 50);"></div>    
  <div id="popup" style="position:absolute;width: 630px;height: 420px;">        
  </div>
</div>

