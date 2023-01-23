<?php
    //INDEX.PHP GENERA CONSTANCIA
    require_once 'class/generaConstancia.class.php';
	require_once 'class/respuestas.class.php';
    $entityBody = file_get_contents('php://input');
    $data = json_decode($entityBody, TRUE);
	$_respuestas = new respuestas;
	

   
    //levantamos el id del recurso buscado
    $resourceId =  array_key_exists('resource_id', $_GET) ? $_GET['resource_id'] : "0";
    

   
   switch( strtoupper($_SERVER['REQUEST_METHOD']) ){
    case 'GET':
            header('Content-Type: application/json');
            $datosArray = $_respuestas->error_405();
            http_response_code(405);
            echo json_encode($datosArray);
        break;
    case 'POST':
          if($resourceId=='piloto' or $resourceId=='produccion'){
              if($resourceId=='piloto'){
                 $ambiente = 'https://pilot-psc.reachcore.com/wsnom151/webservice.asmx';
              }else if($resourceId=='produccion'){
                 $ambiente  = 'https://nom151.advantage-security.com/wsnom151/webservice.asmx';
              }
              
              $data['ambiente'] = $ambiente;
              $_generaConstancia = new generaConstancia($data);
              //solicitud de generacion de constancia
              $constancia = $_generaConstancia->download_document();
              header("Content-Type: application/json");
              echo json_encode($constancia);
              http_response_code(200);
               
          }else{
            header('Content-Type: application/json');
            $datosArray = $_respuestas->error_200_3();
            http_response_code(200);
            echo json_encode($datosArray);
          }
        break;
    case 'PUT':
            header('Content-Type: application/json');
            $datosArray = $_respuestas->error_405();
            http_response_code(405);
            echo json_encode($datosArray);
        break;
    case 'DELETE':
            header('Content-Type: application/json');
            $datosArray = $_respuestas->error_405();
            http_response_code(405);
            echo json_encode($datosArray);
        break;
   }
?>