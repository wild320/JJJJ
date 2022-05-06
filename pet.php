<?php

require_once 'class/responses-class.php';
require_once 'class/pets-class.php';


$_responses = new responses;
$_pets = new pets;

    //Metodo GET
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listPets=$_pets->listPets($pagina);
        header("content-type: application/json");
        echo json_encode($listPets);
        http_response_code(200);
    }elseif(isset($_GET['id'])){
        $id = $_GET['id'];
        $dataPet = $_pets->getPet($id);
        header("content-type: application/json");        
        echo json_encode($dataPet);
        http_response_code(200);
    }    
    //Metodo POST
}elseif($_SERVER['REQUEST_METHOD'] == 'POST'){
      //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
      //enviamos los datos al manejador
    $datosArray = $_pets->post($postBody);
      //delvovemos una respuesta 
     header('Content-Type: application/json');
     if(isset($datosArray["result"]["error_id"])){
         $responseCode = $datosArray["result"]["error_id"];
         http_response_code($responseCode);
     }else{
         http_response_code(200);
     }
     echo json_encode($datosArray);
    
    



}elseif($_SERVER['REQUEST_METHOD'] == 'PUT'){

     //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
     //enviamos los datos al manejador
    $datosArray = $_pets->put($postBody);
     //delvovemos una respuesta 
    header('Content-Type: application/json');
    if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);
   
   
    
}elseif($_SERVER['REQUEST_METHOD'] == 'DELETE'){

    
     //recibimos los datos enviados
     $postBody = file_get_contents("php://input");
     //enviamos los datos al manejador
    $datosArray = $_pets->delete($postBody);
     //delvovemos una respuesta 
    header('Content-Type: application/json');
    if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);
    
    
}else{
    header('content-type:application/json');
    $datosarray = $_responses->error_405();
    echo json_encode($datosarray);
}

?>