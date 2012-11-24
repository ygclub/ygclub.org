<?php	
if ($action == 'getDownloadFeed' && isset($_GET['PHPSESSID'])) {
	session_id($_GET['PHPSESSID']); 
	session_start(); 
	header('Content-Type: application/xml; charset=UTF-8');
	if (get_magic_quotes_gpc()) { 
		echo str_replace('&','&amp;',stripslashes($_SESSION['downloadFeed']));    
	} else {  
		echo str_replace('&','&amp;',$_SESSION['downloadFeed']);
	}
	unset($_SESSION['downloadFeed']);
	session_destroy();
} else if (isset($downloadFeed)) {
	session_start(); 
	$port = '';
	$protocol = 'http://';
	if ($_SERVER['SERVER_PORT'] && $_SERVER['SERVER_PORT'] != 80)
		$port = $_SERVER['SERVER_PORT'];	
	if ($_SERVER['HTTPS'])
		$protocol =  "https://";
	$url =  $protocol.$_SERVER ['HTTP_HOST'].$port.$_SERVER['PHP_SELF'];	
	if (!get_magic_quotes_gpc()) {
		$downloadFeed = addslashes($downloadFeed);
	}
	$_SESSION['downloadFeed'] = $downloadFeed;
	echo $url.'?PHPSESSID='.session_id().'&action=getDownloadFeed';
} else {
	echo "error";
}
?>