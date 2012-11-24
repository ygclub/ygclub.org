<?
function endsWith($s, $ss) {
  if (!$s) {
    return false;
  }
  if (strlen($s) < strlen($ss)) {
    return false;
  }
  $ret = true;
  for ($i = 0; $i < strlen($ss); ++$i) {
    $ch1 = $s[strlen($s) - strlen($ss) + $i];
    $ch2 = $ss[$i];
    if (strtolower($ch1) != strtolower($ch2)) {
      $ret = false;
      break;
    }
  }
  return $ret;
}

$token = $_POST['token'];
$albumId = $_POST['albumId'];
$photoTempName = $_FILES['photo']['tmp_name'];
$photoName = $_FILES['photo']['name'];
$photoType = $_FILES['photo']['type'];

if (endsWith($photoName, ".jpeg") || endsWith($photoName, ".jpg")) {
  $photoType = "image/jpeg";
} else if (endsWith($photoName, ".gif")) {
  $photoType = "image/gif";
} else if (endsWith($photoName, ".bmp")) {
  $photoType = "image/bmp";
} else if (endsWith($photoName, ".png")) {
  $photoType = "image/png";
} else {
  die;
}

if (strlen($token) != 0) { 
  $image_data = file_get_contents($photoTempName);
  $xml = "Media multipart posting\n";
  $xml .= "--END_OF_PART\n";
  $xml .= "Content-Type: application/atom+xml\n";
  $xml .= "\n";
  $xml .= "<entry xmlns='http://www.w3.org/2005/Atom'>\n";
  $xml .= "<title>".$photoName."</title>\n";
  $xml .= "<summary></summary>\n";
  $xml .= "<category scheme=\"http://schemas.google.com/g/2005#kind\" term=\"http://schemas.google.com/photos/2007#photo\"/>\n";
  $xml .= "</entry>\n";
  $xml .= "--END_OF_PART\n";
  $xml .= "Content-Type: ".$photoType."\n\n";
  $xml .= $image_data;
  $xml .= "\n--END_OF_PART--";

  unset($image_data);

  $header = array(
  "Content-Type: multipart/related; boundary=END_OF_PART", 
  "Content-Length: ".strlen($xml),
  "MIME-version: 1.0",
  'Authorization: AuthSub token="'.$token.'"');

  $albumUrl = strlen($albumId ) ? "album/".$albumId : "albumid/default";

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL,"http://picasaweb.google.com/data/feed/api/user/default/".$albumUrl."?imgmax=800");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

  $http_result = curl_exec($ch);
  curl_close ($ch);

  echo $http_result;
}
?>
