<?php

require_once 'connection/connection.php';
require_once 'responses-class.php';

class pets extends connection{

    private $idPet;
    private $id;
    private $name;
    private $photo;
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

        $query = "SELECT id, name, category, status FROM pets limit $inicio,$cantidad";
        $datos = parent::getData($query);
        return ($datos);
    }

    //obtener mascota por id
    public function getPet($id){
        $query = "SELECT * FROM  pets WHERE id= '$id'";
        return parent::getData($query);
    }

    public function post($json){
        $_responses = new responses;
        $datos=json_decode($json,true);

        if(!isset($datos['id'])|| !isset($datos['name'])){
            return $_responses->error_400();
        }else{
            $this->id=$datos['id'];            
            $this->name=$datos['name'];                        
            if(isset($datos['photo'])){$this->photo=$datos['photo'];} 
            if(isset($datos['category'])){$this->category=$datos['category'];}
            if(isset($datos['status'])){$this->status=$datos['status'];}     
            $resp=$this->insertPet();
            if($resp){
                $response = $_responses->response;
                $response["result"]=array(
                    "id"=>$resp
                );                
                return $response;
            }else{
                return $_responses->error_500();
            }

        }
    }

    private function insertPet(){
        $query = "INSERT INTO pets (id,name,photo,category,status) 
            VALUES ('".$this->id."', '".$this->name."','".$this->photo."', '".$this->category."', '".$this->status."')";
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
            if(isset($datos['name'])){$this->name=$datos['name'];}            
            if(isset($datos['photo'])){$this->photo=$datos['photo'];} 
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
        $query = "UPDATE pets SET name='".$this->name."',photo='".$this->photo."',category='"
            .$this->category."',status='".$this->status."' WHERE idPet ='".$this->idPet."'";   
                
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