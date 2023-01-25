<?php
header('Content-type: application/xml');

$soapURL = "https://pilot-psc.reachcore.com/wsnom151/webservice.asmx?op=ValidaConstancia";

$usuario = "Oswaldo Ramos Luna";
$Clave = "50V052022*";
$Entidad = "THINK SMART";
$nombreArchivoOriginal = 'pruebaversion3.pdf';
$referencia = "documento_84";

$constanciabase64 = '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';







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
    <ValidaConstancia xmlns="www.XMLWebServiceSoapHeaderAuth.net">
      <referencia>'.$referencia.'</referencia>
      <constancia>'.$constanciabase64.'</constancia>
    </ValidaConstancia>
  </soap:Body>
</soap:Envelope>';


$headers = array(
   "Content-type: text/xml;charset=\"utf-8\"",
   "Accept: text/xml",
   "Cache-Control: no-cache",
   "Pragma: no-cache",
   "SoapAction: www.XMLWebServiceSoapHeaderAuth.net/ValidaConstancia",
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
/*
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <validaFolioNOM151Response xmlns="http://tempuri.org/">
      <validaFolioNOM151Result>
        <Estado>int</Estado>
        <Descripcion>string</Descripcion>
        <validaFecha>string</validaFecha>
        <validaRelacion>boolean</validaRelacion>
        <validaSello>boolean</validaSello>
        <validaPSC>boolean</validaPSC>
        <documentoNombre>string</documentoNombre>
        <documentoHuellaDigital>string</documentoHuellaDigital>
        <constanciaSerie>long</constanciaSerie>
        <constanciaHuellaDigital>string</constanciaHuellaDigital>
        <contanciaFecha>string</contanciaFecha>
        <constanciaAlgoritmo>string</constanciaAlgoritmo>
        <constanciaSello>string</constanciaSello>
        <pscPolitica>string</pscPolitica>
        <pscNombre>string</pscNombre>
        <qrPDF>string</qrPDF>
        <reportePDF>string</reportePDF>
      </validaFolioNOM151Result>
    </validaFolioNOM151Response>
  </soap:Body>
</soap:Envelope>
*/
?>