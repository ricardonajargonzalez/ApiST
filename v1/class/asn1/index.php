<?php
require_once dirname(__DIR__) . '/asn1/vendor/autoload.php';

use FG\ASN1\ASNObject;

$base64String2 = 'MEECAQEwMTANBglghkgBZQMEAgEFAAQgllKe//niH/swgYx8fM3lBGISPFEp+0qk5mizUMEMuWEGCTiDZGUKgjwBAg==';
$base64String2 = utf8_encode($base64String2);

$resp = base64_decode($base64String2);
$asnObject = ASNObject::fromBinary($resp);
// echo $asnObject[1][1];

$hex = $asnObject[1][1];
 $string = hex2bin($hex); // Hexadecimal a binary string
 $arraybytes = array();

//se agregaron los bytes en el arreglo
$byteLen = strlen($string);
for($i=0; $i < $byteLen; $i++){
  $byte = $string[$i];
  array_push($arraybytes, ord($byte));
}

//se  codifico el arreglo en bytes y se paso a base64
$packed = pack("c*", ...$arraybytes);
$database64 = base64_encode($packed);
echo $database64;

?>