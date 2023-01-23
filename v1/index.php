
<?php
  header('Access-Control-Allow-Origin: *');
  require_once 'class/respuestas.class.php';


  $_respuestas = new respuestas;
  $matches = [];

   /* 
    La expresiones regulares se encapsula con el comienzo de "/" y terminando con "/".
    Cada "\/" es un "/".
    Cada "([^\/]+)" es una expresion de texto.
    En esta empresion de tiene : / + texto + / + texto + / + texto + / + texto
    
    Base url "/apiTS/v1/" +
    servicio api "hashDocument"   +
    id       /1

   */

  //GET A PARTICULAR DOCUMENT
 if (preg_match('/\/([^\/]+)\/([^\/]+)\/([^\/]+)\/([^\/]+)/', $_SERVER["REQUEST_URI"], $matches)) {
     $_GET['resource_type'] = $matches[3]; // type service
     $_GET['resource_id'] = $matches[4];   //recurse id ó ambiente(piloto/produccion)
  
     require 'server.php';

  }else if(preg_match('/\/([^\/]+)\/([^\/]+)\/([^\/]+)\//', $_SERVER["REQUEST_URI"], $matches)) {
    //ALL DOCUMENTS
     $_GET['resource_type'] = $matches[3];

     require 'server.php';
  }else{
      header('Content-Type: application/json');
      $datosArray = $_respuestas->error_400();
      echo json_encode($datosArray);
  }

?>