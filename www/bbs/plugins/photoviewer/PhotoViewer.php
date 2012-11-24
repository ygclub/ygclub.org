<?php
/*
  Copyright 2008 Google Inc.

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
 */

$photoFeedUrl = rawurlencode($_GET['photoFeedUrl']);
if (!$photoFeedUrl) {
  die;
}

$photoviewer_version = '0.1.0';
?>
<!-- saved from url=(0014)about:internet -->
<html lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!--  BEGIN Browser History required section -->
<link rel="stylesheet" type="text/css" href="history/history.css" />
<!--  END Browser History required section -->

<title></title>
<script src="AC_OETags.js" language="javascript"></script>

<!--  BEGIN Browser History required section -->
<script src="history/history.js" language="javascript"></script>
<!--  END Browser History required section -->

<style>
body {
  margin: 0px;
  overflow: hidden
}
</style>
<script language="JavaScript" type="text/javascript">
<!--
// -----------------------------------------------------------------------------
// Globals
// Major version of Flash required
var requiredMajorVersion = 9;
// Minor version of Flash required
var requiredMinorVersion = 0;
// Minor version of Flash required
var requiredRevision = 28;
// -----------------------------------------------------------------------------
// -->
</script>
<script type="text/javascript">
var _picasaVersion = 0;
var plugin = navigator.mimeTypes['application/x-picasa-detect'];
if (plugin) {
	if (!plugin.description) {
		_picasaVersion = 2;
	} else {
		_picasaVersion = parseFloat(plugin.description);
	}
}
</script>
<!--[if gte Picasa 2]>
<script>_picasaVersion = 2;</script>
<![endif]-->
<!--[if gte Picasa 3]>
<script>_picasaVersion = 3;</script>
<![endif]-->
<!--[if gte Picasa 3.1]>
<script>_picasaVersion = 3.1;</script>
<![endif]-->
<script type="text/javascript">

function downloadPhotos(downloadRssLink) {
  if (_picasaVersion > 2) {
	location = 'picasa://downloadfeed/?url=' + escape(downloadRssLink);
  } else {
    parent.location.href = 'http://picasa.google.com/';
  }
}

function checkPicasa() {
  if (_picasaVersion > 2) {
    return true;
  } else {
    return false;
  }
}
</script>
</head>

<body scroll="no">
<script language="JavaScript" type="text/javascript">
<!--
// Version check for the Flash Player that has the ability to start Player Product Install (6.0r65)
var hasProductInstall = DetectFlashVer(6, 0, 65);

// Version check based upon the values defined in globals
var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);

if (hasProductInstall && !hasRequestedVersion) {
  // DO NOT MODIFY THE FOLLOWING FOUR LINES
  // Location visited after installation is complete if installation is required
  var MMPlayerType = (isIE == true) ? "ActiveX" : "PlugIn";
  var MMredirectURL = window.location;
    document.title = document.title.slice(0, 47) + " - Flash Player Installation";
    var MMdoctitle = document.title;

  AC_FL_RunContent(
    "src", "playerProductInstall",
    "FlashVars", "MMredirectURL="+MMredirectURL+'&MMplayerType='+MMPlayerType+'&MMdoctitle='+MMdoctitle+"",
    "width", "100%",
    "height", "100%",
    "align", "middle",
    "id", "PhotoViewer",
    "quality", "high",
    "bgcolor", "#869ca7",
    "name", "PhotoViewer",
    "allowScriptAccess","sameDomain",
    "allowFullScreen","true",
    "type", "application/x-shockwave-flash",
    "pluginspage", "http://www.adobe.com/go/getflashplayer"
  );
} else if (hasRequestedVersion) {
  // if we've detected an acceptable version
  // embed the Flash Content SWF when all tests are passed
  AC_FL_RunContent(
      "src", "<?php echo 'PhotoViewer?' . $photoviewer_version?>",
      "FlashVars", "photoFeedUrl=<?php echo htmlspecialchars($photoFeedUrl) ?>&tracking=false&downloadFeedApi=local",	  
      "width", "100%",
      "height", "100%",
      "align", "middle",
      "id", "PhotoViewer",
      "quality", "high",
      "bgcolor", "#869ca7",
      "name", "PhotoViewer",
      "allowScriptAccess","sameDomain",
      "allowFullScreen","true",
      "type", "application/x-shockwave-flash",
      "pluginspage", "http://www.adobe.com/go/getflashplayer"
  );
  } else {  // flash is too old or we can't detect the plugin
    var alternateContent = 'Alternate HTML content should be placed here. '
    + 'This content requires the Adobe Flash Player. '
    + '<a href=http://www.adobe.com/go/getflash/>Get Flash</a>';
    document.write(alternateContent);  // insert non-flash content
  }
// -->
</script>
<noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
  id="PhotoViewer" width="100%" height="100%"
  codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
  <param name="movie"
    value="PhotoViewer.swf<?php echo '?' . $photoviewer_version ?>" />
  <param name="quality" value="high" />
  <param name="bgcolor" value="#869ca7" />
  <param name="allowScriptAccess" value="sameDomain" />
  <param name="allowFullScreen" value="true" />
  <param name="flashVars" value="photoFeedUrl=<?php echo htmlspecialchars($photoFeedUrl) ?>" />
  <embed src="PhotoViewer.swf<?php echo '?' . $photoviewer_version ?>"
    quality="high" bgcolor="#869ca7" width="100%" height="100%"
    name="PhotoViewer" align="middle" play="true" loop="false"
    quality="high" allowScriptAccess="sameDomain" allowFullScreen="true"
    type="application/x-shockwave-flash"
    flashVars="photoFeedUrl=<?php echo htmlspecialchars($photoFeedUrl) ?>"
    pluginspage="http://www.adobe.com/go/getflashplayer">
  </embed></object></noscript>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try{
var pageTracker = _gat._getTracker("UA-4749634-4");
pageTracker._setDomainName("none");
pageTracker._setAllowLinker(true);
pageTracker._initData();
var page = "/" + document.location.protocol + document.location.hostname + document.location.pathname + document.location.search;
pageTracker._trackPageview(page);
} catch(err) {}
</script>
</body>
</html>
