<?php

include_once '../db/AccesoDatos.php';

class ProductoRestaurante{
    public $id;
    public $tipo;
    public $descripcion;
    public $cantidad;

    public function agregarProducto(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT into productos (tipo,descripcion,cantidad) values(:tipo, :descripcion, :cantidad)");
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->execute();
        return $objetoAccesoDato->retornarUltimoId();
    }

    public static function listarProductos(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_producto as id, tipo, descripcion FROM  productos");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "productoRestaurante");
    }

    public function modificarProducto(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("UPDATE productos SET cantidad=:cantidad WHERE id_producto=:idProducto");
        $consulta->bindValue(':idProducto', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public function borrarProducto(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("DELETE from productos WHERE id_producto=:idProducto");
        $consulta->bindValue(':idProducto', $this->id, PDO::PARAM_INT);
        return $consulta->execute();
    }
}



?>