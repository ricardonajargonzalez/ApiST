<?php
    require_once 'class/hashDocument.class.php';
	require_once 'class/respuestas.class.php';
    $entityBody = file_get_contents('php://input');
    $data = json_decode($entityBody, TRUE);
      // var_dump($data);

	$_respuestas = new respuestas;
	

   
    //levantamos el id del recurso buscado
    $resourceId =  array_key_exists('resource_id', $_GET) ? $_GET['resource_id'] : "0";
    

   
   switch( strtoupper($_SERVER['REQUEST_METHOD']) ){
    case 'GET':
            header('Content-Type: application/json');
            $datosArray = $_respuestas->error_405();
            http_response_code(405);
            echo json_encode($datosArray);

            // $_documenthash = new documenthash($data,$resourceId);
            // $document = $_documenthash->get_document();
            // header("Content-Type: application/json");
            // //echo json_encode($document);
            // http_response_code(200);
            // var_dump($document);
        break;
    case 'POST':
          // if($resourceId=="0"){
          //   header('Content-Type: application/json');
          //   $datosArray = $_respuestas->error_200_1();
          //   http_response_code(200);
          //   echo json_encode($datosArray);
          // }else{
            $_documenthash = new documenthash($data);
            $document = $_documenthash->download_document();
            header("Content-Type: application/json");
            echo json_encode($document);
            http_response_code(200);
          // }
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