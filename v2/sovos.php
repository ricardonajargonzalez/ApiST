<?php
header('Content-type: application/xml');

$soapURL = "https://pilot-psc.reachcore.com/wsnom151/webservice.asmx?op=GeneraConstancia";

$usuario = "Oswaldo Ramos Luna";
$Clave = "50V052022*";
$Entidad = "THINK SMART";

//archivo encriptado con openSSL
$year = '2022';
$month = '11';
$daymonth = '30';
$file_out = 'Manual ThinkSmart.pdf';
// $estructura = getcwd() .'/class/files_bin/'.$year .'/' . $month . '/' . $daymonth . '/';
// $file_tsq = $estructura . $file_out.'.tsq';
// $file = file_get_contents($file_tsq);
// $database64 = base64_encode($file);

// echo $database64; generado manual //MEECAQEwMTANBglghkgBZQMEAgEFAAQg3f4sA/Iq0AdgrLjVewmh3KH4RrjSLUDPvYhPzKT0U/gGCTiDZGUKgjwBAg==

$referencia = $_GET['referencia'];
// $solicitud = "MEECAQEwMTANBglghkgBZQMEAgEFAAQg3f4sA/Iq0AdgrLjVewmh3KH4RrjSLUDPvYhPzKT0U/gGCTiDZGUKgjwBAg==";

//generado manual
$solicitud = $_GET['solicitud'];

$xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <AuthSoapHd xmlns="www.XMLWebServiceSoapHeaderAuth.net">
      <Usuario>'.$usuario.'</Usuario>
      <Clave>'.$Clave.'</Clave>
      <Entidad>'.$Entidad.'</Entidad>
    </AuthSoapHd>
  </soap:Header>
  <soap:Body>
    <GeneraConstancia xmlns="www.XMLWebServiceSoapHeaderAuth.net">
      <referencia>'.$referencia.'</referencia>
      <solicitud>'.$solicitud.'</solicitud>
    </GeneraConstancia>
  </soap:Body>
</soap:Envelope>';


$headers = array(
   "Content-type: text/xml;charset=\"utf-8\"",
   "Accept: text/xml",
   "Cache-Control: no-cache",
   "Pragma: no-cache",
   "SoapAction: www.XMLWebServiceSoapHeaderAuth.net/GeneraConstancia",
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


   $respuesta = new SimpleXMLElement($xml);
   $cuerpo = $respuesta->children('soap', true)->Body->children('', true);
   $GeneraConstanciaResult = $cuerpo->GeneraConstanciaResponse->GeneraConstanciaResult;
   //echo $GeneraConstanciaResult->Estado;
   echo $xml;


// <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
// <soap:Body>
// <GeneraConstanciaResponse xmlns="www.XMLWebServiceSoapHeaderAuth.net">
// <GeneraConstanciaResult>
// <Estado>2</Estado>
// <Descripcion>Cuenta de usuario o contraseña inválida</Descripcion>
// <Folio>0</Folio>
// </GeneraConstanciaResult>
// </GeneraConstanciaResponse>
// </soap:Body>
// </soap:Envelope>
?>