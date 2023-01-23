<?php
require_once "respuestas.class.php";


class documenthash {
  private $dominio;
  private $urlnube;
  private $userdw;
  private $passworddw;
  private $idarchivador;
  private $iddocumento;
  private $cookie;


      public function __construct($dataarray){
        $this->dominio = $dataarray['dominio'];
        $this->urlnube = $dataarray['url_nube'];
        $this->userdw = $dataarray['user_dw'];
        $this->passworddw = $dataarray['password_dw'];
        $this->idarchivador = $dataarray['id_archivador'];
        $this->iddocumento = $dataarray['id_documento'];
        $this->cookie = getcwd() .'/class/cookies.txt';
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


    public function download_document(){
     /*
     =================================
     OBTEBEMOS LOS DATOS DEL DOCUMENTO 
     =================================
     */
    $getdocument = $this->get_document();
    //Validamos archivo valido
    IF($this->allowedResourceTypes($getdocument->ContentType)){
       //archivo valido
           $curl = curl_init();
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
                     $filename = rand(1, 100000);
                     $date = time();
                     $nuevo_fichero = getcwd() .'/class/' . $filename . $date . ".pdf";

                      $fp = fopen($nuevo_fichero, 'w');
                      fwrite($fp, $response);
                      fclose($fp);
                     if (file_exists($nuevo_fichero)) {
                         // return hash_file('sha256', $nuevo_fichero);
                        $hash = hash_file('sha256', $nuevo_fichero);
                        $error = "false";
                        $respuesta = '{"error": '.$error.',"message": "","id_document": "'.$this->iddocumento.'", "ContentType":"'.$getdocument->ContentType.'", "hash": "'. $hash .'"}';
                        return json_decode($respuesta);
                      } else {
                           return "El fichero $nombre_fichero no existe";
                      } 
                    
                  }
    }else{
      //archivo invalido
       $error = "true";
       $message = "Tipo de documento no valido";
       $respuesta = '{"error": '.$error.',"ContentType" : "" ,"message": "'.$message .'","id_document": "", "hash": ""}';
      return json_decode($respuesta);
    }

       
                  
    }

    public function tipodocumento($id,$retcode){
        if($retcode==404){
          return 0;
        }
    }

}// class

?>