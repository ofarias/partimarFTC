<?php
//session_start();
require_once('app/model/tienda.model.php');
require_once('app/fpdf/fpdf.php');
require_once('app/views/unit/commonts/numbertoletter.php');

class tienda_controller{
	/*Metodo que envía a login*/
	var $contexto = "http://SERVIDOR:8081/pegasoFTC/app/";
	private function load_page($page){
		return file_get_contents($page);
	}

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
				default:
				$e = "Error en acceso 1, favor de revisar usuario y/o contraseña";
				header('Location: index.php?action=login&e='.urlencode($e)); exit;
				break;
				}
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
            $totalPiezas = $data->piezas($docr, $tipo);
            $partidas = $data->verPartidasCompras($docr,$tipo);
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

    function verPedidos(){
        if (isset($_SESSION['user'])) {
            $data = new pegaso;
            $pagina = $this->load_template('Compra Venta');
            $html = $this->load_page('app/views/pages/p.verPedidos.php');
            ob_start();
            $usuario = $_SESSION['user']->NOMBRE;
            $tipoUsuario = $_SESSION['user']->LETRA;
            $pedidos = $data->verPedidos();
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

    function autorizaDoc($doc){
        if(isset($_SESSION['user'])){
            $data = new pegaso;
            $response = $data->autorizaDoc($doc);
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
                $this->verPedidos();    
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

    function guardaGasto($tipo, $costo, $cxp, $impuesto, $doc,$tipodoc, $file){
        if(isset($_SESSION['user'])){
            $data=new pegaso;
            ob_start();
            $guarda = $data->guardaGasto($tipo, $costo, $cxp, $impuesto, $doc,$tipodoc, $file);
            $docr = $doc;
            $tipo = $tipodoc;
            $this->costeoRecepcion($docr, $tipo);
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

    function verAuxSaldosCxc($fechaini, $fechafin) {
        if ($_SESSION['user']) {
            $data = new tienda;
            ob_start();
            $pagina = $this->load_template('Pedidos');
            $html = $this->load_page('app/views/pages/Tienda/p.verAuxSaldosCxc.php');
            $fi = $fechaini;
            $ff = $fechafin;
            if ($fechaini == '') {
                $es = 1;
            } else {
                $es=$data->verAuxSaldosCxc($fi, $ff);
                $saldo = $data->saldoFinal($fi, $ff);
                $totalVentas = $data->saldoVentasBrutas($fi, $ff);
                $ventasBrutas = $data->ventasBrutas($fi, $ff);
                $totalVentasNetas = $data->saldoVentasNetas($fi, $ff);
            }
           if ($es > 0) {
                include 'app/views/pages/Tienda/p.verAuxSaldosCxc.php';
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

    function ventasXproducto($fechaini, $fechafin){
        if($_SESSION['user']){
            $data = new tienda;
            ob_start();
            $pagina = $this->load_template('Pedidos');
            $html = $this->load_page('app/views/pages/Tienda/p.ventasXproducto.php');
            $fi = $fechaini;
            $ff = $fechafin;
            if ($fechaini == '') {
                $es = 1;
            } else {
                $es=$data->ventasXproducto($fi, $ff);
                //$saldo = $data->saldoFinal($fi, $ff);
                //$totalVentas = $data->saldoVentasBrutas($fi, $ff);
                //$ventasBrutas = $data->ventasBrutas($fi, $ff);
                //$totalVentasNetas = $data->saldoVentasNetas($fi, $ff);
            }
           if ($es > 0) {
                include 'app/views/pages/Tienda/p.ventasXproducto.php';
                $table = ob_get_clean();
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            } else {
                $pagina = $this->replace_content('/\CONTENIDO\#/ms', $html . '<div class="alert-info"><center><h2>No hay datos para mostrar</h2><center></div>', $pagina);
            }
            $this->view_page($pagina);
        }else {
            $e = "Favor de iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }


}?>

