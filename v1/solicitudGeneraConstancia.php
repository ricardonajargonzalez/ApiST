<?php
header('Content-type: application/xml');

$soapURL = "https://pilot-psc.reachcore.com/wsnom151/webservice.asmx?op=GeneraConstancia";

$usuario = "Oswaldo Ramos Luna";
$Clave = "50V052022*";
$Entidad = "THINK SMART";
$referencia = "documento_72";
$solicitud  = "MEECAQEwMTANBglghkgBZQMEAgEFAAQgllKe//niH/swgYx8fM3lBGISPFEp+0qk5mizUMEMuWEGCTiDZGUKgjwBAg==";



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
   echo $xml;


?>