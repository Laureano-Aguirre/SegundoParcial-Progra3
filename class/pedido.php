<?php

include_once '../db/AccesoDatos.php';

class Pedido{
    public $idPedido;
    public $nombreCliente;
    public $idEmpleado;
    public $productos = array();
    public $estado;
    public $tiempoFinalizacion;

    public function agregarPedido(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT into pedidos (id_pedido,nombre_cliente,id_empleado,productos,estado,tiempo_finalizacion) values(:idPedido, :nombreCliente, :idEmpleado, :productos, :estado, :tiempoFinalizacion)");
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':idEmpleado', $this->idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':productos', $this->productos, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoFinalizacion', $this->tiempoFinalizacion, PDO::PARAM_INT);
        $consulta->execute();
        return $objetoAccesoDato->retornarUltimoId();   //falta pasar los productos

    }

    public static function listarPedidos(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_pedido as idPedido ,nombre_cliente as nombreCliente, id_empleado as idEmpleado, productos, estado, tiempo_finalizacion as tiempoFinalizacion FROM  pedidos");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "pedido");
    }

    public function modificarPedido(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("UPDATE pedidos SET productos=:productos, estado=:estado, tiempo_finalizacion=:tiempoFinalizacion WHERE id_pedido=:id");
        $consulta->bindValue(':id', $this->idPedido, PDO::PARAM_STR);
        $consulta->bindValue(':productos', $this->productos, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoFinalizacion', $this->tiempoFinalizacion, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public function borrarPedido(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("DELETE from pedidos WHERE id_pedido=:idPedido");
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_STR);
        return $consulta->execute();
    }

    public static function generarIdAlfanumerico($length = 5) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $id = '';
        
        for ($i = 0; $i < $length; $i++) {
            $id .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $id;
    }
}


?>