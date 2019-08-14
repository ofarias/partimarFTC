<?php
session_start();
require_once('app/controller/pegaso.controller.php');
$controller = new pegaso_controller;
$target_dir = "C:\\xampp\\htdocs\\DocRemision\\";
$target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);
$file = basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
$doc=$_POST['doc'];
$tipodoc=$_POST['tipodoc'];
$tipo=$_POST['tipo'];
$costo=$_POST['costo'];
$cxp=$_POST['cxp'];
$impuesto=$_POST['impuesto'];

echo strtoupper($fileType).'<br/>';
if ($_FILES["fileToUpload"]["size"] > ((1024*1024)*20)) {
    echo "El archivo dede medir menos de 20 MB.";
    $uploadOk = 0;
}else{
    if (file_exists($target_file)  
        // or ( strtoupper($fileType) != ("PDF") or strtoupper($fileType) !=("XLSX") or strtoupper($fileType) !=("JPG") or strtoupper($fileType) !=("PNG") or strtoupper($fileType)!=("TXT") ) 
        ){
        echo "El Archivo que cargo, ya existen en el Sistema. Se creo asignacion al documento <p>";
        //echo "o el archivo no es valido; solo se pueden subir arvhivos PDF. <p>";
        $uploadOk = 0;
        //$tipo = 'duplicado';
        $registro = $controller->guardaComprobante($target_file, $doc, $tipodoc, $target_dir, $file);
        $registrogasto = $controller->guardaGasto($tipo, $costo, $cxp, $impuesto, $doc, $tipodoc, $file);
        
    }else{
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "El Archivo: ". basename( $_FILES["fileToUpload"]["name"]). " se ha cargado.<p>";
            //$tipo = 'ok';
            $registro = $controller->guardaComprobante($target_file, $doc, $tipodoc, $target_dir, $file);
            $registrogasto = $controller->guardaGasto($tipo, $costo, $cxp, $impuesto, $doc, $tipodoc, $file);    
        } else {
            echo "Ocurrio un problema al subir su archivo, favor de revisarlo.";
        }
            echo 'Archivo: '.$target_file;
    }
}
?>