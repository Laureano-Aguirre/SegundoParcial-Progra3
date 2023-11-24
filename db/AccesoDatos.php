<?php

class AccesoDatos{
    private static $objetoAccesoDatos;
    private $objetoPDO;

    private function __construct(){
        try { 
            $this->objetoPDO = new PDO('mysql:host=localhost;dbname=restaurante_db;charset=utf8', 'root', '');
            $this->objetoPDO->exec("SET CHARACTER SET utf8");
            } 
        catch (PDOException $e) { 
            print "ERROR!: " . $e->getMessage(); 
            die();
        }
    }

    public function __clone(){ 
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR); 
    }

    public static function dameUnObjetoAcceso(){ 
        if (!isset(self::$objetoAccesoDatos)) {          
            self::$objetoAccesoDatos = new AccesoDatos(); 
        } 
        return self::$objetoAccesoDatos;        
    }

    public function retornarConsulta($sql){ 
        return $this->objetoPDO->prepare($sql); 
    }

    public function retornarUltimoId()
    { 
        return $this->objetoPDO->lastInsertId(); 
    }
}



?>