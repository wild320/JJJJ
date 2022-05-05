<?php

class conection{
   private $server;
   private $user;
   private $password;
   private $database;
   private $port;
   private $conection;

   function __construct(){
       $listdata=$this->dataConection();
       foreach ($listdata as $key => $value) {
           $this->server = $value["server"];
           $this->user = $value["user"];
           $this->password = $value["password"];
           $this->database = $value["database"];
           $this->port = $value["port"];                   
       }
       $this->conection = new mysqli($this->server,$this->user,$this->password,$this->database,$this->port);
       if($this->conection->connect_errno){
           echo " algo salio mal";
       }

   }


   private function dataConection(){
       $direction=dirname(__FILE__);
       $jsondata = file_get_contents($direction . "/" . "config");
       return json_decode($jsondata,true);
   }
}
?>