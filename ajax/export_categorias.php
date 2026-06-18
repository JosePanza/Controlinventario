<?php

    include('is_logged.php');
    /* Connect To Database*/
    require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

    // Get optional search filter
    $q = isset($_GET['q']) ? mysqli_real_escape_string($con, (strip_tags($_GET['q'], ENT_QUOTES))) : '';

    $sWhere = "";
    if ($q != ""){
        $sWhere = "WHERE nombre_categoria LIKE '%".$q."%'";
    }
    $sWhere .= " ORDER BY nombre_categoria";

    $sql = "SELECT id_categoria, nombre_categoria, descripcion_categoria, date_added FROM categorias " . $sWhere;
    $query = mysqli_query($con, $sql);

    // send headers for download
    $filename = 'categorias_'.date('Ymd').'.csv';
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    // output BOM for Excel compatibility
    echo "\xEF\xBB\xBF";

    $out = fopen('php://output', 'w');
    // column headers
    fputcsv($out, array('ID','Nombre','Descripción','Agregado'));

    if ($query){
        while ($row = mysqli_fetch_assoc($query)){
            $id = $row['id_categoria'];
            $nombre = $row['nombre_categoria'];
            $descripcion = $row['descripcion_categoria'];
            $date_added = date('d/m/Y', strtotime($row['date_added']));
            fputcsv($out, array($id, $nombre, $descripcion, $date_added));
        }
    }
    fclose($out);
    exit;

?>
