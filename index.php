<?php
session_start();
//echo $_SESSION["unimed"];
date_default_timezone_set('America/Mexico_City');

require_once('app/controller/pegaso.controller.php');
$controller = new pegaso_controller;
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
	$numero = $_POST['numero'];
	$marca = $_POST['marca'];
	$modelo = $_POST['modelo'];
	$placas = $_POST['placas'];
	$operador = $_POST['operador'];
	$tipo = $_POST['tipo'];
	$tipo2 = $_POST['tipo2'];
	$coordinador = $_POST['coordinador'];
	$controller->AltaUnidadesF($numero, $marca, $modelo, $placas, $operador, $tipo, $tipo2, $coordinador);
}elseif(isset($_POST['user']) && isset($_POST['contra'])){
	$controller->LoginA($_POST['user'], $_POST['contra']);
}elseif(isset($_POST['actualizausr'])){
	$controller->actualiza($_POST['mail'], $_POST['usuario1'], $_POST['contrasena1'], $_POST['email1'], $_POST['rol1'], $_POST['estatus']);
}elseif($action == 'modifica'){
	$controller->ModificaU($_GET['e']);
}elseif (isset($_POST['selectFactura'])) {
    $docf = $_POST['docf'];
    $select = $_POST['select'];
    $response=$controller->selectFactura($docf, $select);
    echo json_encode($response);
    exit();
}elseif (isset($_POST['GeneraReporteSalida'])) {
    $controller->GeneraReporteSalida();
} elseif (isset($_POST['imprimirReporte'])) {
    $vehiculo = $_POST['vehiculo'];
    $cajas = $_POST['cajas'];
    $placas = $_POST['placas'];
    $operador = $_POST['operador'];
    $observaciones = $_POST['observaciones'];
    $fecha = $_POST['fecha'];
    $controller->generaEmbarque($vehiculo, $cajas, $placas, $operador, $observaciones, $fecha);
    //$controller->imprimirReporte($vehiculo,$cajas,$placas,$operador, $observaciones, $fecha);
} elseif (isset($_POST['reporteEmbarque'])) {
    $idr = $_POST['idr'];
    $controller->reporteEmbarque($idr);
} elseif (isset($_POST['cancelaEmbarque'])) {
    $idr = $_POST['idr'];
    $controller->cancelaEmbarque($idr);
} elseif (isset($_POST['cambiarReporte'])) {
    $vehiculo = $_POST['vehiculo'];
    $cajas = $_POST['cajas'];
    $placas = $_POST['placas'];
    $operador = $_POST['operador'];
    $observaciones = $_POST['observaciones'];
    $fecha = $_POST['fecha'];
    $idr = $_POST['idr'];
    $controller->cambiarReporte($vehiculo, $cajas, $placas, $operador, $observaciones, $fecha, $idr);
} elseif (isset($_POST['reimprimirReporte'])) {
    $idr = $_POST['idr'];
    $controller->reimprimirReporte($idr);
} elseif (isset($_POST['cambiarFecha'])) {
    $docf = $_POST['docf'];
    $nuevaFecha = $_POST['nuevaFecha'];
    $cliente = $_POST['cliente'];
    $controller->cambiarFecha($docf, $nuevaFecha, $cliente);
} elseif (isset($_POST['cerrarFecha'])) {
    $docf = $_POST['docf'];
    $controller->cerrarFecha($docf);
} elseif (isset($_POST['guardaObsPar'])) {
    $datos = $_POST['datos'];
    $response=$controller->guardaObsPar($datos);
    echo json_encode($response);
    exit();
} elseif (isset($_POST['liberarRecepcion'])) {
    $docr = $_POST['docr'];
    $controller->liberarRecepcion($docr);
} elseif (isset($_POST['guardaCaja'])) {
    $idr = $_POST['idr'];
    $docf = $_POST['docf'];
    $cajas = $_POST['cajasxp'];
    $response=$controller->guardaCaja($idr, $docf, $cajas);
    echo json_encode($response);
    exit();
}elseif (isset($_POST['costeoRecepcion'])) {
    $docr = $_POST['docr'];
    $tipo = $_POST['tipo'];
    $controller->costeoRecepcion($docr, $tipo);
}elseif(isset($_POST['autorizaDoc'])){
    $doc = $_POST['autorizaDoc'];
    $response = $controller->autorizaDoc($doc, $_POST['tipo'], $_POST['motivo']);
    echo json_encode($response);
    exit();
}elseif (isset($_POST['calcularCosto'])) {
    
    $controller->calcularCosto();
}elseif(isset($_POST['revisaRemision'])){
    $doc=$_POST['doc'];
    $response=$controller->revisaRemision($doc);
    echo json_encode($response);
    exit();
}elseif (isset($_POST['asignaMaterial'])) {
    $doc = $_POST['doc'];
    $partida = $_POST['partida'];
    $cant = $_POST['cant'];
    $partidas =$_POST['partidas'];
    $response=$controller->asignaMaterial($doc, $cant, $partida,$partidas);
    echo json_encode($response);
    exit();
}elseif(isset($_POST['guardaObs'])){
    $doc=$_POST['doc'];
    $obs=$_POST['guardaObs'];
    $response=$controller->guardaObs($doc, $obs);
    echo json_encode($response);
    exit();
}elseif(isset($_POST['setFP'])){
    $idf = $_POST['setFP'];
    $response = $controller->setFP($idf);
    echo json_encode($response);
    exit();
}elseif(isset($_POST['gastoAduana'])){
    $docc=$_POST['gastoAduana'];
    $part=$_POST['part'];
    $monto=$_POST['monto'];
    $saldo=$_POST['saldo'];
    $response = $controller->gastoAduana($docc, $part, $monto, $saldo);
    echo json_encode($response);
    exit();
}elseif(isset($_POST['FORM_ACTION_FACTURAS_UPLOAD'])){
    $tipo = $_POST['tipo'];
    $files2upload = $_POST['files2upload'];
    $controller->facturacionCargaXML($files2upload, $tipo);
}elseif(isset($_POST['elimimarGasto'])){
    $idg=$_POST['idg'];
    $doco=$_POST['doco'];
    $controller->eliminarGasto($idg,$doco);
}elseif (isset($_POST['seleccionar'])) {
    $prod=$_POST['prod'];
    $cant=$_POST['cant'];
    $tipo=$_POST['seleccionar'];
    $response = $controller->seleccionar($prod, $cant, $tipo);
    echo json_encode($response);
    exit();
}elseif (isset($_POST['enviaCorreoVentas'])) {
    $clie=$_POST['cliente'];
    $correo=$_POST['correo'];
    $controller->enviaCorreoVentas($clie, $correo);
    exit();
}elseif (isset($_POST['recostearInt'])) {
    $doco = $_POST['recostearInt'];
    $response=$controller->recostearInt($doco);
    echo json_encode($response);
    exit();
}      
else{switch ($_GET['action']){
	case 'login':
		$controller->Login();
		break;
    case 'salir':
        $controller->salir();
        header('Location: index.php?action=login');
        break;
	case 'CambiarSenia':
		$controller->CambiarSenia();
		break;
	case 'madmin':
		$controller->MenuAdmin();
		break;
	case 'costeo':
		$controller->Costoe();
	case 'verFacturas':
        $controller->verFacturas();
        break;
    case 'reporteEmbarque':
        $idr = $_GET['idr'];
        $controller->reporteEmbarque($idr);
        break;
    case 'verFacturasFecha':
        $controller->verFacturasFecha();
        break;
    case 'verCambiosFechas':
        $controller->verCambiosFechas();
        break;
    case 'verRecepProcesadas':
        $controller->verRecepProcesadas();
        break;
    case 'verReportes':
        $controller->verReportes();
        break;
    case 'reporteEmbarque':
            $idr = $_GET['idr'];
            $controller->reporteEmbarque($idr);
            break;
    case 'verCompras':
        $controller->verCompras();
        break;
    case 'verComprasRecibidas':
        $controller->verComprasRecibidas();
        break;
    case 'verPedidos':
        $tipo = (isset($_GET['tipo']))? $_GET['tipo']:'';
        $controller->verPedidos($tipo);
        break;
    case 'verObservaciones':
        $obs=$_GET['obs'];
        $doc = $_GET['doc'];
        $controller->verObservaciones($obs, $doc);
        break;
    case 'verArchivos':
        $doc=$_GET['doc'];
        $tipo = $_GET['tipo'];
        $controller->verArchivos($doc, $tipo);
    break;
    case 'preparaRemision':
        $doc=$_GET['doc'];
        $tipo=$_GET['tipo'];
        $controller->prepararRemision($doc, $tipo);
        break;
    case 'imprimeRemision':
        $controller->imprimeRemision($_GET['doc'],$_GET['tipo']);
        break;
    case 'facturaUploadFile':
        $tipo = $_GET['tipo'];
        $controller->facturacionSeleccionaCargaXML($tipo);
        break;
    case 'verXMLSP':
        $controller->verXMLSP();
        break;
    case 'verGastos':
        $doco=$_GET['doco'];
        $mensaje = (isset($_GET['mensaje']))? $_GET['mensaje']:'';
        $controller->verGastos($doco, $mensaje);
        break;
    case 'costeoRecepcion':
        $docr =$_GET['docr'];
        $tipo =$_GET['tipo'];
        $controller->costeoRecepcion($docr, $tipo);
        break;
    case 'finalizaCosteo':
        $docr =$_GET['docr'];
        $controller->finalizaCosteo($docr);
        break;
    case 'verCatalogoProductos':
        $controller->verCatalogoProductos();
        break;
    case 'sincCxP':
        $controller->sincCxP();
        break;
    case 'sincPagos':
        $controller->sincPagos();
        break;
	default:
		header('Location: index.php?action=login');
	break;
	}

}
?>