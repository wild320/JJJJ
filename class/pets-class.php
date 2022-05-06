<?php

require_once 'connection/connection.php';
require_once 'responses-class.php';

class pets extends connection{

    private $idPet;
    private $id;
    private $name;
    private $photoUrls;    
    private $tags;
    private $category;
    private $status;

    //obtener todas las mascotas con paginacion
    public function listPets($pagina = 1){
        $inicio = 0;
        $cantidad = 10;
        if($pagina>1){
            $inicio = ($cantidad = ($pagina -1))+1;
            $cantidad = $cantidad = $pagina;
        }

        $query = "SELECT id, name,photoUrls,tags, category, status FROM pets limit $inicio,$cantidad";
        $datos = parent::getData($query);
        return ($datos);
    }

    //obtener mascota por id
    public function getPet($id){
        $query = "SELECT * FROM  pets WHERE id= '$id'";
        return parent::getData($query);
    }

    //obtener mascota por Status
    public function getStatus($status){
        $query = "SELECT * FROM  pets WHERE status= '$status'";
        return parent::getData($query);
    }
    
        //metodo post
    public function post($json){
        
        $_responses = new responses;
            //recibo el json y lo convierto en un array
        $datos=json_decode($json,true);
            //compruebo campos requidos
        if(!isset($datos['id'])|| !isset($datos['name'])){
            return $_responses->error_400();
        }else{
            $this->id=$datos['id'];            
            $this->name=$datos['name'];                        
            if(isset($datos['photoUrls'])){
                $resp = $this-> photoEncode($datos['photoUrls']);
                $this->photoUrls=$resp;
            }              
            if(isset($datos['tags'])){$this->tags=$datos['tags'];}
            if(isset($datos['category'])){$this->category=$datos['category'];}
            if(isset($datos['status'])){$this->status=$datos['status'];}     
            $resp=$this->insertPet();
            if($resp){
                $response = $_responses->response;
                $response["result"]=array(
                    "Registro N° "=>$resp
                );                
                return $response;
            }else{
                return $_responses->error_500();
            }

        }
    }

    private function photoEncode($photoUrls){
        $direction = dirname(__DIR__) . "\public\images\\";
        $parts = explode(";base64,",$photoUrls);
        $extension = explode('/',mime_content_type($photoUrls))[1];
        $photo_base64 = base64_decode($parts[1]);
        $file = $direction . uniqid(). "." . $extension;
        file_put_contents($file,$photo_base64);
        $newDirection = str_replace('\\','/',$file);

        return $newDirection;
    }

    private function insertPet(){
        $query = "INSERT INTO pets (id,name,photoUrls,tags,category,status) 
            VALUES ('".$this->id."', '".$this->name."','".$this->photoUrls."','".$this->tags."', '".$this->category."', '".$this->status."')";
        $resp = parent::nonQueryId($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }

        
    }

    public function put($json){
        
        $_responses = new responses;
        $datos=json_decode($json,true);

        if(!isset($datos['idPet'])){
            return $_responses->error_400();
        }else{
            $this->idPet=$datos['idPet'];              
            if(isset($datos['id'])){$this->id=$datos['id'];}            
            if(isset($datos['name'])){$this->name=$datos['name'];} if(isset($datos['photoUrls'])){
                $resp = $this-> photoEncode($datos['photoUrls']);
                $this->photoUrls=$resp;
            } 
            if(isset($datos['tags'])){$this->tags=$datos['tags'];}
            if(isset($datos['category'])){$this->category=$datos['category'];}
            if(isset($datos['status'])){$this->status=$datos['status'];}   

            $resp=$this->editPet();
            if($resp){
                $response = $_responses->response;
                $response["result"]=array(
                    "id"=>$this->id,
                    "name"=>$this->name
                );                
                return $response;
            }else{
                return $_responses->error_500();
            }

        }
    }

    
    private function editPet(){
        $query = "UPDATE pets SET name='".$this->name."',photoUrls='".$this->photoUrls."',tags='".$this->tags."',
                category='".$this->category."',status='".$this->status."'WHERE idPet ='".$this->idPet."'";   
                
        $resp = parent::nonQuery($query);
        if($resp>=1){
            return $resp;
        }else{
            return 0;
        }

        
    }

    public function delete($json){
        
        $_responses = new responses;
        $datos=json_decode($json,true);

        if(!isset($datos['idPet'])){
            return $_responses->error_400();
        }else{
            $this->idPet=$datos['idPet'];
            $resp=$this->deletePet();
            if($resp){
                $response = $_responses->response;
                $response["result"]=array(
                    "idPet"=>$this->idPet
                );                
                return $response;
            }else{
                return $_responses->error_500();
            }

        }
    }

    private function deletePet(){
        $query = "DELETE FROM pets WHERE idPet =$this->idPet"; 
        $resp = parent::nonQuery($query);
        if ($resp >=1){
            return $resp;
        }else{
            return 0;
        }
    }


}




?>