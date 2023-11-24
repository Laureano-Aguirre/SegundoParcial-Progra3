<?php

include_once '../class/mesa.php';

class mesaController{

    public function agregarMesa($idCliente, $idPedido, $idMozo, $idEncuesta, $estado){
        $mesa = new Mesa();
        //$mesa->idMesa = $mesa->retornarUltimoID() + 1;
        //$mesa->idMesa = 10000;
        $mesa->idCliente= $idCliente;
        $mesa->idPedido = $idPedido;
        $mesa->idMozo = $idMozo;
        $mesa->idEncuesta = $idEncuesta;
        $mesa->estado = $estado;
        return $mesa->agregarMesa();
    }

    public function listarMesas(){
        return Mesa::listarMesa();
    }

    public function modificarMesa($idMesa, $estado){
        $mesa = new Mesa();
        $mesa->idMesa = $idMesa;
        $mesa->estado = $estado;
        return $mesa->modificarMesa();
    }

    public function borrarMesa($idMesa){
        $mesa = new Mesa();
        $mesa->idMesa = $idMesa;
        return $mesa->borrarMesa();
    }

}




?>