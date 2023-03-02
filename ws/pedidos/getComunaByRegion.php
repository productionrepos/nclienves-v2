<?php
    
    include_once('../bd/dbconn.php');
    $idregion = $_POST['idregion'];
        $conn = new bd();
        $conn->conectar();

    $query = 'SELECT co.nombre_comuna as nombre, id_comuna as id FROM provincia pro
            inner join comuna co on co.id_provincia = pro.id_provincia
            inner join region re on re.id_region = pro.id_region
            where re.id_region ='.$idregion.";";

    if($res = $conn -> mysqli->query($query)){
            while($datareg = mysqli_fetch_array($res))
                {
                    $nombre = $datareg['nombre'];
                    $id = $datareg["id"];
                    $return_array[]=array(
                        "nombre" => $nombre,
                        "id"  => $id
                    );
            }
            echo json_encode($return_array, JSON_FORCE_OBJECT);
    }


    





?>