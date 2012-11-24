<?php
function exchangeToken() {
  $token = $_GET['token'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://www.google.com/accounts/AuthSubSessionToken");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FAILONERROR, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: AuthSub token="'.$token.'"'));
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  
  $result = curl_exec($ch);
  curl_close($ch);
  $splitStr = split("=", $result);
  return trim($splitStr[1]);
  
}
?>

<script language="JavaScript" type="text/javascript">
var token = '<? echo exchangeToken();?>';
var uploader;
if (navigator.appName.indexOf ("Microsoft") != -1) {
    uploader = window.opener.document.getElementById('PhotoUploader');
} else {
    uploader = window.opener.document['PhotoUploader'];
}

uploader.setAuthToken(token);
window.close();
</script>
