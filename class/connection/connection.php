<?php

class connection{
   private $server;
   private $user;
   private $password;
   private $database;
   private $port;
   private $conection;

   //funcion constructora para instanciar los datos de la configuracion de la base de datos
   function __construct(){
       $listdata=$this->dataConection();
       foreach ($listdata as $key => $value) {
           $this->server = $value["server"];
           $this->user = $value["user"];
           $this->password = $value["password"];
           $this->database = $value["database"];
           $this->port = $value["port"];                   
       }
       //creo la conexiona mysql
       $this->connection = new mysqli($this->server,$this->user,$this->password,$this->database,$this->port);
       if($this->connection->connect_errno){
           echo " algo salio mal";
           die();
       }


   }
   /**la funcion lee el array json del archivo config donde tengo la configuracion de la base de datos
    * para el momento de mandar a produccion el proyecto sea facil cambiar la url de la base de datos    * 
    */
   
   private function dataConection(){
       $direction=dirname(__FILE__);
       $jsondata = file_get_contents($direction . "/" . "config");
       return json_decode($jsondata,true);
   }

   private function convertUTF8($array){
       array_walk_recursive($array,function(&$item,$key){
           if(!mb_detect_encoding($item,'utf-8',true)){
               $item = utf8_decode($item);
           }
       });
       return $array;
   }

   public function getData($sqlstr){
       $results = $this->connection->query($sqlstr);
       $resultArray = array();
       foreach ($results as $key ) {
          $resultArray[]=$key;
       }
       return $this->convertUTF8($resultArray);
   }


   //Metodo para guardar

   public function nonQuery($sqlstr){
    $results = $this->connection->query($sqlstr);
    return $this->connection->affected_rows;
   }

   //INSERT
   public function nonQueryId($sqlstr){
    $results = $this->connection->query($sqlstr);
    $filas= $this->connection->affected_rows;
    if($filas>=1){
        return $this->connection->insert_id;

    }else{
        return 0;
    }
   }





}
?>