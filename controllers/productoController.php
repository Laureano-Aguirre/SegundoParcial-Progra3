<?php

include_once '../class/producto.php';

class productoController{

    public function agregarProducto($tipo, $descripcion, $cantidad){
        $producto = new ProductoRestaurante();
        $producto->tipo = $tipo;
        $producto->descripcion = $descripcion;
        $producto->cantidad = $cantidad;
        return $producto->agregarProducto();
    }

    public function listarProductos(){
        return ProductoRestaurante::listarProductos();
    }

    public function modificarProducto($idProducto, $cantidad){
        $producto = new ProductoRestaurante();
        $producto->id = $idProducto;
        $producto->cantidad = $cantidad;
        return $producto->modificarProducto();
    }

    public function borrarProducto($idProducto){
        $producto = new ProductoRestaurante();
        $producto->id = $idProducto;
        return $producto->borrarProducto();
    }
}

?>