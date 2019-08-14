<?php
session_start();
//echo $_SESSION["unimed"];
date_default_timezone_set('America/Mexico_City');

//require_once('app/controller/pegaso.controller.php');
require_once('app/controller/tienda.controller.php');
$controller = new tienda_controller;
//echo $_POST['nombre'];
//echo $_POST['actualizausr'];
if(isset($_GET['action'])){
$action = $_GET['action'];

}else{
	$action = '';
}
if (isset($_POST['usuario'])){
	$controller->InsertaUsuarioN($_POST['usuario'], $_POST['contrasena'], $_POST['email'], $_POST['rol'], $_POST['letra']);	
}elseif (isset($_POST['cambioSenia'])){
	$nuevaSenia=$_POST['nuevaSenia'];
	$actual = $_POST['actualSenia'];
	$usuario=$_POST['u'];
	$controller->cambioSenia($nuevaSenia, $actual, $usuario );
}elseif(isset($_POST['faltaunidades'])){	
	$coordinador = $_POST['coordinador'];
	$controller->AltaUnidadesF($numero, $marca, $modelo, $placas, $operador, $tipo, $tipo2, $coordinador);
}elseif(isset($_POST['verAuxSaldosCxc'])) {
    $fechaini = $_POST['fechaini'];
    $fechafin = $_POST['fechafin'];
    $controller->verAuxSaldosCxc($fechaini, $fechafin);    
}elseif (isset($_POST['ventasXproducto'])) {
	$fechaini = $_POST['fechaini'];
    $fechafin = $_POST['fechafin'];
    $controller->ventasXproducto($fechaini, $fechafin);    
}
else{switch ($_GET['action']){
    case 'repVentas':
        if (isset($_GET['fechaini'])) {
                $fechaini = $_GET['fechaini'];
                $fechafin = $_GET['fechafin'];
                $impresion = $_GET['impresion'];
            } else {
                $fechaini = '';
                $fechafin = '';
                $impresion = 'no';
            }

            if ($impresion == 'no') {
                $controller->verAuxSaldosCxc($fechaini, $fechafin);
            } elseif ($impresion = 'si') {
                echo 'Manda a imprimir';
                $controller->imprimeES($fechaini, $fechafin);
            }
        break;
    case 'ventasXproducto':
    	if (isset($_GET['fechaini'])) {
                $fechaini = $_GET['fechaini'];
                $fechafin = $_GET['fechafin'];
                $impresion = $_GET['impresion'];
            } else {
                $fechaini = '';
                $fechafin = '';
                $impresion = 'no';
            }
            if ($impresion == 'no') {
                $controller->ventasXproducto($fechaini, $fechafin);
            } elseif ($impresion = 'si') {
                echo 'Manda a imprimir';
                $controller->imprimeESvxp($fechaini, $fechafin);
            }
        break;   
	default:
		header('Location: index.php?action=login');
	break;
	}

}
?>