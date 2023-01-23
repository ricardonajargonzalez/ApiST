<?php
header('Content-type: application/xml');

$soapURL = "https://pilot-psc.reachcore.com/wsnom_validacion/webservice.asmx?op=validaFolioNOM151";

$usuario = "Oswaldo Ramos Luna";
$Clave = "50V052022*";
$Entidad = "THINK SMART";
$nombreArchivoOriginal = 'pruebaversion3.pdf';
$folio = 10672805;

$database64 = 'OTYwODA1RDQ2QTJBQTMxQjhFMUJDMEZFNzY0MTFCRkYwQjA5RTE1MDZDOTE1NEU2MUUwNzAxOTc0M0IwRTZDQw==';
// $database64 = 'RkFDOUE4NkRGMzg3NjQyMDI4RjAzNDhDNTZDNzNBQzU1QTM3REQ4QTU0QUFFMTY5MkU4NjdGNDdBQkQ1NzUyQQ==';
//El algoritmo aplicado de digestión al archivo original no es válido. Sólo se soporta SHA256 y SHA512


$xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <AuthSoapHd xmlns="http://tempuri.org/">
      <Entidad>'.$Entidad.'</Entidad>
      <Usuario>'.$usuario.'</Usuario>
      <Clave>'.$Clave.'</Clave>
    </AuthSoapHd>
  </soap:Header>
  <soap:Body>
    <validaFolioNOM151 xmlns="http://tempuri.org/">
      <nombreArchivoOriginal>'.$nombreArchivoOriginal.'</nombreArchivoOriginal>
      <huellaDigitalArchivo>'.$database64.'</huellaDigitalArchivo>
      <folio>'.$folio.'</folio>
    </validaFolioNOM151>
  </soap:Body>
</soap:Envelope>';


$headers = array(
   "Content-type: text/xml;charset=\"utf-8\"",
   "Accept: text/xml",
   "Cache-Control: no-cache",
   "Pragma: no-cache",
   "SoapAction: http://tempuri.org/validaFolioNOM151",
   "Content-length: ". strlen($xml_post_string),
);

$url = $soapURL;

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl,CURLOPT_USERPWD, $usuario . ":" . $Clave);

curl_setopt($curl,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
curl_setopt($curl,CURLOPT_TIMEOUT,30);
curl_setopt($curl,CURLOPT_POST,true);
curl_setopt($curl,CURLOPT_POSTFIELDS, $xml_post_string);

curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($curl);
curl_close($curl);


$xml = $response;
echo $xml;

?>