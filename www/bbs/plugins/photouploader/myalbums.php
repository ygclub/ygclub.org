<?

function getAlbums($token) {
  $header = array('Authorization: AuthSub token="'.$token.'"');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"http://picasaweb.google.com/data/feed/api/user/default?kind=album");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FAILONERROR, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  $http_result = curl_exec($ch);
  curl_close ($ch);
  return $http_result;
}

$token = $_GET['token'];
if (strlen($token) == 0) {
  die;
}

$result = getAlbums($token);
echo $result;
 
?>
