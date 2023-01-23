<?php
require_once "respuestas.class.php";
require_once dirname(__DIR__) . '/class/asn1/vendor/autoload.php';
use FG\ASN1\ASNObject;


class generaConstancia {
  private $dominio;
  private $urlnube;
  private $userdw;
  private $passworddw;
  private $idarchivador;
  private $iddocumento;
  private $cookie;
  private $ambiente;
  private $usuariosv;
  private $clavesv;
  private $entidadsv;
  private $referenciasv;
  private $policysv;
  private $url_bin;
  // private $nombrearchivo;
  private $huelladigital;
  private $constancia;
  private $validarConstacia;


      public function __construct($dataarray){
        $this->dominio = $dataarray['dominio'];
        $this->urlnube = $dataarray['url_nube'];
        $this->userdw = $dataarray['user_dw'];
        $this->passworddw = $dataarray['password_dw'];
        $this->idarchivador = $dataarray['id_archivador'];
        $this->iddocumento = $dataarray['id_documento'];
        $this->cookie = getcwd() .'/class/cookies.txt';
        $this->ambiente = $dataarray['ambiente'];
        $this->usuariosv = $dataarray['usuario_sv'];
        $this->clavesv = $dataarray['clave_sv'];
        $this->entidadsv = $dataarray['entidad_sv'];
        $this->referenciasv = 'documento_' . $dataarray['id_documento'];
        $this->policysv = $dataarray['policy_sv'];
        $this->huelladigital = $dataarray['huelladigital'];
        $this->validarConstacia = $dataarray['validar_constancia']; // generar reporte de validacion de constancia?
    }

    public function set_url_bin($url_bin){
      $this->url_bin = $url_bin;
    }

    public function huellaDigital($psolicitud){

      $solicitudbase64 = utf8_encode($psolicitud);

      $resp = base64_decode($solicitudbase64);
      $asnObject = ASNObject::fromBinary($resp);


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
      return $database64;
    }

    public function validaConstancia_con_file_asn1($array){
      $soapURL = "https://pilot-psc.reachcore.com/wsnom_validacion/webservice.asmx?op=validaNOM151";

                $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
          <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Header>
              <AuthSoapHd xmlns="http://tempuri.org/">
                <Entidad>'.$this->entidadsv.'</Entidad>
                <Usuario>'.$this->usuariosv.'</Usuario>
                <Clave>'.$this->clavesv.'</Clave>
              </AuthSoapHd>
            </soap:Header>
            <soap:Body>
              <validaNOM151 xmlns="http://tempuri.org/">
                <nombreArchivoOriginal>'.$array['Original_FileName'].'</nombreArchivoOriginal>
                <huellaDigitalArchivo>'.$array['huella_digital'].'</huellaDigitalArchivo>
                <constancia>'.$array['Constancia_sv'].'</constancia>
              </validaNOM151>
            </soap:Body>
          </soap:Envelope>';


          $headers = array(
             "Content-type: text/xml;charset=\"utf-8\"",
             "Accept: text/xml",
             "Cache-Control: no-cache",
             "Pragma: no-cache",
             "SoapAction: http://tempuri.org/validaNOM151",
             "Content-length: ". strlen($xml_post_string),
          );


            $url = $soapURL;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl,CURLOPT_USERPWD, $this->usuariosv . ":" . $this->clavesv);

            curl_setopt($curl,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
            curl_setopt($curl,CURLOPT_TIMEOUT,30);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS, $xml_post_string);

            curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($curl);
            curl_close($curl);


            $xml = simplexml_load_string($response);
            $data = $xml->xpath("//soap:Body/*")[0];
            $details = $data->children("http://tempuri.org/");
            $reportePDF = $details->validaNOM151Result->reportePDF;

            //split constancia
            $split = explode('{"0":"', $reportePDF);
            $reportePDF = explode('"}', $split[0]);
            
    }

    public function validaConstancia($array){

      $soapURL = "https://pilot-psc.reachcore.com/wsnom_validacion/webservice.asmx?op=validaNOM151";

                $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
          <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Header>
              <AuthSoapHd xmlns="http://tempuri.org/">
                <Entidad>'.$this->entidadsv.'</Entidad>
                <Usuario>'.$this->usuariosv.'</Usuario>
                <Clave>'.$this->clavesv.'</Clave>
              </AuthSoapHd>
            </soap:Header>
            <soap:Body>
              <validaNOM151 xmlns="http://tempuri.org/">
                <nombreArchivoOriginal>'.$array['Original_FileName'].'</nombreArchivoOriginal>
                <huellaDigitalArchivo>'.$array['huella_digital'].'</huellaDigitalArchivo>
                <constancia>'.$array['Constancia_sv'].'</constancia>
              </validaNOM151>
            </soap:Body>
          </soap:Envelope>';


          $headers = array(
             "Content-type: text/xml;charset=\"utf-8\"",
             "Accept: text/xml",
             "Cache-Control: no-cache",
             "Pragma: no-cache",
             "SoapAction: http://tempuri.org/validaNOM151",
             "Content-length: ". strlen($xml_post_string),
          );


            $url = $soapURL;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl,CURLOPT_USERPWD, $this->usuariosv . ":" . $this->clavesv);

            curl_setopt($curl,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
            curl_setopt($curl,CURLOPT_TIMEOUT,30);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS, $xml_post_string);

            curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($curl);
            curl_close($curl);


            $xml = simplexml_load_string($response);
            $data = $xml->xpath("//soap:Body/*")[0];
            $details = $data->children("http://tempuri.org/");
            $reportePDF = $details->validaNOM151Result->reportePDF;

            //split constancia
            $split = explode('{"0":"', $reportePDF);
            $reportePDF = explode('"}', $split[0]);
            
            //key, value, array 
            $arrayjson = $this->AddItem("reporte_pdf_valida", $reportePDF[0], $array); //key, value, array 
            return $arrayjson;

    }

    public function subir_pdf_validador($array){
        $binary = base64_decode($array['reporte_pdf_valida']);
        $nombrefille = $array['huella_digital'] . ".pdf";
        

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->urlnube .'/docuware/platform/FileCabinets/'.$this->idarchivador.'/Sections?DocId='. $this->iddocumento,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_COOKIEFILE => $this->cookie,
          // CURLOPT_POSTFIELDS => ["upload" => $cf],
          CURLOPT_POSTFIELDS => $binary,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/octet-stream',
            'Content-Disposition: file; filename="'.$nombrefille.'"',
            'Accept: application/json'
          ),
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);
        $retcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

         if ($err) {
               $arrayjson = array('error' => true, "message" => "error al subir pdf validador");
          } else {
              $arrayjson = $this->AddItem("upload_pdf_validador", true, $array); //key, value, array 
          }

          return $arrayjson;
    }

    public function generarConstancia($file_tsq,$getdocument){ //ruta file tsq, respuesta documento(docuware)
        //Convertimos el archivo .tsq a base64
        $file = file_get_contents($file_tsq);
        $database64 = base64_encode($file);
        //obtenemos nombre original del documento
        $nombreArchivo = $getdocument->Title;

        
        //GENERAMOS CONSTANCIA
         $soapURL = $this->ambiente ."?op=GeneraConstancia";
         $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
          <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Header>
              <AuthSoapHd xmlns="www.XMLWebServiceSoapHeaderAuth.net">
                <Usuario>'.$this->usuariosv.'</Usuario>
                <Clave>'.$this->clavesv.'</Clave>
                <Entidad>'.$this->entidadsv.'</Entidad>
              </AuthSoapHd>
            </soap:Header>
            <soap:Body>
              <GeneraConstancia xmlns="www.XMLWebServiceSoapHeaderAuth.net">
                <referencia>'.$this->referenciasv.'</referencia>
                <solicitud>'.$database64.'</solicitud>
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
            curl_setopt($curl,CURLOPT_USERPWD, $this->usuariosv . ":" . $this->clavesv);

            curl_setopt($curl,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
            curl_setopt($curl,CURLOPT_TIMEOUT,30);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS, $xml_post_string);

            curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            $retcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            $error = "false";

          if ($err) {
              $resp = array('error' => true, "solicitud"=>'', "constancia"=> '',"estado"=> '',"folio"=> '');
            return $resp;
          } else {
            /*===============================================================
            //RESPUESTA DEL SERVICIO SOVOS NOM151 (GENERACION DE CONSTANCIA)
            =================================================================
            */
            $xml = simplexml_load_string($response);
            $data = $xml->xpath("//soap:Body/*")[0];
            $details = $data->children("www.XMLWebServiceSoapHeaderAuth.net");
            $constancia = $details->GeneraConstanciaResult->Constancia;
            $estado = $details->GeneraConstanciaResult->Estado;
            $folio = $details->GeneraConstanciaResult->Folio;
            /*===============================================================
            //SE REALIZA LA SUBIDA DEL DOCUMENTO ASN1 A DOCUWARE
            =================================================================
            */
             
             //convertimos la constancia(base64) a archivo(asn1)
             $nombreArchivoExtension = $nombreArchivo . ".asn1";
             $respbase64tofile = $this->base64Tofile($constancia,$nombreArchivo); //constancia(base64), nombre archivo original.
             $this->upload_file_document($constancia,$nombreArchivoExtension); //constancia base64, nombre archivo
             //validar si se subio el documento ?????

             $resp = array('error' => false, "solicitud"=>$database64, "constancia"=> $constancia,"estado"=>$estado,"folio"=>$folio, "path_asn1"=>$respbase64tofile['pathfile_asn1']);

          }

         return $resp;
    }


   public function base64Tofile($stringbase64,$nombreArchivo){ //constancia(base64), nombre archivo original sin extension
      $content = base64_decode($stringbase64);
      $nuevo_fichero = getcwd() .'/class/files_asn1/' . $nombreArchivo . ".asn1";
      $this->set_url_bin($nuevo_fichero);

      $fp = fopen($nuevo_fichero, 'w');
      fwrite($fp, $content);
      fclose($fp);
      
     $array = array("pathfile_asn1" => $nuevo_fichero);
     return $array; 
    }


      public function upload_file_document($constanciabase64,$nombrearchivo){ //constancia, nombre de archivo

      $binary = base64_decode($constanciabase64);

      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $this->urlnube .'/docuware/platform/FileCabinets/'.$this->idarchivador.'/Sections?DocId='. $this->iddocumento,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_COOKIEFILE => $this->cookie,
        // CURLOPT_POSTFIELDS => ["upload" => $cf],
        CURLOPT_POSTFIELDS => $binary,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/octet-stream',
          'Content-Disposition: file; filename="'.$nombrearchivo.'"',
          'Accept: application/json'
        ),
      ));

      //          'X-File-ModifiedDate: 2020-08-26T00:00:00.000Z',

      $response = curl_exec($curl);

      curl_close($curl);
      return $response;
    }

    public function get_document(){

         $curl = curl_init();
                  curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->urlnube .'/docuware/platform/FileCabinets/'.$this->idarchivador.'/Documents/' .  $this->iddocumento,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_COOKIEFILE => $this->cookie,
                    CURLOPT_HTTPHEADER => array(
                      'Accept: application/json'
                    ),
                  ));
            
                  $response = curl_exec($curl);
                  $err = curl_error($curl);
                  $retcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                  curl_close($curl);

                  if ($err) {
                    return $err;
                  } else {
                    return json_decode($response);
                  }

    }

    public function Genera_solic_constSHA256($path,$document,$arrayjson){
      $year = date("o");
      $month = date("m");
      $daymonth = date("d");
      $estructura = getcwd() .'/class/files_tsq/'.$year .'/' . $month . '/' . $daymonth;
      $file_out = $estructura . "/" . $document->Title.'.tsq';
      $existeDir = false;
      //Generamos directorio
      if(is_dir($estructura)){
          $existeDir = true;
      }else{
            //Generamos directorio
             $res = mkdir($estructura, 0777, true);
             if($res){
               $existeDir = true;
             }else{
               $existeDir = false;
             } 
      }

      if($existeDir){ // generamos el archivo .TSQ

        $output = shell_exec('openssl ts -query -data "'.$path.'" -sha256 -no_nonce -policy '.$this->policysv.' -out "'. $file_out. '"');

          if (file_exists($file_out)) {
              $arrayjsonresult = $this->AddItem("path_tsq", $file_out, $arrayjson); //key, value, array
          }
      }


      return $arrayjsonresult;
    }

    public function download_document(){
     /*
     =================================
     OBTEBEMOS LOS DATOS DEL DOCUMENTO 
     =================================
     */
    $getdocument = $this->get_document();


    //Validamos documento valido
    IF($this->allowedResourceTypes($getdocument->ContentType)){ //solo archivos pdf
           $curl =  curl_init();
                    curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->urlnube ."/docuware/platform/FileCabinets/".$this->idarchivador."/Documents/".$this->iddocumento."/FileDownload?targetFileType=Auto&keepAnnotations=false",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_COOKIEFILE => $this->cookie
                  ));
            
                  $response = curl_exec($curl);
                  $err = curl_error($curl);
                  $retcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                  curl_close($curl);

                  if ($err) {
                    return $err;
                  } else {
                     //DESCARGAMOS EL PDF DE DOCUWARE Y LO GUARDAMOS EN EL SERVIDOR
                     $result_archivo = $this->guardarArchivoServidor($getdocument,$response);
                     if($result_archivo['error'] == false){
    
                       // //VALIDAMOS CONSTANCIA
                       $this->validarConstacia == true ?   // realizar la validacion de constancia
                         $result = $this->validaConstancia($result_archivo) //array
                       : $result = $result_archivo;  // no se valida constancia

                        
                        if($result['error'] == false){
                           $result_archivo = $result;
                           //SUBIMOS ARCHIVO PDF VALIDAR CONSTANCIA A DOCUWARE
                           $result = $this->subir_pdf_validador($result);
                           if($result['error'] == false){
                               $result_archivo = $result;
                           }else{
                               // var_dump("error al subir pdf validador")
                           }
                        }else{
                          // var_dump("error al validar constancia")
                        }
                     }else{
                       //var_dump(" error en generar constancia");
                     }
                     

                      return $result_archivo;
                  }
    }else{
      //archivo invalido
       $error = "true";
       $message = "Tipo de documento no valido";
       $respuesta = '{"error": '.$error.',"ContentType" : "" ,"message": "'.$message .'","id_document": "", "hash": ""}';
      return json_decode($respuesta);
    }

       
                  
    }

    public function crearDirectorio($document){

          $date = time();
          $year = date("o");
          $month = date("m");
          $daymonth = date("d");

          $estructura = getcwd() .'/class/files/'.$year .'/' . $month . '/' . $daymonth;
          $nombreArchivo = $document->Sections;
          $nombreArchivo = $nombreArchivo[0]->OriginalFileName;
          $patharchivo = $estructura . "/" . $nombreArchivo;
          $existeDir = 0;

          if(is_dir($estructura)){
              $existeDir = 1;
          }else{
            //Generamos directorio
             $res = mkdir($estructura, 0777, true);
             if($res){
               $existeDir = 1;
             }else{
               $existeDir = 0;
             } 
          }

          $array = array("existeDir" => $existeDir, "path" => $patharchivo);
          return $array;              
    }

    public function guardarArchivoServidor($getdocument,$respArchivoDW){
          $filename = $getdocument->Title;
                     
          //GENERAMOS EL DICTORIO
          $directorio = $this->crearDirectorio($getdocument);
          $path = $directorio['path'];
          //COPIAMOS ARCHIVO PDF AL SERVIDOR
          $fp = fopen($path, 'w');
          fwrite($fp, $respArchivoDW);
          fclose($fp);
          
          if (file_exists($path)) {
    
              //Generamos la solicitud de constancia openSSL
              sleep(4);
              $arrayjson = array();
              $arrayjson = array('error' => false);
              $arrayjson = $this->AddItem("message", "satisfactorio", $arrayjson); //key, value, array 
              $resArrayTsq = $this->Genera_solic_constSHA256($path,$getdocument,$arrayjson);
              //regresamos el nuevo arrayjson solo si genero el archivo
              $arrayjson = count($resArrayTsq)> 0 ? $resArrayTsq : $arrayjson;

              // Crear constancia api sovos
              $result_generar = count($resArrayTsq)> 0 ? 1 : null;
              $result_generar = count($resArrayTsq)> 0 ? $this->generarConstancia($resArrayTsq['path_tsq'],$getdocument) : null;
              
              //nombre del archivo original
              $nombreArchivo = $getdocument->Sections;
              $nombreArchivo = $nombreArchivo[0]->OriginalFileName;
              //key, value, array 
              $arrayjson = $this->AddItem("Original_FileName", $nombreArchivo, $arrayjson); //key, value, array
              //key, value, array 
              $arrayjson = $this->AddItem("Path_Original_FileName", $path, $arrayjson); //key, value, array 
                   // var_dump($result_generar);

              if($result_generar['error']==false){
                //key, value, array 
                $arrayjson = $this->AddItem("path_asn1", $result_generar['path_asn1'], $arrayjson); //key, value, array 
                
                $arrayjson = $this->AddItem("solicitud_sv", $result_generar['solicitud'], $arrayjson); //key, value, array 
                //OBTENEMOS LA HUELLA DIGITAL
                $huellaDigital = $this->huellaDigital($result_generar['solicitud']);
                $arrayjson = $this->AddItem("huella_digital", $huellaDigital, $arrayjson); //key, value, array 

                //split constancia
                $split = explode('{"0":"', $result_generar['constancia']);
                $constancia = explode('"}', $split[0]);

                //split estado
                $splitestado = explode('{"0": "', $result_generar['estado']);
                $estado = explode('"', $splitestado[0]);

                //split folio
                $splitfolio = explode('{"0": "', $result_generar['folio']);
                $folio = explode('"', $splitfolio[0]);
                
                //key, value, array 
                $arrayjson = $this->AddItem("Constancia_sv", $constancia[0], $arrayjson); 
                //key, value, array 
                $arrayjson = $this->AddItem("Estatus_genera_const_sv", $estado[0], $arrayjson); 
                //key, value, array 
                $arrayjson = $this->AddItem("folio_sv", intval($folio[0]), $arrayjson);


               }
              
                        // $json_resp = json_encode($arrayjson);

                        // return json_decode($json_resp);
               return $arrayjson;
          } else {
                        $arrayjson = array('error' => true);
                        $arrayjson = $this->AddItem("message", "error al descargar archivo de docuware", $arrayjson); //key, value, array
                        $json_resp = json_encode($arrayjson);
                        return json_decode($json_resp);
          } 
                    
    }

    public function AddItem($key, $value, $array){
      $array[$key] = $value;
      return $array;
    }

    public function tipodocumento($id,$retcode){
        if($retcode==404){
          return 0;
        }
    }

    public function allowedResourceTypes($file){
      //Definimos los tipos de archivos validos
      $allowedResourceTypes = [
          'application/pdf',
          'application/xml'
      ];
      $ok = true;
        if( !in_array($file, $allowedResourceTypes) ){
           $ok = false; 
        }
      return $ok;
    }

}// class

?>