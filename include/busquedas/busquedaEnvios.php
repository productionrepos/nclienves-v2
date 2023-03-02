<?php

function totalEnvios($id,$inicio,$fin){
    $conn = new bd();
    $conn -> conectar();

    $queryCantBultos = "SELECT count(id_pedido) as suma FROM pedido
                    where id_cliente = $id and estado_pedido in (2,3)
                    and timestamp_pedido BETWEEN $inicio and $fin";

    if($res = $conn->mysqli->query($queryCantBultos))
    {
        $dato = $res ->fetch_object();
    }
    else{
        $dato = $conn->mysqli->error;
    }

    return $dato;
}

function totalEnviosEntregados($id,$inicio,$fin){
    $conn = new bd();
    $conn -> conectar();

    $queryCantBultosEntregados = "SELECT count(id_bulto) as suma FROM bulto b inner JOIN pedido p on p.id_pedido = b.id_pedido 
                                where p.id_cliente = $id and p.estado_pedido in (2,3) and b.estado_logistico = 5
                                and timestamp_pedido BETWEEN $inicio and $fin";

    if($res = $conn->mysqli->query($queryCantBultosEntregados))
    {
        $dato = $res ->fetch_object();
    }
    else{
        $dato = $conn->mysqli->error;
    }

    return $dato;
}

function totalEnviosEnTransito($id,$inicio,$fin){
    $conn = new bd();
    $conn -> conectar();

    $queryCantBultosEntregados = "SELECT count(id_bulto) as suma FROM bulto b inner JOIN pedido p on p.id_pedido = b.id_pedido 
                                where p.id_cliente = $id and p.estado_pedido in (2,3) and b.estado_logistico = 4
                                and timestamp_pedido BETWEEN $inicio and $fin";

    if($res = $conn->mysqli->query($queryCantBultosEntregados))
    {
        $dato = $res ->fetch_object();
    }
    else{
        $dato = $conn->mysqli->error;
    }

    return $dato;
}

function totalEnviosConProblemas($id,$inicio,$fin){
    $conn = new bd();
    $conn -> conectar();

    $queryCantBultosEntregados = "SELECT count(id_bulto) as suma FROM bulto b inner JOIN pedido p on p.id_pedido = b.id_pedido 
                                where p.id_cliente = $id and p.estado_pedido in (2,3) and b.estado_logistico = 6
                                and timestamp_pedido BETWEEN $inicio and $fin";

    if($res = $conn->mysqli->query($queryCantBultosEntregados))
    {
        $dato = $res ->fetch_object();
    }
    else{
        $dato = $conn->mysqli->error;
    }

    return $dato;
}

