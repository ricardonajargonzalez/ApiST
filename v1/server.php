<?php

  //definimos los recursos disponibles de la api
    $allowedResourceTypes = [
        'hashDocument',
        'getDocument',
        'GeneraConstancia',
        'ValidaConstancia'
    ];

  //validamos que el recurso este disponible
        $resourceType = $_GET['resource_type'];
        //si no encontramos ningun elemento que coincida del arreglo terminamos el script
        if( !in_array($resourceType, $allowedResourceTypes) ){
            header('Content-Type: application/json');
            $datosArray = $_respuestas->error_400();
            echo json_encode($datosArray);
            die;
        }


    switch( $resourceType ){
    case 'hashDocument':
        require 'hash_document_resource/index.php';
        break;
    case 'GeneraConstancia':
        require 'genera_constancia_resource/index.php';
        break;
    case 'ValidaConstancia':
        require 'valida_constancia_resource/index.php';
        break;
   }

?>