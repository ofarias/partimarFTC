<?php
//session_start();
require_once('app/model/pegaso.model.php');
require_once('app/fpdf/fpdf.php');
require_once('app/views/unit/commonts/numbertoletter.php');
require_once 'app/model/ventas.model.php';
require_once('app/model/tienda.model.php');

class pegaso_controller{
	/*Metodo que envía a login*/
	var $contexto = "http://SERVIDOR:8081/pegasoFTC/app/";
	private function load_page($page){
		return file_get_contents($page);
	}
   /* METODO QUE ESCRIBE EL CODIGO PARA QUE SEA VISTO POR EL USUARIO
		INPUT
		$html | codigo html
		OUTPUT
		HTML | codigo html		
	*/
   private function view_page($html){
		echo $html;
	}
   
   private function replace_content($in='/\#CONTENIDO\#/ms', $out,$pagina){
		 return preg_replace($in, $out, $pagina);	 	
	}

	function Login(){

			$pagina = $this->load_templateL('Login');
			$html = $this->load_page('app/views/modules/m.login.php');
			$pagina = $this->replace_content('/\#CONTENIDO\#/ms', $html, $pagina);
			$this->view_page($pagina);
	}

    function salir(){
        $data= new pegaso;
        $salir=$data->salir();
        return;
    }

	/*Obtiene y carga el template*/
	function load_template($title='Sin Titulo'){
		$pagina = $this->load_page('app/views/master.php');
		$header = $this->load_page('app/views/sections/s.header.php');
		$pagina = $this->replace_content('/\#HEADER\#/ms' ,$header , $pagina);
		$pagina = $this->replace_content('/\#TITLE\#/ms' ,$title , $pagina);		
		return $pagina;
	}
	
	/*Header para login*/
	function load_templateL($title='Sin Titulo'){
		$pagina = $this->load_page('app/views/master.php');
		$header = $this->load_page('app/views/sections/header.php');
		$pagina = $this->replace_content('/\#HEADER\#/ms' ,$header , $pagina);
		$pagina = $this->replace_content('/\#TITLE\#/ms' ,$title , $pagina);		
		return $pagina;
	}

    function load_template_popup($title='Ferretera Pegaso'){
        $pagina = $this->load_page('app/views/master.php');
        $header = $this->load_page('app/views/sections/s.header2.php');
        $pagina = $this->replace_content('/\#HEADER\#/ms' ,$header , $pagina);
        $pagina = $this->replace_content('/\#TITLE\#/ms' ,$title , $pagina);        
        return $pagina;
    }

    function CerrarVentana(){
        
        if (isset($_SESSION['user'])){
            $data = new pegaso;
            $pagina=$this->load_template('Pedidos');
            $html = $this->load_page('app/views/pages/p.cerrarventana.php');
            include 'app/views/pages/p.cerrarventana.php';
            $this->view_page($pagina);
        }else{
                $e = "Favor de iniciar Sesión";
                header('Location: index.php?action=login&e='.urlencode($e)); exit;
        }
    }

	function Autocomp(){
		$arr = array('prueba1', 'trata2', 'intento3', 'prueba4', 'prueba5');
		echo json_encode($arr);
		exit;
	}
	
	function LoginA($user, $pass){
		$data = new pegaso;
			$rs = $data->AccesoLogin($user, $pass);
				if(isset($rs) > 0){					
					$r = $data->CompruebaRol($user);
					switch ($r->USER_ROL){
						case 'administrador':
						$this->MenuAdmin();
						break;
						case 'Almacen':
						$this->MenuAlmacen();
						break;
						case 'Compras':
						$this->MenuCompras();
						break;
                        case 'monitor':
                        $this->MenuMonitor();
                        break;
                        case 'tienda':
                        $this->MenuTienda();
                        break;
                        case 'xml':
                        $this->xmlMenu();
                        break;
                        case 'monitorVentas':
                        $this->MenuMonitorVentas();
                        break;
						default:
						$e = "Error en acceso 1, favor de revisar usuario y/o contraseña";
						header('Location: index.php?action=login&e='.urlencode($e)); exit;
						break;
						}

				}else{
					$e = "Error en acceso 2, favor de revisar usuario y/o contraseña";
						header('Location: index.php?action=login&e='.urlencode($e)); exit;
				}
	}

	function Inicio(){
		if(isset($_SESSION['user'])){
			$o = $_SESSION['user'];
			switch($o->USER_ROL){
				case 'administrador':
				$this->MenuAdmin();
				break;
				case 'Almacen':
				$this->MenuAlmacen();
				break;
				case 'Compras':
				$this->MenuCompras();
				break;
                case 'monitor':
                $this->MenuMonitor();
                break;
                case 'tienda':
                $this->MenuTienda();
                break;
                case 'xml':
                $this->xmlMenu();
                break;
                case 'monitorVentas':
                $this->MenuMonitorVentas();
                break;

				default:
				$e = "Error en acceso 1, favor de revisar usuario y/o contraseña";
				header('Location: index.php?action=login&e='.urlencode($e)); exit;
				break;
				}
		}
	}

	function MenuCompras(){
		if(isset($_SESSION['user']) && $_SESSION['user']->USER_ROL == 'Compras'){
			$pagina = $this->load_template('Menu Admin');			
			$html = $this->load_page('app/views/modules/m.mcompras.php');
			$pagina = $this->replace_content('/\#CONTENIDO\#/ms', $html, $pagina);
			$this-> view_page($pagina);
		}else{
			$e = "Favor de Revisar sus datos";
			header('Location: index.php?action=login&e='.urlencode($e)); exit;
		}
	} 

    function MenuMonitor(){
        if(isset($_SESSION['user']) && $_SESSION['user']->USER_ROL == 'monitor'){
            $pagina = $this->load_template('Menu Admin');           
            $html = $this->load_page('app/views/modules/m.mmonitor.php');
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $html, $pagina);
            $this-> view_page($pagina);
        }else{
            $e = "Favor de Revisar sus datos";
            header('Location: index.php?action=login&e='.urlencode($e)); exit;
        }
    }

    function MenuTienda(){
        if(isset($_SESSION['user']) && $_SESSION['user']->USER_ROL == 'tienda'){
            $pagina = $this->load_template('Menu Admin');           
            $html = $this->load_page('app/views/modules/m.mtienda.php');
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $html, $pagina);
            $this-> view_page($pagina);
        }else{
            $e = "Favor de Revisar sus datos";
            header('Location: index.php?action=login&e='.urlencode($e)); exit;
        }
    } 
	/*Carga menu de administrador*/
	function MenuAdmin(){
		if(isset($_SESSION['user']) && $_SESSION['user']->USER_ROL == 'administrador'){
			$pagina = $this->load_template('Menu Admin');			
			$html = $this->load_page('app/views/modules/m.madmin.php');
			$pagina = $this->replace_content('/\#CONTENIDO\#/ms', $html, $pagina);
			$this-> view_page($pagina);
		}else{
			$e = "Favor de Revisar sus datos";
			header('Location: index.php?action=login&e='.urlencode($e)); exit;
		}
	}	

	function MenuAlmacen(){
		if(isset($_SESSION['user']) && $_SESSION['user']->USER_ROL == 'Almacen'){
			$pagina = $this->load_template('Menu Admin');			
			$html = $this->load_page('app/views/modules/m.malmacen.php');
			$pagina = $this->replace_content('/\#CONTENIDO\#/ms', $html, $pagina);
			$this-> view_page($pagina);
		}else{
			$e = "Favor de Revisar sus datos";
			header('Location: index.php?action=login&e='.urlencode($e)); exit;
		}
	}

   function xmlMenu(){
        if(isset($_SESSION['user']) && $_SESSION['user']->USER_ROL == 'xml'){
            $data = new pegaso;
            $pagina = $this->load_template('Menu Admin');           
            $html = $this->load_page('app/views/modules/m.mxml.php');
            $usuario = $_SESSION['user']->NOMBRE;
            ob_start();
                $table = ob_get_clean();
                include 'app/views/modules/m.mxml.php';
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this-> view_page($pagina);
        }else{
            $e = "Favor de Revisar sus datos";
            header('Location: index.php?action=login&e='.urlencode($e)); exit;
        }   
    }

    function MenuMonitorVentas(){
        if(isset($_SESSION['user'])){
            $data = new ventasExternas;
            $pagina= $this->load_template('Menu Admin');
            $html =$this->load_page('app/views/modules/m.monitorVentas.php');
            $usuario = $_SESSION['user']->NOMBRE;
            ob_start();
                $table =ob_get_clean();
                include 'app/views/modules/m.monitorVentas.php';
                $pagina=$this->replace_content('/\#CONTENIDO\#/ms',$table, $pagina);
            $this->view_page($pagina);
        }else{
            $e="Favor de Revisar sus datos";
            header('Location:index.php?action=login&e='.urlencode($e));exit();
        }
    }

	function CambiarSenia(){
		if(isset($_SESSION['user'])){
			$data= new pegaso;
			$pagina = $this->load_template('Menu Admin');			
			$html = $this->load_page('app/views/pages/p.cambiarSenia.php');
			$pagina = $this->replace_content('/\#CONTENIDO\#/ms', $html, $pagina);
			ob_start();
			$this-> view_page($pagina);
		}else{
			$e = "Favor de Revisar sus datos";
			header('Location: index.php?action=login&e='.urlencode($e)); exit;
		}
	}

	function cambioSenia($nuevaSenia, $actual, $usuario){
		if(isset($_SESSION['user'])){
			$data=new pegaso;
			$pagina = $this->load_template('Menu Admin');			
			$html = $this->load_page('app/views/pages/p.cambiarSenia.php');
			ob_start();
			$cambio=$data->cambioSenia($nuevaSenia, $actual, $usuario);
			$this->CerrarVentana();
		}else{
			$e = "Favor de Revisar sus datos";
			header('Location: index.php?action=login&e='.urlencode($e)); exit;
		}	
	}

	/*nuevos menus*/
	function MenuVentas(){
		if(isset($_SESSION['user']) && $_SESSION['user']->USER_ROL == 'ventas'){
			$pagina = $this->load_template('Menu Admin');			
			$html = $this->load_page('app/views/modules/m.mventas.php');
			$pagina = $this->replace_content('/\#CONTENIDO\#/ms', $html, $pagina);
			$this-> view_page($pagina);
		}else{
			$e = "Favor de Revisar sus datos";
			header('Location: index.php?action=login&e='.urlencode($e)); exit;
		}
	}

	function MenureciboRecoleccion(){
		$gerente = $_SESSION['user']->COORDINADOR_COMP;
		if(isset($_SESSION['user']) && $_SESSION['user']->USER_ROL == 'reciboRecoleccion'){
			$pagina = $this->load_template('Menu Admin');			
			$html = $this->load_page('app/views/modules/m.mreciboRecoleccion.php');
			$pagina = $this->replace_content('/\#CONTENIDO\#/ms', $html, $pagina);
			$this-> view_page($pagina);
		}else{
			$e = "Favor de Revisar sus datos";
			header('Location: index.php?action=login&e='.urlencode($e)); exit;
		}	
	}

	function verFacturas() {
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            $pagina = $this->load_template('Pedidos');
            $html = $this->load_page('app/views/pages/p.verFacturas.php');
            ob_start();
            $facturas = $data->verFacturas();
            if (count($facturas)) {
                include 'app/views/pages/p.verFacturas.php';
                $table = ob_get_clean();
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            } else {
                $pagina = $this->replace_content('/\CONTENIDO\#/ms', $html . '<div class="alert-info"><center><h2>No hay datos para mostrar</h2><center></div>', $pagina);
            }
            $this->view_page($pagina);
        } else {
            $e = "Favor de iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

   function selectFactura($docf, $select) {
        
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            $pagina = $this->load_template('Pedidos');
            $html = $this->load_page('app/views/pages/p.verFacturas.php');
            ob_start();
            $response = $data->selectFactura($docf, $select);
            //$this->verFacturas();
            return $response; 
        } else {
            $e = "Favor de iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function GeneraReporteSalida() {

        
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            $pagina = $this->load_template('Pedidos');
            $html = $this->load_page('app/views/pages/p.generaReporte.php');
            ob_start();
            $datos = $data->datosReporteSalida();
            //$unidades=$data->traeUnidades()
            if (isset($datos)) {
                include 'app/views/pages/p.generaReporte.php';
                $table = ob_get_clean();
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            } else {
                $pagina = $this->replace_content('/\CONTENIDO\#/ms', $html . '<div class="alert-info"><center><h2>No hay datos para mostrar</h2><center></div>', $pagina);
            }
            $this->view_page($pagina);
        } else {
            $e = "Favor de iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function generaEmbarque($vehiculo, $cajas, $placas, $operador, $observaciones, $fecha) {
        
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            $embarque = $data->registraEmbarque($vehiculo, $cajas, $placas, $operador, $observaciones, $fecha);
            $redireccionar = "reporteEmbarque&idr={$embarque}";
            $pagina = $this->load_template('Pedidos');
            $html = $this->load_page('app/views/pages/p.redirectform.php');
            include 'app/views/pages/p.redirectform.php';
            $this->view_page($pagina);
        }
    }

    function imprimirReporte($vehiculo, $cajas, $placas, $operador, $observaciones, $fecha) {
        ob_start();
        $data = new Pegaso;
        $datos = $data->datosFacturas($embarque);
        $fecha = date("Y-m-d H:i:s");
        $pdf = new FPDF('L', 'mm', 'Letter');
        $pdf->AddPage();
        //$pdf->Image('app/views/images/headerCierreRuta.jpg',10,15,205,55);
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 24);
        $pdf->Write(6, 'PARTICIPACIONES MARIANO S.A. DE C.V.');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Write(6, 'REPORTE DE EMBARQUES DE MERCANCIA');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Write(6, '');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Write(6, 'Folio: ' . $embarque . '                                          Fecha de Recepcion: ' . $fecha);
        $pdf->Ln();
        $pdf->Write(6, 'Operador:' . $operador . '         Vehiculo: ' . $vehiculo . '         Placas:' . $placas);
        $pdf->Ln();
        $pdf->Ln(10);
        $pdf->Write(6, 'Observaciones: ' . $observaciones . '        Cajas:' . $cajas . '.');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(10, 6, "CAJAS", 1);
        $pdf->Cell(18, 6, "FACTURA / REMISION", 1);
        $pdf->Cell(40, 6, "CLIENTE", 1);
        $pdf->Cell(30, 6, "FECHA", 1);
        $pdf->Cell(18, 6, "IMPORTE", 1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'I', 7);
        foreach ($datos as $row) {
            $pdf->Cell(10, 6, $cajas, 1);
            $pdf->Cell(18, 6, trim($row->CVE_DOC), 1);
            $pdf->Cell(40, 6, substr($row->NOMBRE, 0, 23), 1);
            $pdf->Cell(30, 6, $row->FECHAELAB, 1);
            $pdf->Cell(18, 6, '$ ' . number_format($row->OBSERVACION, 2), 1);
        }


        $pdf->Ln(12);
        $pdf->Write(6, '_______________________                    ______________________________________     _____________________               _________________________________');
        $pdf->Ln();
        $pdf->Write(6, 'VERIFICO CARGA                                 FIRMA OPERADOR                               AUTORIZO                                        DEPTO DE COBRANZA ');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Ln();
        ob_get_clean();
        $pdf->Output('Reporte de Embarque: ' . $embarque . '.pdf', 'i');
    }

    function reimprimirReporte($idr) {
        ob_start();
        $data = new Pegaso;
        //$embarque=$data->registraEmbarque($vehiculo,$cajas,$placas,$operador, $observaciones, $fecha);
        $embarque = $idr;
        $datosEmbarque = $data->reimprimirEmbarque($idr);
        $datos = $data->datosFacturas($embarque);

        foreach ($datosEmbarque as $d) {
            $folio = $d->ID;
        }
        $fecha = date("Y-m-d H:i:s");
        $pdf = new FPDF('L', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->SetXY(10, 10);
        $pdf->Image('app/views/images/logoPartimar.png', 10, 22);
        //$pdf->SetXY(30,30);
        //$pdf->Write(0,'FOLIO: ');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 6, "PARTICIPACIONES MARIANO S.A. DE C.V.", 0, 0, 'C');
        $pdf->Ln();
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 6, 'REPORTE DE EMBARQUES DE MERCANCIA', 0, 0, 'C');
        $pdf->Cell(0, 10, 'Folio: ' . $folio, 0, 0, 'R');
        $pdf->Ln(.5);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, '', 0, 0, 'C');
        $pdf->Ln();
       
        foreach ($datosEmbarque as $data) {
            $cajas = $data->CAJAS;
            $pdf->SetFont('Arial', 'B', 12);
            //$pdf->Write(6,'Folio: '.$data->ID);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetX(120);
            $pdf->Write(6, 'Fecha de Recepcion: ' . $data->FECHA_REPORTE);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(100, 6, 'Operador: '.$data->OPERADOR, 0, 0, 'L');
            $pdf->Cell(80, 6, 'Vehiculo:  '.$data->VEHICULO, 0, 0, 'L');
            $pdf->Cell(80, 6, 'Placas: '.$data->PLACAS, 0, 0, 'L');
            $pdf->Ln();
            $pdf->Write(6, 'Estatus del Embarque: ' . $data->ESTATUS);
            $pdf->Ln();
            $pdf->Write(6, 'Observaciones: ' . $data->OBSERVACIONES . '        Cajas:' . $data->CAJAS . '.');
            $pdf->Ln();
        }

        $pdf->LN();
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(10, 6, "CAJAS", 1);
        $pdf->Cell(65, 6, "FACTURA / REMISION", 1);
        $pdf->Cell(60, 6, "CLIENTE", 1);
        $pdf->Cell(30, 6, "FECHA FACTURA", 1);
        $pdf->Cell(80, 6, "OBSERVACIONES", 1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'I', 7);

        foreach ($datos as $row) {
            if (substr($row->DOCUMENTO, 0, 1) == 'F') {
                $doc = 'Factura Electronica : ';
            } else {
                $doc = 'Remision :';
            }
            $pdf->Cell(10, 6, $row->CAJAS, 'L,T,R');
            $pdf->Cell(65, 6, $doc . trim($row->DOCUMENTO), 'L,T,R');
            $pdf->Cell(60, 6, substr($row->CLIENTE, 0, 60), 'L,T,R');
            $pdf->Cell(30, 6, $row->FECHA_ELABORACION, 'L,T,R');
            $pdf->Cell(80, 6, substr($row->OBSERVACION,0,60), 'L,T,R');
            $pdf->Ln(4);
            $pdf->Cell(10, 6, '', 'L,B,R');
            $pdf->Cell(65, 6, 'Sucursal : ('.$row->SUCURSAL.')'.$row->NOMSUCURSAL, 'L,B,R');
            $pdf->Cell(60, 6, '', 'L,B,R');
            $pdf->Cell(30, 6, '', 'L,B,R');
            $pdf->Cell(80, 6, substr($row->OBSERVACION,60,120), 'L,B,R');
            $pdf->Ln();
        }

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetXY(15, 180);
        $pdf->Write(6, '_______________________                    ______________________________________     _____________________               _________________________________');
        $pdf->Ln();
        $pdf->Write(6, '        VERIFICO CARGA                                 FIRMA OPERADOR                                                       AUTORIZO                                        DEPTO DE COBRANZA ');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Ln();
        ob_get_clean();
        $pdf->Output('Reporte de Embarque' . $embarque . '.pdf', 'D');
    }

       function verReportes() {
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            $pagina = $this->load_template('Pedidos');
            $html = $this->load_page('app/views/pages/p.verReportes.php');
            ob_start();
            $reportes = $data->verReportes();
            if (count($reportes)) {
                include 'app/views/pages/p.verReportes.php';
                $table = ob_get_clean();
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            } else {
                $pagina = $this->replace_content('/\CONTENIDO\#/ms', $html . '<div class="alert-info"><center><h2>No hay datos para mostrar</h2><center></div>', $pagina);
            }
            $this->view_page($pagina);
        } else {
            $e = "Favor de iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function reporteEmbarque($idr) {
        
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            $pagina = $this->load_template('Pedidos');
            $html = $this->load_page('app/views/pages/p.reporteEmbarque.php');
            ob_start();
            $reporte = $data->reporteEmbarque($idr);
            $facturas = $data->reporteEmbarqueFacturas($idr);
            if (count($reporte)) {
                include 'app/views/pages/p.reporteEmbarque.php';
                $table = ob_get_clean();
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            } else {
                $pagina = $this->replace_content('/\CONTENIDO\#/ms', $html . '<div class="alert-info"><center><h2>No hay datos para mostrar</h2><center></div>', $pagina);
            }
            $this->view_page($pagina);
        } else {
            $e = "Favor de iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function guardaCaja($idr, $docf, $cajas) {
        
        if ($_SESSION['user']) {
            $data = new pegaso;
            ob_start();
            $response = $data->guardaCaja($idr, $docf, $cajas);
            return $response;
        }
    }

    function cancelaEmbarque($idr) {
        
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            ob_start();
            $cancelar = $data->cancelaEmbarque($idr);
            $this->verReportes();
        } else {
            $e = "Favor de iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function cambiarReporte($vehiculo, $cajas, $placas, $operador, $observaciones, $fecha, $idr) {
        
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            ob_start();
            $cambiar = $data->cambiarReporte($vehiculo, $cajas, $placas, $operador, $observaciones, $fecha, $idr);
            $this->reporteEmbarque($idr);
        } else {
            $e = "Favor de iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

  function verCompras() {
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            $pagina = $this->load_template('Compra Venta');
            $html = $this->load_page('app/views/pages/Compras/p.ver.compras.php');
            ob_start();
            $docr = Null;
            $tipo = Null; 
            $usuario =$_SESSION['user']->NOMBRE;
            $compras = $data->verCompras($docr, $tipo);
            if (count($compras) > 0) {
                include 'app/views/pages/Compras/p.ver.compras.php';
                $table = ob_get_clean();
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            } else {
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $html . '<div class="alert-danger"><center><h2>NO SE ENCONTRARON
                     SOLICITUDES PENDIENTES DE IMPRESIÓN</h2><center></div>', $pagina);
            }
            $this->view_page($pagina);
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function costeoRecepcion($docr, $tipo){
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            $pagina = $this->load_template('Compra Venta');
            $html = $this->load_page('app/views/pages/Compras/p.ver.compra.completa.php');
            ob_start();
            $compras = $data->verCompras($docr, $tipo);
            $gastos = $data->gastosCompras($docr, $tipo);
            $totalPiezas = $data->piezas($docr, $tipo);
            $partidas = $data->verPartidasCompras($docr,$tipo);
            $proveedores = $data->proveCompras();
            $repartido =  0;
            if (isset($compras)) {
                include 'app/views/pages/Compras/p.ver.compra.completa.php';
                $table = ob_get_clean();
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            } else {
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $html . '<div class="alert-danger"><center><h2>NO SE ENCONTRARON
                	 SOLICITUDES PENDIENTES DE IMPRESIÓN</h2><center></div>', $pagina);
            }
            $this->view_page($pagina);
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function recConta($folio) {
        session_cache_limiter('private_no_expire');
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            $pagina = $this->load_template('Compra Venta');
            $html = $this->load_page('app/views/pages/Compras/p.ver.compras.php');
            ob_start();
            $recconta = $data->recConta($folio);
            $compras = $data->verCompras();
            if (count($compras) > 0) {
                include 'app/views/pages/p.ver.compras.php';
                $table = ob_get_clean();
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            } else {
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $html . '<div class="alert-danger"><center><h2>NO SE ENCONTRARON
                	 SOLICITUDES PENDIENTES DE IMPRESIÓN</h2><center></div>', $pagina);
            }
            $this->view_page($pagina);
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function verComprasRecibidas() {
        
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            $pagina = $this->load_template('Compra Venta');
            $html = $this->load_page('app/views/pages/Compras/p.ver.compras.recibidas.php');
            ob_start();
            $compras = $data->verComprasRecibidas();
            if (count($compras) > 0) {
                include 'app/views/pages/p.ver.compras.recibidas.php';
                $table = ob_get_clean();
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            } else {
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $html . '<div class="alert-danger"><center><h2>NO SE ENCONTRARON
                	 SOLICITUDES PENDIENTES DE IMPRESIÓN</h2><center></div>', $pagina);
            }
            $this->view_page($pagina);
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function verPedidos($tipo){
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            $pagina = $this->load_template('Compra Venta');
            $html = $this->load_page('app/views/pages/p.verPedidos.php');
            ob_start();
            $usuario = $_SESSION['user']->NOMBRE;
            $tipoUsuario = $_SESSION['user']->LETRA;
            echo 'Este es lel tipo de usuario'.$tipoUsuario;
            $pedidos = $data->verPedidos($tipo);
            if (count($pedidos) > 0) {
                include 'app/views/pages/p.verPedidos.php';
                $table = ob_get_clean();
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            } else {
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $html . '<div class="alert-danger"><center><h2>NO SE ENCONTRARON
                     PEDIDOS PENDIENTES</h2><center></div>', $pagina);
            }
            $this->view_page($pagina);
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }   

    function autorizaDoc($doc, $tipo, $motivo){
        if(isset($_SESSION['user'])){
            $data = new pegaso;
            $response = $data->autorizaDoc($doc, $tipo, $motivo);
            return $response;
        }
    }

    function verObservaciones($obs, $doc){
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            $pagina = $this->load_template_popup('Compra Venta');
            $html = $this->load_page('app/views/pages/p.verObservaciones.php');
            ob_start();
                $usuario = $_SESSION['user']->NOMBRE;
                $tipoUsuario = $_SESSION['user']->LETRA;
                $obs = $data->verObservaciones($obs, $doc);
                include 'app/views/pages/p.verObservaciones.php';
                $table = ob_get_clean();
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
                $this->view_page($pagina);
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }


    function guardaComprobante($target_file, $doc, $tipodoc, $target_dir, $file ){
        if(isset($_SESSION['user'])){
            $data=new pegaso;
            $response = $data->guardaComprobante($target_file, $doc, $tipodoc, $target_dir, $file);
            if($tipodoc != 'o'){
                $redireccionar='verPedidos';
                $pagina = $this->load_template('Pedidos');
                $html = $this->load_page('app/views/pages/p.redirectform.php');
                include 'app/views/pages/p.redirectform.php';
                $this->view_page($pagina);   
            }else{
                return;
            }
        }
    }

    function verArchivos($doc, $tipo){
        if (isset($_SESSION['user'])) {
            $data= new pegaso;
            $pagina=$this->load_template('Pedidos');
            $html=$this->load_page('app/views/pages/p.verArchivos.php');
            ob_start();
                $comprobantes = $data->verArchivos($doc, $tipo);
                include 'app/views/pages/p.verArchivos.php';
                $table = ob_get_clean();
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms',$table,$pagina);
                $this->view_page($pagina);
        }else{
            $e = "Favor de iniciar Sesión";
            header('Location: index.php?action=login&e='.urlencode($e));
        }       
    }

    function setFP($idf){
        if($_SESSION['user']){
            $data = new pegaso;
            $response = $data->setFP($idf);
            return $response;
        }
    }

    function guardaGasto($tipo, $costo, $cxp, $impuesto, $doc,$tipodoc, $file,$xml, $moneda, $tc, $proveedor){
        if(isset($_SESSION['user'])){
            $data=new pegaso;
            ob_start();
            $guarda = $data->guardaGasto($tipo, $costo, $cxp, $impuesto, $doc, $tipodoc, $file, $xml, $moneda, $tc, $proveedor);
            $docr = $doc;
            $tipo = $tipodoc;
            $redireccionar="costeoRecepcion&docr={$docr}&tipo={$tipo}";
            $pagina = $this->load_template('Pedidos');
            $html = $this->load_page('app/views/pages/p.redirectform.php');
            include 'app/views/pages/p.redirectform.php';
            $this->view_page($pagina); 
            //$this->costeoRecepcion($docr, $tipo);
        }
    }

    function finalizaCosteo($docr){
        if(isset($_SESSION['user'])){
            $data = new pegaso;
            ob_start();
            $exec=$data->finalizaCosteo($docr);
            $tipo = 'o';
            $redireccionar="costeoRecepcion&docr={$docr}&tipo={$tipo}";
            $pagina = $this->load_template('Pedidos');
            $html = $this->load_page('app/views/pages/p.redirectform.php');
            include 'app/views/pages/p.redirectform.php';
            $this->view_page($pagina);
        }
    }

    function revisaRemision($doc){
        if($_SESSION['user']){
            $data= new pegaso;
            ob_start();
            $response = $data->revisaRemision($doc);
            return $response;
        }
    }

    function prepararRemision($doc, $tipo){
        if($_SESSION['user']){
            $data = new pegaso;
            ob_start();
            $pagina=$this->load_template('Pedidos');
            $html=$this->load_page('app/views/pages/p.prepararRemision.php');
            $partidas = $data->prepararRemision($doc, $tipo);
            include 'app/views/pages/p.prepararRemision.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms',$table,$pagina);
            $this->view_page($pagina);
        }else{
            $e = "Favor de iniciar Sesión";
            header('Location: index.php?action=login&e='.urlencode($e));
        }
    }

    function imprimeRemision($doc, $tipo){
        ob_start();
        $data = new Pegaso;
        $hoy = date("Y-m-d H:i:s");
        $pdf = new FPDF('L', 'mm', 'Letter');
        $pdf->AddPage();
        //$pdf->Image('app/views/images/headerCierreRuta.jpg',10,15,205,55);
        $Cabecera = $data->cabeceraDoc($doc);
        $Detalle = $data->detalleDoc($doc);
        $usuario=$_SESSION['user']->NOMBRE;
        $tipo = 3;
        $pdf = new FPDF('P','mm','Letter');
        $pdf->AddPage();
        $pdf->Image('app/views/images/Logos/Partimar.png',10,10,50,15);
        $pdf->SetFont('Courier', 'B', 20);
        $pdf->SetTextColor(255,0,0);
        $pdf->SetXY(100, 10);
        $pdf->Write(10,'R E M I S I O N');
        $pdf->SetXY(100, 20);
        $pdf->Write(10,utf8_decode('No. '.trim($doc)));
        //$pdf->Ln(10);
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(50);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetXY(30, 30);
        $pdf->Write(6, ''); // Control de impresiones.
        $pdf->Ln(5);
        foreach ($Cabecera as $data){
            $folio = $doc;
            $pedido = $data->CVE_PEDI;
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Write(6,'Remision:'.$data->CVE_DOC.' -- Fecha de Prefactura'.$data->FECHA_DOC);
        $pdf->Ln(4);
        $pdf->Write(6,'Usuario Imprime: '.$usuario.'---> Fecha de Impresion:'.$hoy);
        $pdf->Ln(4);
        $pdf->Write(6,'Cliente : ('.$data->CLAVE.')'.$data->NOMBRE);
        $pdf->Ln(4);
        $pdf->Write(6,'RFC: '.$data->RFC);
        $pdf->Ln(4);
        $pdf->Write(6,'Direccion: Calle :'.$data->CALLE.', Num Ext:'.$data->NUMEXT.', Colonia: '.$data->COLONIA);
        $pdf->Ln(4);
        $pdf->Write(6,'Estado: '.$data->ESTADO.', Pais: '.$data->PAIS);
        $pdf->Ln(4);
        $pdf->Write(6,'Pedido Cliente: '.$data->CVE_PEDI);
        $pdf->Ln(8);
        }
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(6,6,"Part.",1);
        $pdf->Cell(13,6,"Art.",1);
        $pdf->Cell(13,6,"Clave SAT",1);
        $pdf->Cell(13,6,"Unidad SAT",1);
        $pdf->Cell(60,6,"Descripcion",1);
        $pdf->Cell(8,6,"Cant",1);
        $pdf->Cell(10,6,"UM",1);
        $pdf->Cell(13,6,"Surtido",1);
        $pdf->Cell(30,6,"Atendio ",1);
        $pdf->Cell(30,6,"Firma",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'I', 6);
            $descuento = 0;
            $subtotal = 0;
            $iva = 0;
            $total = 0;
            $partida= 0;
            $descTot=0;
            //var_dump($Detalle);
        foreach($Detalle as $row){
            $subtotal += ($row->PREC * $row->CANT);
            $descTot += ($row->PREC*(($row->DESC1/100)*$row->CANT));
            $iva += (($row->PREC * $row->CANT)-(($row->PREC*($row->DESC1/100))*$row->CANT))*.16;
            $total += (($row->PREC * $row->CANT)-(($row->PREC*($row->DESC1/100))*$row->CANT))*1.16;
            $desp = 0;

            $pdf->Cell(6,6,($row->NUM_PAR),'L,T,R, B');
            $pdf->Cell(13,6,($row->CVE_ART),'L,T,R, B');
            $pdf->Cell(13,6,($row->CVE_PRODSERV),'L,T,R, B');
            $pdf->Cell(13,6,($row->CVE_UNIDAD),'L,T,R, B');
            $pdf->Cell(60,6,substr(html_entity_decode($row->DESCR,ENT_QUOTES), 0,45), 'L,T,R, B');
            $pdf->Cell(8,6,number_format($row->CANT,2),'L,T,R, B');
            $pdf->Cell(10,6,$row->UNI_MED,'L,T,R, B');
            $pdf->Cell(13,6,'','L,T,R, B');
            $pdf->Cell(30,6,'','L,T,R, B');
            $pdf->Cell(30,6,'','L,T,R, B');
            $pdf->Ln();
        }

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetXY(15, 180);
        $pdf->Write(6, '_______________________                    ______________________________________     _____________________               _________________________________');
        $pdf->Ln();
        $pdf->Write(6, '        VERIFICO MERCANCIA                                 FIRMA OPERADOR                                                       AUTORIZO                                        DEPTO DE COBRANZA ');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Ln();
        ob_get_clean();
            
            $pdf->Ln(5);
            $pdf->SetTextColor(255,0,0);
            $pdf->SetXY(10, 220);
            $pdf->Write(10,'Pedido del cliente ('.$pedido.')');
            $pdf->Ln(3);
            $pdf->Write(10,'Favor de confirmar la existencia de este material');
            $pdf->Ln(3);
            $pdf->Write(10,'Atiende: '.$usuario);
            $pdf->Ln(3);
            $pdf->SetFont('Arial','',14);
            //$pdf->Write(10, '"DOCUMENTO INTENCIONALMENTE CON VALORES EN 0.00"');
            //$pdf->Ln(5);
            $pdf->Write(10,'"DOCUMENTO SIN VALIDEZ FISCAL"');
        //$pdf->Output( 'app/Orden de Compra Pegaso No.'.$folio.'.pdf','f');
        
            $pdf->Output( 'Remision No.'.$folio.'.pdf','D');  
    }

    function imprimeRemision_v2($doc, $tipo){
        ob_start();
        $data = new Pegaso;
        $hoy = date("Y-m-d H:i:s");
        $pdf = new FPDF('L', 'mm', 'Letter');
        $pdf->AddPage();
        //$pdf->Image('app/views/images/headerCierreRuta.jpg',10,15,205,55);
        $Cabecera = $data->cabeceraDoc($doc);
        $Detalle = $data->detalleDoc($doc);
        $usuario=$_SESSION['user']->NOMBRE;
        $tipo = 3;
        $pdf = new FPDF('P','mm','Letter');
        $pdf->AddPage();
        //$pdf->Image('app/views/images/headers/LogoFTC.jpg',10,10,50,15);
        $pdf->SetFont('Courier', 'B', 20);
        $pdf->SetTextColor(255,0,0);
        $pdf->SetXY(100, 10);
        $pdf->Write(10,'R E M I S I O N');
        $pdf->SetXY(100, 20);
        $pdf->Write(10,utf8_decode('No. '.trim($doc)));
        //$pdf->Ln(10);
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(50);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetXY(30, 30);
        $pdf->Write(6, ''); // Control de impresiones.
        $pdf->Ln(5);
        foreach ($Cabecera as $data){
            $folio = $doc;
            $pedido = $data->CVE_PEDI;
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Write(6,'Remision:'.$data->CVE_DOC.' -- Fecha de Prefactura'.$data->FECHA_DOC);
        $pdf->Ln(4);
        $pdf->Write(6,'Usuario Imprime: '.$usuario.'---> Fecha de Impresion:'.$hoy);
        $pdf->Ln(4);
        $pdf->Write(6,'Cliente : ('.$data->CLAVE.')'.$data->NOMBRE);
        $pdf->Ln(4);
        $pdf->Write(6,'RFC: '.$data->RFC);
        $pdf->Ln(4);
        $pdf->Write(6,'Direccion: Calle :'.$data->CALLE.', Num Ext:'.$data->NUMEXT.', Colonia: '.$data->COLONIA);
        $pdf->Ln(4);
        $pdf->Write(6,'Estado: '.$data->ESTADO.', Pais: '.$data->PAIS);
        $pdf->Ln(4);
        $pdf->Write(6,'Pedido Cliente: '.$data->CVE_PEDI);
        $pdf->Ln(8);
        }
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(6,6,"Part.",1);
        $pdf->Cell(13,6,"Art.",1);
        $pdf->Cell(13,6,"Clave SAT",1);
        $pdf->Cell(13,6,"Unidad SAT",1);
        $pdf->Cell(60,6,"Descripcion",1);
        $pdf->Cell(8,6,"Cant",1);
        $pdf->Cell(10,6,"UM",1);
        $pdf->Cell(13,6,"Precio",1);
        $pdf->Cell(15,6,"Subtotal ",1);
        $pdf->Cell(15,6,"Iva",1);
        $pdf->Cell(15,6,"Total",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'I', 6);
            $descuento = 0;
            $subtotal = 0;
            $iva = 0;
            $total = 0;
            $partida= 0;
            $descTot=0;
            //var_dump($Detalle);
        foreach($Detalle as $row){
            $subtotal += ($row->PREC * $row->CANT);
            $descTot += ($row->PREC*(($row->DESC1/100)*$row->CANT));
            $iva += (($row->PREC * $row->CANT)-(($row->PREC*($row->DESC1/100))*$row->CANT))*.16;
            $total += (($row->PREC * $row->CANT)-(($row->PREC*($row->DESC1/100))*$row->CANT))*1.16;
            $desp = 0;

            $pdf->Cell(6,6,($row->NUM_PAR),'L,T,R, B');
            $pdf->Cell(13,6,($row->CVE_ART),'L,T,R, B');
            $pdf->Cell(13,6,($row->CVE_PRODSERV),'L,T,R, B');
            $pdf->Cell(13,6,($row->CVE_UNIDAD),'L,T,R, B');
            $pdf->Cell(60,6,substr(html_entity_decode($row->DESCR,ENT_QUOTES), 0,45), 'L,T,R, B');
            $pdf->Cell(8,6,number_format($row->CANT,2),'L,T,R, B');
            $pdf->Cell(10,6,$row->UNI_MED,'L,T,R, B');
            $pdf->Cell(13,6,'$ '.number_format($row->PREC,2),'L,T,R, B');
            $pdf->Cell(15,6,'$ '.number_format(($row->PREC * $row->CANT)-(($row->PREC * ($row->DESC1/100))*$row->CANT),2),'L,T,R, B');
            $pdf->Cell(15,6,'$ '.number_format((($row->PREC * $row->CANT)-(($row->PREC*($row->DESC1/100))*$row->CANT))*.16,2),'L,T,R, B');
            $pdf->Cell(15,6,'$ '.number_format((($row->PREC * $row->CANT)-(($row->PREC*($row->DESC1/100))*$row->CANT))*1.16,2),'L,T,R, B');
            $pdf->Ln();
        }
            $pdf->Cell(6,6,"",0);
            $pdf->Cell(13,6,"",0);
            $pdf->Cell(13,6,"",0);
            $pdf->Cell(25,6,"",0);
            $pdf->Cell(8,6,"",0);
            $pdf->Cell(63,6,"",0);
            $pdf->Cell(15,6,"",0);
            $pdf->Cell(8,6,"",0);
            $pdf->Cell(15,6,"SubTotal",1);
            $pdf->Cell(15,6,'$ '.number_format($subtotal,2),1);
            $pdf->Cell(13,6,"",0);
            $pdf->Cell(20,6,"",0);
            $pdf->Ln();
           
            $pdf->Cell(6,6,"",0);
            $pdf->Cell(13,6,"",0);
            $pdf->Cell(13,6,"",0);
            $pdf->Cell(25,6,"",0);
            $pdf->Cell(8,6,"",0);
            $pdf->Cell(63,6,"",0);
            $pdf->Cell(15,6,"",0);
            $pdf->Cell(8,6,"",0);
            $pdf->Cell(15,6,"IVA",1);
            $pdf->Cell(15,6,'$ '.number_format(($subtotal-$descTot)*.16,2),1);
            $pdf->Cell(13,6,"",0);
            $pdf->Cell(20,6,"",0);
            $pdf->Ln();
            $pdf->Cell(6,6,"",0);
            $pdf->Cell(13,6,"",0);
            $pdf->Cell(13,6,"",0);
            $pdf->Cell(25,6,"",0);
            $pdf->Cell(8,6,"",0);
            $pdf->Cell(63,6,"",0);
            $pdf->Cell(15,6,"",0);
            $pdf->Cell(8,6,"",0);
            $pdf->Cell(15,6,"Total",1);
            $pdf->Cell(15,6,'$ '.number_format(($subtotal-$descTot)*1.16,2),1);
            $pdf->Cell(13,6,"",0);
            $pdf->Cell(20,6,"",0);
            $pdf->Ln(5);
            $pdf->SetTextColor(255,0,0);
            $pdf->SetXY(10, 220);
            $pdf->Write(10,'Pedido del cliente ('.$pedido.')');
            $pdf->Ln(3);
            $pdf->Write(10,'Favor de confirmar la existencia de este material');
            $pdf->Ln(3);
            $pdf->Write(10,'Atiende: '.$usuario);
            $pdf->Ln(3);
            $pdf->SetFont('Arial','',14);
            //$pdf->Write(10, '"DOCUMENTO INTENCIONALMENTE CON VALORES EN 0.00"');
            //$pdf->Ln(5);
            $pdf->Write(10,'"DOCUMENTO SIN VALIDEZ FISCAL"');
        //$pdf->Output( 'app/Orden de Compra Pegaso No.'.$folio.'.pdf','f');
        
            $pdf->Output( 'Remision No.'.$folio.'.pdf','D');  
    }

    function asignaMaterial($doc, $cant, $partida, $partidas){
        if($_SESSION['user']){
            $data = new pegaso;
            ob_start();
            $response = $data->asignaMaterial($doc, $cant,$partida, $partidas);
            return $response;
        }
    }

    function guardaObs($doc, $obs){
        if($_SESSION['user']){
            $data = new pegaso;
            ob_start();
            $response = $data->guardaObs($doc, $obs);
            return $response;
        }
    }

    function facturacionCargaXML($files2upload, $tipo){
        if (isset($_SESSION['user'])) {            
            $data = new pegaso;
            $valid_formats = array("xml", "XML");
            $max_file_size = 1024 * 1000; //1000 kb
            //$target_dir = "C:\\Temp\\uploads\\xml\\";
            if($tipo == 'F'){
                $target_dir="C:/tmp/partimar/uploads/xml/";   
            }elseif($tipo == 'C'){
                $target_dir = "C:/tmp/partimar/uploads/xml/cancelados/";  
            }elseif($tipo == 'R'){
                $target_dir = "C:/tmp/partimar/uploads/xml/recibidos/";
            }
            $count = 0;
            $respuesta = 0;
            // Loop $_FILES to exeicute all files
            foreach ($_FILES['files']['name'] as $f => $name) {     
                if ($_FILES['files']['error'][$f] == 4) {
                    continue; // Skip file if any error found
                }
                if ($_FILES['files']['error'][$f] == 0) {
                    if ($_FILES['files']['size'][$f] > $max_file_size) {
                        $message[] = "$name es demasiado grande para subirlo.";
                        continue; // Skip large files
                    } elseif (!in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats)) {
                        $message[] = "$name no es un archivo permitido.";
                        continue; // Skip invalid file formats
                    } else { // No error found! Move uploaded files 
                        $archivo = $target_dir.$name;
                        $exec = $data->seleccionarArchivoXMLCargado($archivo);
                        if($exec==null){
                            if (move_uploaded_file($_FILES["files"]["tmp_name"][$f], $target_dir . $name)){
                                $count++; // Number of successfully uploaded file
                                $respuesta += $data->insertarArchivoXMLCargado($archivo, $tipo);
                                //unlink($_FILES["files"]["tmp_name"][$f]);
                            }
                        } else {
                            echo "Archivo $archivo duplicado. No se ha logrado subir.";
                        }
                    }
                }
            }
            echo "Archivos cargados con exito: $count-$respuesta";
            $this->facturacionSeleccionaCargaXML($tipo);
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function gastoAduana($docc, $part, $monto, $saldo){
        if($_SESSION['user']){
            $data = new pegaso;
            $response =$data->gastoAduana($docc, $part, $monto, $saldo);
            return $response;
        }
    }

    function facturacionSeleccionaCargaXML($tipo){
        if (isset($_SESSION['user'])) {            
            $data = new pegaso;
            $pagina = $this->load_template('Pagos');                        
            $html = $this->load_page('app/views/pages/p.factura.upload.xml.php');            
            ob_start();            
            include 'app/views/pages/p.factura.upload.xml.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);

            $this->view_page($pagina);
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function verXMLSP(){
        if($_SESSION['user']){
            $data = new pegaso;
            $pagina = $this->load_template();
            $html=$this->load_page('app/views/pages/xml/p.verXMLSP.php');
            ob_start();
            $user=$_SESSION['user']->NOMBRE;
            $uuid =false;
            $info=$data->verXMLSP($uuid);
            include 'app/views/pages/xml/p.verXMLSP.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms',$table, $pagina);
            $this->view_page($pagina);
        }
    }

   function verGastos($doco, $mensaje){
        if($_SESSION['user']){
            $data = new pegaso;
            $pagina = $this->load_template();
            $html=$this->load_page('app/views/pages/compras/p.verGastos.php');
            ob_start();
            //echo "<script>alert(algo)</script>";
            $user=$_SESSION['user']->NOMBRE;
            $info=$data->verGastos($doco);
            include 'app/views/pages/compras/p.verGastos.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms',$table, $pagina);
            $this->view_page($pagina);
        }
   }

   function eliminarGasto($idg,$doco){
        if($_SESSION['user']){
            $data = new pegaso;
            ob_start();
            $user=$_SESSION['user']->NOMBRE;
            $info=$data->eliminarGasto($idg, $doco);
            $redireccionar="verGastos&doco={$doco}&mensaje={$info}";
            $pagina = $this->load_template('Pedidos');
            $html = $this->load_page('app/views/pages/p.redirectform.php');
            include 'app/views/pages/p.redirectform.php';
            $this->view_page($pagina); 
        }
   }

   function verCatalogoProductos(){
        if($_SESSION['user']){
            $data = new ventasExternas;
            $pagina = $this->load_template();
            $html=$this->load_page('app/views/pages/ventasExternas/p.verCatalogoProductos.php');
            ob_start();
            $info=$data->verCatalogoProductos();
            include 'app/views/pages/ventasExternas/p.verCatalogoProductos.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms',$table, $pagina);
            $this->view_page($pagina);
        }
   }

    function seleccionar($prod, $cant, $tipo){
        if($_SESSION['user']){
            $data = new ventasExternas;
            $response = $data->seleccionar($prod, $cant, $tipo );
            return $response;
        }
    }

    function enviaCorreoVentas($clie,$correo){
        if($_SESSION['user']){
            $data= new ventasExternas;
            $exec=$data->correoVentas();
            $res=$data->limpiaProductos($clie, $correo);
            $usuario=$_SESSION['user']->NOMBRE;
            $_SESSION['exec']=$exec;
            $_SESSION['correo']=$correo;
            $_SESSION['cliente']=$clie;
            $_SESSION['titulo']='Solicitud de Productos'.$usuario;
            include 'app/mailer/send.correoVentas.php';
            $redireccionar="verCatalogoProductos";
            $pagina = $this->load_template('Pedidos');
            $html = $this->load_page('app/views/pages/p.redirectform.php');
            include 'app/views/pages/p.redirectform.php';
            $this->view_page($pagina);
        }
    }

    function recostearInt($doco){
        if($_SESSION['user']){
            $data = new pegaso;
            //$response=$data->recostearInt($doco);
            return $response;
        }
    }

    function sincCxP(){
        if($_SESSION['user']){
            $data= new pegaso;
            $dataTienda = new tienda;
            $info =$dataTienda->sincCxP();
            if(count($info)>0){
                $response=$data->sincCxP($info);
                if($response['status']=='ok'){
                    echo "<script>alert('".$response['mensaje']."')</script>";
                }else{
                    echo 'Enviar por correo electronico.'.$response['errores'];
                }
            }else{
                echo "<script>alert('Todos los datos estan sincronizados, favor de revisar la informacion')</script>";
            }
           $this->xmlMenu();
        }
    }


    function sincPagos(){
        if($_SESSION['user']){
            $data= new pegaso;
            $dataTienda = new tienda;
            $info = $data->traePagos();
            if(count($info) > 0){
                $response =$dataTienda->insertaPagos($info);
            }else{
                echo "<script>alert('No hay pagos pendientes de sincronizar')</script>";
            }
            $this->xmlMenu();
        }
    }

}?>

