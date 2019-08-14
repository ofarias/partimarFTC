<?php

require_once 'app/model/database.php';

/*Clase para hacer uso de database*/
class pegaso extends database{
	/*Comprueba datos de login*/
	function AccesoLogin($user, $pass){
		$u = $user;
			$this->query = "SELECT ID, USER_LOGIN, USER_PASS, USER_ROL, LETRA, LETRA2, LETRA3, LETRA4, LETRA5, LETRA6, NUMERO_LETRAS, NOMBRE, CC, CR, aux_comp, COORDINADOR_COMP, USER_EMAIL
						FROM PG_USERS 
						WHERE USER_LOGIN = '$u' and USER_PASS = '$pass'"; /*Contraseña va encriptada con MD5*/
		 	$log = $this->QueryObtieneDatos();
		 
			$this->query = "SELECT ID, USER_LOGIN, USER_PASS, USER_ROL, LETRA, LETRA2, LETRA3, LETRA4, LETRA5, LETRA6, NUMERO_LETRAS, NOMBRE, CC, CR, aux_comp, COORDINADOR_COMP, USER_EMAIL
						FROM PG_USERS
						WHERE USER_LOGIN = '$u' and USER_PASS = '$pass'"; /*Contraseña va encriptada con MD5*/
		 	$log2 = $this->EjecutaQuerySimple();
		 
		 	while ($tsArray = ibase_fetch_object($log2)){
		 		$data[]=$tsArray;
		 	}

			if(isset($log) > 0){
				/*Creamos variable de sesion*/
					$_SESSION['user'] = $log;
                    $logFtc=$this->registroLogin();
					//var_dump($_SESSION['user']);
					return $_SESSION['user'];				
			}else{
				return 0;
			}
	}

	function CompruebaRol($user){
		$this->query = "SELECT USER_ROL FROM PG_USERS WHERE USER_LOGIN = '$user'";/*Falta Tabla*/
		 $log = $this->QueryObtieneDatos();
			if(isset($log) > 0){
				return $log;
			}else{
				return 0;
			}		
	}
	
	function cambioSenia($nuevaSenia, $actual, $usuario){
		$this->query="SELECT IIF(USER_PASS IS NULL, 0, USER_PASS) AS PASSWORD, IIF(ID IS NULL, 0 , ID) AS ID FROM PG_USERS WHERE USER_LOGIN = '$usuario'";
		$rs=$this->EjecutaQuerySimple();
		$row=ibase_fetch_object($rs);
		$pass = $row->PASSWORD;
		$id=$row->ID;
		if( $pass == $actual){
			$this->query="UPDATE PG_USERS SET user_pass = '$nuevaSenia' where id = $id";
			$this->EjecutaQuerySimple();
		}
		return;
	}

  function verFacturas() {
        $a = "SELECT f.*, cl.nombre, cl.codigo
            	from factf06 f
            	left join clie06 cl on cl.clave = f.cve_clpv
            	where f.enviado is null and f.status!='C' and (seleccion is null or seleccion <> 2) and fechaelab >= '05.04.2018'";
        $this->query = $a;
        $result = $this->EjecutaQuerySimple();
        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        $b = "SELECT f.*, cl.nombre, cl.codigo
            	from factr06 f
            	left join clie06 cl on cl.clave = f.cve_clpv
            	where f.enviado is null and f.status!='C' and (f.seleccion is null or seleccion <> 2) and fechaelab >= '05.04.2018'";
        $this->query = $b;

        $result = $this->EjecutaQuerySimple();

        while ($tsArray = ibase_fetch_object($result)) {
            $data[] = $tsArray;
        }

        return $data;
    }

   function selectFactura($docf, $select) {

        if (substr($docf, 0, 1) == 'F') {
            if ($select == 0) {
                $this->query = "UPDATE FACTF06 SET SELECCION = 1 WHERE CVE_DOC = '$docf'";
            } elseif ($select == 1) {
                $this->query = "UPDATE FACTF06 SET SELECCION = 0 WHERE CVE_DOC = '$docf'";
            }
        } else {
            if ($select == 0) {
                $this->query = "UPDATE FACTR06 SET SELECCION = 1 WHERE CVE_DOC = '$docf'";
            } elseif ($select == 1) {
                $this->query = "UPDATE FACTR06 SET SELECCION = 0 WHERE CVE_DOC = '$docf'";
            }
        }
        $rs = $this->EjecutaQuerySimple();
                if(ibase_affected_rows() == 1){

                         return array("status"=>'ok');
                }else{
                    return array("status"=>'no');
                }
    }

    function registroLogin(){
        $usuario =$_SESSION['user']->USER_LOGIN;
        $nombre = $_SESSION['user']->NOMBRE;
        $ip= $_SERVER['REMOTE_ADDR'];
        $equipo=php_uname();
        $p=session_id();
        $pn=$_SERVER['HTTP_USER_AGENT'];
        //echo 'Nombre de la session de PHP:'.$pn;
        $this->query="INSERT INTO FTC_INICIO_LOGS (ID, USR_LOGIN, USR_NOMBRE, USR_EQUIPO, FECHA, STATUS, IP, PHP, navegador)
                            VALUES (null, '$usuario', '$nombre', '$equipo',current_timestamp, 'I','$ip', '$p', '$pn')";
        $this->grabaBD();
        return;
    }

    function get_client_ip_env() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function salir(){
        $php= session_id();
        $usuario = $_SESSION['user']->USER_LOGIN;
        $this->query="UPDATE FTC_INICIO_LOGS SET STATUS = 'O', salida = current_timestamp WHERE PHP='$php' and USR_LOGIN='$usuario' and status='I' ";
        $this->EjecutaQuerySimple();
        $_SESSION=array();
        session_unset();
        session_destroy();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        //header('Location: index.php?action=login');
        return;
    }

    function datosFacturas($embarque) {
        $this->query = "SELECT p.*,
                        (SELECT calle from infenvio06 where cve_info = f.dat_envio) as calle,
                        (SELECT calle from infenvio06 where cve_info = r.dat_envio) as calle2,
                        (SELECT ccl.CAMPLIB1 FROM factf06 fa inner join infenvio06 ie on f.dat_envio= ie.cve_info
                            inner join clie06 cl on cl.clave = ie.cve_cons 
                            inner join clie_clib06 ccl on cl.clave = ccl.cve_clie  
                            where fa.cve_doc = f.cve_doc ) as Sucursal,
                        (SELECT ccl.CAMPLIB8 FROM factf06 fa inner join infenvio06 ie on f.dat_envio= ie.cve_info
                            inner join clie06 cl on cl.clave = ie.cve_cons 
                            inner join clie_clib06 ccl on cl.clave = ccl.cve_clie  
                            where fa.cve_doc = f.cve_doc ) as NomSucursal
                        FROM par_embarques  p
                        LEFT JOIN FActF06 f on f.cve_doc = p.documento
                        left join factr06 r on r.cve_doc = p.documento
                        WHERE p.EMBARQUE = $embarque";
        $rs = $this->QueryObtieneDatosN();
        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }

        return $data;
    }

    function datosReporteSalida() {
        $this->query = "SELECT COUNT(CVE_DOC) as facturas FROM FACTF06 WHERE SELECCION = 1";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $facturas = $row->FACTURAS;

        $this->query = "SELECT COUNT(CVE_DOC) as remisiones FROM FACTR06 WHERE SELECCION = 1";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $remisiones = $row->REMISIONES;

        $docs = $facturas + $remisiones;

        if ($docs == 0) {
            return "Debe de seleccionar por lo menos un documento para envio.";
        } else {
            $this->query = "SELECT F.*, CL.NOMBRE FROM FACTF06 F
						LEFT JOIN CLIE06 CL ON CL.CLAVE = F.CVE_CLPV
						 WHERE SELECCION = 1";
            $rs = $this->QueryObtieneDatosN();

            while ($tsarray = ibase_fetch_object($rs)) {
                $data[] = $tsarray;
            }

            $this->query = "SELECT F.*, CL.NOMBRE FROM FACTR06 F
						LEFT JOIN CLIE06 CL ON CL.CLAVE = F.CVE_CLPV
						 WHERE SELECCION = 1";
            $rs = $this->QueryObtieneDatosN();

            while ($tsarray = ibase_fetch_object($rs)) {
                $data[] = $tsarray;
            }

            return $data;
        }
    }

    function registraEmbarque($vehiculo, $cajas, $placas, $operador, $observaciones, $fecha) {
        $this->query = "INSERT INTO EMBARQUES VALUES(null, '1', '$operador', '$observaciones', '$vehiculo', '$placas','$fecha', current_timestamp, '$cajas', 'Transito', 0)";
        $rs = $this->EjecutaQuerySimple();
        $this->query = "SELECT MAX(ID) as folio FROM EMBARQUES";
        $res = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($res);
        $folio = $row->FOLIO;

        $this->query = "UPDATE FACTF06 SET EMBARQUE ='$folio', seleccion = 2 where seleccion = 1";
        $result = $this->EjecutaQuerySimple();

        $this->query = "UPDATE FACTR06 SET EMBARQUE ='$folio', seleccion = 2 where seleccion = 1";
        $result = $this->EjecutaQuerySimple();

        $this->query = "SELECT f.*, cl.nombre FROM FACTF06 f inner join clie06 cl on cl.clave = f.cve_clpv WHERE EMBARQUE = '$folio'";
        $res = $this->QueryObtieneDatosN();
        echo 'Obtiene los nuevos datos:';
        echo $this->query;
        while ($tsarray = ibase_fetch_object($res)) {
            $data[] = $tsarray;
        }
        foreach ($data as $datos) {
            echo 'Crea la Partida:' . '<p>';
            $documento = $datos->CVE_DOC;
            $cliente = $datos->NOMBRE;
            $fecha_elaboracion = $datos->FECHAELAB;
            $importe = $datos->IMPORTE;
            $pedido = $datos->CVE_PEDI;
            $embarque = $datos->EMBARQUE;
            $this->query = "INSERT INTO PAR_EMBARQUES VALUES (NULL, '$documento', '$cliente', '$fecha_elaboracion', $importe, '$pedido', $embarque, null, current_timestamp, null, null, 0)";
            $rs = $this->EjecutaQuerySimple();
        }

        unset($data);
        $this->query = "SELECT f.*, cl.nombre FROM FACTR06 f inner join clie06 cl on cl.clave = f.cve_clpv WHERE EMBARQUE = '$folio'";
        $res = $this->QueryObtieneDatosN();
        echo 'Obtiene los nuevos datos:';
        echo $this->query;
        while ($tsarray = ibase_fetch_object($res)) {
            $data[] = $tsarray;
        }
        foreach ($data as $datos) {
            echo 'Crea la Partida:' . '<p>';
            $documento = $datos->CVE_DOC;
            $cliente = $datos->NOMBRE;
            $fecha_elaboracion = $datos->FECHAELAB;
            $importe = $datos->IMPORTE;
            $pedido = $datos->CVE_PEDI;
            $embarque = $datos->EMBARQUE;
            $this->query = "INSERT INTO PAR_EMBARQUES VALUES (NULL, '$documento', '$cliente', '$fecha_elaboracion', $importe, '$pedido', $embarque, null, current_timestamp, null, null, 0)";
            $rs = $this->EjecutaQuerySimple();
        }
        return $folio;
    }


   function reporteEmbarque($idr) {
        $this->query = "SELECT * from EMBARQUES  where ID = $idr";
        $rs = $this->QueryObtieneDatosN();

        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }

        return @$data;
    }

    function reporteEmbarqueFacturas($idr) {
        $this->query = "SELECT p.*, iif((select fecha_ent from  factf06 where cve_doc = documento) is null,
    				(select fecha_ent from factr06 where trim(cve_doc) = trim(documento)), (select fecha_ent from  factf06 where cve_doc = documento)) as fechaent
                    FROM PAR_EMBARQUES  p
                    WHERE EMBARQUE = $idr";
        $rs = $this->QueryObtieneDatosN();
        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }

        return @$data;
    }


        function verReportes() {
        $this->query = "SELECT * FROM EMBARQUES order by id desc";
        $rs = $this->QueryObtieneDatosN();

        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }
        return @$data;
    }

    function guardaCaja($idr, $docf, $cajas) {
        $this->query = "UPDATE PAR_EMBARQUES SET CAJAS = $cajas where Embarque = $idr and documento = '$docf'";
        $rs = $this->EjecutaQuerySimple();
        return $response=array('status'=>'ok');
    }

    function cancelaEmbarque($idr) {
        $this->query = "UPDATE EMBARQUES SET ESTATUS = 'Cancelado' where id = $idr";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE FACTF06 SET seleccion = null, embarque = null where embarque = $idr";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE FACTR06 SET SELECCION = NULL, EMBARQUE =  NULL WHERE EMBARQUE =$idr";
        $rs = $this->EjecutaQuerySimple();

        $this->query = "UPDATE PAR_EMBARQUES SET fecha_cancelacion = current_timestamp where embarque = $idr";
        $rs = $this->EjecutaQuerySimple();

        return;
    }

    function cambiarReporte($vehiculo, $cajas, $placas, $operador, $observaciones, $fecha, $idr) {
        $this->query = "UPDATE EMBARQUES SET vehiculo = '$vehiculo', cajas =$cajas, placas='$placas', operador='$operador', observaciones='$observaciones', fecha_reporte='$fecha' where id = $idr ";
        //echo $this->query;
        //break;

        $rs = $this->EjecutaQuerySimple();

        return;
    }

    function reimprimirEmbarque($idr) {
        $this->query = "SELECT * FROM EMBARQUES WHERE ID = $idr";
        $rs = $this->QueryObtieneDatosN();

        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }

        return $data;
    }

    function validaImpresion($idr) {
        $this->query = "SELECT count(id) as ID FROM EMBARQUES WHERE IMPRESO = 1 and id = $idr";
        $rs = $this->QueryObtieneDatosN();
        $row = ibase_fetch_object($rs);
        $valida = $row->ID;
        echo $valida;
        //break;
        return $valida;
    }

    function verFacturasFecha() {
        $this->query = "SELECT f.*, cl.nombre, cl.diascred from FACTF06 f inner join clie06 cl on cl.clave = f.cve_clpv where (fecha_ok = 0 or fecha_ok is null) and f.status <> 'C'";
        $rs = $this->QueryObtieneDatosN();
        while ($tsarray = ibase_fetch_object($rs)) {
            $data[] = $tsarray;
        }
        return @$data;
    }

    function verCompras($docr, $tipo) {
        $data=array();
        if ($docr) {
            if($tipo == 'o'){
                $tabla = 'COMPO06';
            }elseif($tipo == 'r'){
                $tabla ='COMPR01';
            }
            $this->query = "SELECT c.* , p.nombre
    				  from $tabla c
    				  left join prov01 p on c.cve_clpv = p.clave
    				  where cve_doc = '$docr'";
            $rs = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        } else {
            $this->query = "SELECT c.* , p.nombre
    				  from compr01 c
    				  left join prov01 p on c.cve_clpv = p.clave
    				  where costeo is null
    				  and finalizado is null
    				  and c.status <> 'C'";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;        
            }
            $this->query="SELECT c.* , p.nombre 
                         from compo06 c 
                         left join prov01 p on c.cve_clpv = p.clave
                         where costeo is null 
                         and finalizado is null
                         and c.status <> 'C' 
                         and c.serie ='INT'";
            $rs = $this->QueryObtieneDatosN();
            while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
            }
        }
        return $data;
    }

    function gastosCompras($docr, $tipo){
            $data=array();
             $this->query="SELECT fg.tipo, sum(fg.costo*tc) as monto, 
             (select count(fa.archivo) from FTC_GASTOS_COMPRA fa where fa.documento = '$docr' and status < 8  AND fa.ARCHIVO IS NOT NULL and fa.archivo != '' and fa.tipo = fg.tipo) as Archivos, 
             (select count(fx.xml) from FTC_GASTOS_COMPRA fx WHERE fx.DOCUMENTO = '$docr' and status < 8  AND fx.XML IS NOT NULL and fx.xml != '' and fx.tipo = fg.tipo) as xmls 
             FROM FTC_GASTOS_COMPRA fg WHERE fg.DOCUMENTO = '$docr' and status < 8 group by fg.tipo";
            $rs=$this->EjecutaQuerySimple();
            while ($tsArray=ibase_fetch_object($rs)) {
                $data[]=$tsArray;
            }
            //echo $this->query;
            return $data;
    }

    function proveCompras(){
        $this->query="SELECT * FROM prov01 WHERE STATUS = 'A'";
        $rs=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($rs)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function piezas($docr, $tipo) {
        if($tipo == 'o'){
            $this->query = "SELECT SUM(CANT) AS CANTIDAD FROM PAR_COMPO06 WHERE CVE_DOC ='$docr'";
            $rs = $this->QueryObtieneDatosN();
        }elseif($tipo == 'r'){
            $this->query="SELECT SUM(CANT) AS CANTIDAD FROM PAR_COMPR01 WHERE CVE_DOC = '$docr'";
            $rs=$this->EjecutaQuerySimple();
        }
        $row = ibase_fetch_object($rs);
        $cantidad = $row->CANTIDAD;
        //echo $this->query;
        return $cantidad;
    }



    function verPartidasCompras($docr, $tipo ){
        if($tipo == 'o'){
            $tabla = 'PAR_COMPO06';
        }elseif($tipo == 'r'){
            $tabla = 'PAR_COMPR01';
        }

        $this->query = "SELECT par.* , i.descr, lt.pedimento as ped
    					from $tabla par
    					left join inve06 i on i.cve_art = par.cve_art
                        left join enlace_ltpd01 eltpd  on eltpd.E_LTPD = par.E_LTPD
                        left join ltpd01 lt on lt.reg_ltpd = eltpd.reg_ltpd
    					 where cve_doc = '$docr'";
        $rs = $this->QueryObtieneDatosN();

        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        return @$data;
    }

    function recConta($folio) {
        $usuario = $_SESSION['user']->NOMBRE;

        if (substr($folio, 0, 2) == 'ch') {
            $tabla = 'P_CHEQUES';
            $campo = 'CHEQUE';
        } elseif (substr($folio, 0, 2) == 'tr') {
            $tabla = 'P_TRANS';
            $campo = 'TRANS';
        } elseif (substr($folio, 0, 1) == 'e') {
            $tabla = 'P_EFECTIVO';
            $campo = 'EFECTIVO';
        } elseif (substr($folio, 0, 3) == 'CR-') {
            $tabla = 'SOLICITUD_PAGO';
            $campo = 'IDSOL';
            $vttf = substr($folio, 3, 2);
            $vf = substr($folio, 6, 6);
            $this->query = "UPDATE $tabla set status = 'Recibido', fecha_rec_conta = current_timestamp, usuario_recibe = '$usuario' where upper(TP_TES_FINAL) = '$vttf' and folio = $vf";
            $rs = $this->EjecutaQuerySimple();
            return $rs;
        }

        $this->query = "UPDATE $tabla SET STATUS_CONTABILIDAD = 1, fecha_rec_conta = current_timestamp, usuario_recibe = '$usuario' where $campo = '$folio'";
        $rs = $this->EjecutaQuerySimple();
        return $rs;
    }

    function verComprasRecibidas() {
        $this->query = "SELECT BENEFICIARIO, MONTO, DOCUMENTO, STATUS, FECHA_REC_CONTA, BANCO, USUARIO_RECIBE, CHEQUE AS FOLIO FROM p_cheques p where fecha >= '01.01.2017' and STATUS_CONTABILIDAD = 1";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        $this->query = "SELECT BENEFICIARIO, MONTO, DOCUMENTO, STATUS, FECHA_REC_CONTA, BANCO, USUARIO_RECIBE, TRANS AS FOLIO FROM p_TRANS p where fecha >='01.11.2016' and STATUS_CONTABILIDAD = 1";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        $this->query = "SELECT BENEFICIARIO, MONTO, DOCUMENTO, STATUS, FECHA_REC_CONTA, BANCO, USUARIO_RECIBE, EFECTIVO AS FOLIO FROM P_EFECTIVO p where fecha >='01.11.2016' and STATUS_CONTABILIDAD = 1";
        $rs = $this->QueryObtieneDatosN();
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }

        $this->query = "SELECT p.nombre as BENEFICIARIO, s.MONTO_FINAL AS MONTO, ('SOL'||'-'||s.IDSOL) AS DOCUMENTO, s.STATUS, s.FECHA_REC_CONTA, s.BANCO_FINAL, s.USUARIO_RECIBE, ('CR'||'-'||UPPER(s.TP_TES_FINAL)||'-'||s.FOLIO) AS FOLIO, s.BANCO
    				 FROM SOLICITUD_PAGO s
    				 left join prov01 p on p.clave = s.proveedor
    				 WHERE s.STATUS = 'Recibido'";
        $rs = $this->QueryObtieneDatosN();
        //echo $this->query;
        while ($tsArray = ibase_fetch_object($rs)) {
            $data[] = $tsArray;
        }
        return @$data;
    }

    function verPedidos($tipo){
        if(empty($tipo)){
            $this->query="SELECT f.*, ftc.STATUS AS STATUS_ALMACEN, ftc.*, c.nombre, 
                      datediff(day, fechaelab, current_timestamp ) as dias,
                      (select count(p.pxs) from par_factr06 p where p.cve_doc = f.cve_doc and p.pxs > 0) as pxs,
                      (select count(fl.id) from FTC_ARCHIVOS fl where fl.tipo ='R' and fl.documento = f.cve_doc and status is null) as archivos,
                      CAST((select list(USUARIO||'->'||OBS) from FTC_REM_OBS WHERE DOC = f.cve_doc) AS varchar(500)) as comentarios,
                      c.diascred, 
                      (select count(f1.id) from FTC_ARCHIVOS f1 where f1.tipo = 'R' and f1.documento = f.cve_doc and status =9) as fp,
                      (select max(f1.NOMBRE) from FTC_ARCHIVOS f1 where f1.tipo = 'R' and f1.documento = f.cve_doc and status =9) as Nombre_fp,
                      (SELECT OBSERVACIONES FROM FTC_FACTP01 WHERE DOCUMENTO = cve_doc) AS RECHAZO
                      FROM FACTR06 f
                      left join ftc_factp01 ftc on ftc.documento = f.cve_doc 
                      LEFT JOIN clie06 c on c.clave = f.cve_clpv
                      WHERE FECHAELAB >= dateadd(-7 day to current_date)
                      and serie = ''
                      and (NOT UPPER(trim(CVE_CLPV)) STARTING WITH UPPER('07')) 
                      --and (NOT UPPER(trim(CVE_CLPV)) STARTING WITH UPPER('08')) 
                      order by fechaelab asc";
                      //echo $this->query;
        }elseif($tipo == 'p'){
            $this->query="SELECT f.*, ftc.STATUS AS STATUS_ALMACEN, ftc.*, c.nombre, 
                      datediff(day, fechaelab, current_timestamp ) as dias,
                      (select count(p.pxs) from par_factr06 p where p.cve_doc = f.cve_doc and p.pxs > 0) as pxs,
                      (select count(fl.id) from FTC_ARCHIVOS fl where fl.tipo ='R' and fl.documento = f.cve_doc and status is null) as archivos,
                      CAST((select list(USUARIO||'->'||OBS) from FTC_REM_OBS WHERE DOC = f.cve_doc) AS varchar(500)) as comentarios,
                      c.diascred, 
                      (select count(f1.id) from FTC_ARCHIVOS f1 where f1.tipo = 'R' and f1.documento = f.cve_doc and status =9) as fp,
                      (select max(f1.NOMBRE) from FTC_ARCHIVOS f1 where f1.tipo = 'R' and f1.documento = f.cve_doc and status =9) as Nombre_fp,
                      ftc.status,
                      (SELECT OBSERVACIONES FROM FTC_FACTP01 WHERE DOCUMENTO = cve_doc) AS RECHAZO 
                      FROM FACTR06 f
                      left join ftc_factp01 ftc on ftc.documento = f.cve_doc 
                      LEFT JOIN clie06 c on c.clave = f.cve_clpv
                      WHERE FECHAELAB >= dateadd(-7 day to current_date)
                      and serie = ''
                      and (NOT UPPER(trim(CVE_CLPV)) STARTING WITH UPPER('07')) 
                      --and (NOT UPPER(trim(CVE_CLPV)) STARTING WITH UPPER('08'))
                      and ftc.status is null 
                      order by fechaelab asc";
                      //echo $this->query;
        }elseif($tipo == '30'){
            $this->query="SELECT f.*, ftc.STATUS AS STATUS_ALMACEN, ftc.*, c.nombre, 
                      datediff(day, fechaelab, current_timestamp ) as dias,
                      (select count(p.pxs) from par_factr06 p where p.cve_doc = f.cve_doc and p.pxs > 0) as pxs,
                      (select count(fl.id) from FTC_ARCHIVOS fl where fl.tipo ='R' and fl.documento = f.cve_doc and status is null) as archivos,
                      CAST((select list(USUARIO||'->'||OBS) from FTC_REM_OBS WHERE DOC = f.cve_doc) AS varchar(500)) as comentarios,
                      c.diascred, 
                      (select count(f1.id) from FTC_ARCHIVOS f1 where f1.tipo = 'R' and f1.documento = f.cve_doc and status =9) as fp,
                      (select max(f1.NOMBRE) from FTC_ARCHIVOS f1 where f1.tipo = 'R' and f1.documento = f.cve_doc and status =9) as Nombre_fp,
                      ftc.status,
                      (SELECT OBSERVACIONES FROM FTC_FACTP01 WHERE DOCUMENTO = cve_doc) AS RECHAZO 
                      FROM FACTR06 f
                      left join ftc_factp01 ftc on ftc.documento = f.cve_doc 
                      LEFT JOIN clie06 c on c.clave = f.cve_clpv
                      WHERE FECHAELAB >= dateadd(-30 day to current_date)
                      and serie = ''
                      and (NOT UPPER(trim(CVE_CLPV)) STARTING WITH UPPER('07')) 
                      --and (NOT UPPER(trim(CVE_CLPV)) STARTING WITH UPPER('08'))
                      order by fechaelab asc";
                      //echo $this->query;
        }

        $rs=$this->EjecutaQuerySimple();
        while ($tsarray=ibase_fetch_object($rs)){
            $data[]=$tsarray;
        }
        return ($data);
    }


    function autorizaDoc($doc, $tipo, $motivo){
        $usuario = $_SESSION['user']->NOMBRE;
        if($tipo == 'l'){
            $sta='Liberado';
        }else{
            $sta='Rechazado';
        }
        $this->query="SELECT * FROM FACTR06 WHERE CVE_DOC = '$doc'";
        $res=$this->EjecutaQuerySimple();
        $val= ibase_fetch_object($res);
        if($val->STATUS == 'C'){
            $mensaje= array("status"=>'c');   
        }else{
            $this->query="SELECT * FROM FTC_FACTP01 WHERE DOCUMENTO = '$doc'";
            $rs=$this->EjecutaQuerySimple();
            $row=ibase_fetch_object($rs); 
            if(!empty($row)){
                $docf = $row->DOCUMENTO; 
                $status = $row->STATUS;
                $usario = $row->USUARIO_LIBERA;
                $fecha = $row->FECHA_LIBERA;
                $mensaje = array("status"=>$sta,"usuario"=>$usuario,"fecha"=>$fecha);
                $this->query="UPDATE FTC_FACTP01 SET STATUS= '$sta', usuario_libera='$usuario', FECHA_LIBERA = current_timestamp, observaciones='$motivo' where DOCUMENTO = '$doc'";
                $this->queryActualiza();
            }else{
                $this->query="INSERT INTO FTC_FACTP01 (ID, DOCUMENTO, STATUS, USUARIO_LIBERA, FECHA_LIBERA, observaciones ) 
                                VALUES (NULL, '$doc', '$sta', '$usuario', current_timestamp, '$motivo')";
                $rs=$this->EjecutaQuerySimple();
            }
            $mensaje = array("status"=>'ok',"usuario"=>$usuario, "fecha"=>'', "sta"=>$sta);
        }
        return $mensaje;
    }

    function verObservaciones($obs){
        $data=array();
        $this->query="SELECT * FROM OBS_DOCF01 where cve_obs ='$obs'";
        //echo $this->query;
        $rs=$this->EjecutaQuerySimple();
        while ($tsarray=ibase_fetch_object($rs)) {
            $data[]=$tsarray;
        }
        return $data;
    }

    function guardaComprobante($target_file, $doc, $tipodoc, $target_dir, $file){
        $usuario =$_SESSION['user']->NOMBRE;
        $this->query="INSERT INTO FTC_ARCHIVOS (ID, TIPO, NOMBRE, DOCUMENTO, UBICACION, USUARIO, FECHA)
                        values (null, '$tipodoc', '$file', '$doc', '$target_dir', '$usuario', current_timestamp )";
        $this->EjecutaQuerySimple();
        return;
    }

    function verArchivos($doc, $tipo){
        $data=array();
        $this->query="SELECT * FROM FTC_ARCHIVOS where tipo = '$tipo' and documento containing('$doc')";
        $rs=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($rs)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function guardaGasto($tipo, $costo, $cxp, $impuesto, $doc, $tipodoc, $file, $xml, $moneda, $tc, $proveedor){
        $usuario =$_SESSION['user']->NOMBRE;
        //exit($usuario);
        $cxp == 'on'? $cxp=1:$cxp=0;
        $impuesto == 'on'? $impuesto=1:$impuesto=0;
        $this->query="INSERT INTO FTC_GASTOS_COMPRA (ID, TIPO, COSTO, CXP, IMPUESTO, tipo_doc, DOCUMENTO, USUARIO, FECHA, STATUS, ARCHIVO, XML, MONEDA, Tc, clave, proveedor)
                     VALUES(NULL, '$tipo', $costo, $cxp, $impuesto,'$tipodoc', '$doc', '$usuario', current_timestamp, 0, '$file','$xml', '$moneda', $tc, '$proveedor', (select nombre from prov01 where trim(clave)= trim('$proveedor') ) )";
        $this->EjecutaQuerySimple();
        if($cxp == 1){
            if($moneda == 'MNX'){
                $nummoned=1;    
            }elseif($moneda == 'USD'){
                $nummoned = 2;
            }elseif($moneda == 'EU'){
                $nummoned = 9;
            }
            $this->query="INSERT INTO PAGA_M01 (CVE_PROV, REFER, NUM_CARGO, NUM_CPTO, CVE_FOLIO, CVE_OBS, DOCTO, IMPORTE, FECHA_APLI, FECHA_VENC, AFEC_COI, NUM_MONED, TCAMBIO, IMPMON_EXT, FECHAELAB, TIPO_MOV, SIGNO, REF_SIST, STATUS)
                VALUES ((select clave from prov01 where trim(clave)=trim('$proveedor')), '$doc',
                (select iif(max(num_cargo) is null, 0, max(num_cargo)) from paga_m01 where cve_prov = (select clave from prov01 where trim(clave)=trim('$proveedor')) and refer = '$doc') + 1, 99, '$doc', 0, '$doc', ($costo*$tc), current_timestamp, current_timestamp, 'A', $nummoned, $tc, $costo, current_timestamp, 'C', 1, 'P', 'A')";
            $this->EjecutaQuerySimple();
        }
        return;
    }

    function finalizaCosteo($docr){
        $this->query="UPDATE PAR_COMPO06 SET COSTO_ADICIONAL_TOTAL = 0 + COSTO_IGI  WHERE CVE_DOC = '$docr'";
        $this->queryActualiza();
        $gastoTot= 0;
        $this->query="SELECT OC.*, (SELECT C.IMPORTE * C.TIPCAMB FROM COMPO06 C WHERE C.CVE_DOC = OC.CVE_DOC) as totaldocmnx,
                    (SELECT C.TIPCAMB FROM COMPO06 C WHERE C.CVE_DOC = OC.CVE_DOC) as tcdoc,
                    (SELECT C.NUM_MONED FROM COMPO06 C WHERE C.CVE_DOC = OC.CVE_DOC) as moneda
                     FROM PAR_COMPO06 OC WHERE OC.CVE_DOC='$docr'";
        $res=$this->EjecutaQuerySimple();
        while($tsArray = ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        foreach ($data as $key){ 
            $this->query="SELECT tipo, SUM(COSTO * TC) AS GASTO,
                          (SELECT CAMPO FROM FTC_TIPO_GASTO WHERE NOMBRE = TIPO) AS CAMPO
                          FROM FTC_GASTOS_COMPRA WHERE DOCUMENTO = '$docr' and status < 8  GROUP BY TIPO";
            $rs=$this->EjecutaQuerySimple();
                while ($tsArray2 = ibase_fetch_object($rs)) {
                    $data2[]=$tsArray2;
                }
                    foreach ($data2 as $key2) {
                        /// FORMULA
                        $gasto = (( ($key->TOT_PARTIDA+$key->TOTIMP1+ $key->TOTIMP2+$key->TOTIMP3+$key->TOTIMP4- 
                                ( ($key->CANT*$key->COST)*($key->DESCU/100))) 
                                * $key->TIP_CAM) / $key->TOTALDOCMNX ) * $key2->GASTO;
                       
                            echo 'Gasto: '.$key2->CAMPO.' Monto'.$gasto;
                            $gastoTot +=$gasto;
                        if($key2->CAMPO == 'COSTO_IGI'){
                        }else{
                            $this->query="UPDATE PAR_COMPO06 SET $key2->CAMPO = $gasto, COSTO_ADICIONAL_TOTAL = COSTO_ADICIONAL_TOTAL + $gasto
                                WHERE CVE_DOC = '$key->CVE_DOC' AND num_par = $key->NUM_PAR";     
                            $res = $this->queryActualiza();
                         }  
                    }
                    
            unset($data2);
        }
        return;
    }

    function setFP($idf){
        $this->query="UPDATE FTC_ARCHIVOS SET STATUS=9 WHERE ID = $idf and Status is null";
        $rs = $this->queryActualiza();
        if($rs == 1){
            return $mensaje=array("status"=>'ok');
        }else{
            return $mensaje=array("status"=>'no');
        }
    }

    function revisaRemision($doc){
        $usuario = $_SESSION['user']->NOMBRE;
        $this->query="SELECT * FROM FTC_FACTP01 WHERE DOCUMENTO = '$doc'";
        $res=$this->EjecutaQuerySimple();
        $row=ibase_fetch_object($res);
        
        if(empty($row)){
            $this->query="INSERT INTO FTC_FACTP01 (ID, DOCUMENTO, STATUS, USUARIO_LIBERA, FECHA_LIBERA ) 
                            VALUES (NULL, '$doc', null, null , null)";
            $rs=$this->EjecutaQuerySimple();
            $this->query="SELECT * FROM FTC_FACTP01 WHERE DOCUMENTO = '$doc'";
            $res=$this->EjecutaQuerySimple();
            $row=ibase_fetch_object($res);
            $status = 'pendiente';        
        }else{
            $status = $row->STATUS;
            if(empty($status)){
                $status = 'Liberado';
            }else{
                $status = $row->STATUS; 
            }
        }
        if($status == 'Liberado' OR $status == 'EN PROCESO' or $status == 'pendiente'){
            $this->query="SELECT * FROM FTC_PREPARA_REMISION WHERE IDREM = $row->ID";
            $rs = $this->EjecutaQuerySimple();
            $row2=ibase_fetch_object($rs);

            if(!empty($row2)){
                if($usuario == $row2->USUARIO){
                    return $mensaje=array('status'=>'ok', 'usuario'=>$row2->USUARIO,'fecha'=>$row2->FECHA_INICIA);
                }else{
                    return $mensaje=array('status'=>'bloqueada', 'usuario'=>$row2->USUARIO,'fecha'=>$row2->FECHA_INICIA);
                }
            }else{
                $this->query="INSERT INTO FTC_PREPARA_REMISION (ID, IDREM, DOC, USUARIO, FECHA_INICIA, FECHA_FINALIZA, RESULTADO, STATUS, fecha) 
                                    values(null, $row->ID, '$row->DOCUMENTO', '$usuario', current_timestamp, null,'Inicial', 0, current_timestamp)";
                $this->grabaBD();
                $this->query="SELECT MAX(ID) as id FROM FTC_PREPARA_REMISION WHERE doc = '$doc'";
                $r=$this->EjecutaQuerySimple();
                $row=ibase_fetch_object($r);
                $idrem = $row->ID;
                $this->query="EXECUTE PROCEDURE SP_PREPARA_REMISION ('$doc',$idrem,'$usuario')";
                $result=$this->EjecutaQuerySimple();
                return $mensaje=array('status'=>'ok', 'usuario'=>$usuario,'fecha'=>date('d-m-Y'));
            }
        }elseif($status == 'LISTO'){
                return $mensaje=array('status'=>'ok', 'usuario'=>'','fecha'=>'');
        }
    }

    function prepararRemision($doc,$tipo){
        $data=array();
        $usuario=$_SESSION['user']->NOMBRE;
        if($tipo == 'desbloquear'){
            $this->query="UPDATE FTC_PREPARA_REM_DETALLE SET PREPARADO = 0, FALTANTE = CANTIDAD, USUARIO = '$usuario', FECHA=current_timestamp WHERE DOC = '$doc'";
            $this->queryActualiza();
            $this->query="UPDATE FTC_PREPARA_REMISION SET USUARIO ='$usuario', FECHA_INICIA=current_timestamp, resultado='Inicial', status = status + 1 where doc = '$doc'";
            $this->queryActualiza();
        }
        $this->query="SELECT * FROM FTC_PREPARA_REM_DETALLE WHERE DOC = '$doc'";
        $r=$this->EjecutaQuerySimple();
        //echo $this->query;
        while ($tsArray=ibase_fetch_object($r)){
            $data[]=$tsArray;
        }
        return $data;
    }
    
    function asignaMaterial($doc, $cant, $partida, $partidas){
        $usuario =$_SESSION['user']->NOMBRE;
        $this->query="SELECT * FROM FTC_PREPARA_REM_DETALLE WHERE PARTIDA = $partida and doc = '$doc'";
        $res=$this->EjecutaQuerySimple();
        $row=ibase_fetch_object($res);
        $faltante = $row->FALTANTE;
        //echo $usuario.'---'.$row->USUARIO;
        if($row->USUARIO <> $usuario){
            return $mensaje=array("status"=>'out',"usuario"=>$row->USUARIO,"FECHA"=>$row->FECHA);
        }
        if($cant > $faltante){
            return $mensaje=array("status"=>'no');
        }else{
            $this->query="UPDATE FTC_PREPARA_REM_DETALLE SET PREPARADO = PREPARADO + $cant, faltante= cantidad -(PREPARADO + $cant) where partida = $partida and doc = '$doc'";
            $res=$this->queryActualiza();
            if($res == 1){
                $this->query="SELECT count(partida) as partidasc FROM FTC_PREPARA_REM_DETALLE WHERE FALTANTE = 0 AND DOC = '$doc' ";
                $res=$this->EjecutaQuerySimple();
                $row1=ibase_fetch_object($res);
                //echo $row1->PARTIDASC.'--'.$partidas;
                if($row1->PARTIDASC == $partidas){
                    $this->query="UPDATE FTC_PREPARA_REMISION SET RESULTADO = 'LISTO', FECHA_FINALIZA=current_timestamp WHERE DOC = '$doc'";
                    $this->queryActualiza();
                    $this->query="UPDATE FTC_FACTP01 SET STATUS = 'LISTO' WHERE DOCUMENTO = '$doc'";
                    $this->queryActualiza();    
                }else{
                    $this->query="UPDATE FTC_PREPARA_REMISION SET RESULTADO = 'EN PROCESO' WHERE DOC = '$doc'";
                    $this->queryActualiza();
                    $this->query="UPDATE FTC_FACTP01 SET STATUS = 'EN PROCESO' WHERE DOCUMENTO = '$doc'";
                    $this->queryActualiza();
                }
                return $mensaje=array("status"=>'ok');
            }else{
                return $mensaje=array("status"=>'no');
            }            
        }
    }

    function guardaObs($doc, $obs ){
        $usuario=$_SESSION['user']->NOMBRE;
        $this->query="INSERT INTO FTC_REM_OBS (ID, DOC, USUARIO, OBS, FECHA) 
                            VALUES(NULL, '$doc', '$usuario', '$obs', current_timestamp)";
        $this->EjecutaQuerySimple();
        return $mensaje=array("status"=>'ok');
    }

    function seleccionarArchivoXMLCargado($archivo){
        $this->query= "SELECT NOMBRE,ARCHIVO,FECHA,USUARIO,TIPO FROM XML_DATA_FILES WHERE NOMBRE = '$archivo';";
        //echo "sql: ".$this->query;
        $rs = $this->QueryObtieneDatosN();
        while($tsArray=ibase_fetch_object($rs)){
            $data[]=$tsArray;
        }
        return @$data;
    }
       
    function insertarArchivoXMLCargado($archivo, $tipo){
        $TIME = time();
        $HOY = date("Y-m-d H:i:s", $TIME);
        $usuario = $_SESSION['user']->USER_LOGIN;
        $this->query= "INSERT INTO XML_DATA_FILES (ID,NOMBRE,ARCHIVO,FECHA,USUARIO,TIPO)VALUES(NULL,'$archivo','--','$HOY','$usuario', '$tipo')";
        //echo "sql = ".$this->query;
        $respuesta = $this->grabaBD();
        $this->insertaXMLData($archivo, $tipo);
        return $respuesta;
    }


    function gastoAduana($docc, $part, $monto, $saldo){
        $user=$_SESSION['user']->NOMBRE;
        $this->query="UPDATE PAR_COMPO06 SET COSTO_IGI = $monto, saldo_aduana = $saldo where cve_doc = '$docc' and num_par = $part";
        //echo $this->query;
        $rs=$this->queryActualiza();
        if($rs==1){
            return $mensaje=array("status"=>'ok');        
        }else{
            return $mensaje=array("status"=>'error');
        }
     }

 function insertaXMLData($archivo, $tipo){
        $tipo2 = $tipo;
        $data = $this->seleccionarArchivoXMLCargado($archivo);
        if($data!=null){
            foreach ($data as $row):
                $file = $row->NOMBRE;
            endforeach;
            $myFile = fopen("$file", "r") or die("No se ha logrado abrir el archivo ($file)!");
            $myXMLData = fread($myFile, filesize($file));
            $xml = simplexml_load_string($myXMLData) or die("Error: No se ha logrado crear el objeto XML ($file)");
            $ns = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('c', $ns['cfdi']);
            $xml->registerXPathNamespace('t', $ns['tfd']);
          
            foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
                  $version = $cfdiComprobante['version'];
                  if($version == ''){
                    $version = $cfdiComprobante['Version'];
                  }

                  if($version == '3.2'){
                      $serie = $cfdiComprobante['serie'];                  
                      $folio = $cfdiComprobante['folio'];
                      $total = $cfdiComprobante['total'];
                      $subtotal = $cfdiComprobante['subTotal'];
                      $descuento = $cfdiComprobante['descuento'];
                      $tipo = $cfdiComprobante['tipoDeComprobante'];
                      $condicion = $cfdiComprobante['condicionesDePago'];
                      $metodo = $cfdiComprobante['metodoDePago'];
                      $moneda = $cfdiComprobante['Moneda'];
                      $lugar = $cfdiComprobante['LugarExpedicion'];
                      $tc = $cfdiComprobante['TipoCambio'];
                  }elseif($version == '3.3'){
                      $serie = $cfdiComprobante['Serie'];                  
                      $folio = $cfdiComprobante['Folio'];
                      $total = $cfdiComprobante['Total'];
                      $subtotal = $cfdiComprobante['SubTotal'];
                      $descuento = $cfdiComprobante['Descuento'];
                      $tipo = $cfdiComprobante['TipoDeComprobante'];
                      $condicion = $cfdiComprobante['CondicionesDePago'];
                      $metodo = $cfdiComprobante['MetodoPago'];
                      $moneda = $cfdiComprobante['Moneda'];
                      $lugar = $cfdiComprobante['LugarExpedicion'];
                      $tc = (isset($cfdiComprobante['TipoCambio']))? $cfdiComprobante['TipoCambio']:1;
                      $Certificado = $cfdiComprobante['Certificado'];
                      $Sello = $cfdiComprobante['Sello'];
                      $noCert = $cfdiComprobante['NoCertificado'];
                      $formaPago = $cfdiComprobante['FormaPago'];
                      $LugarExpedicion = $cfdiComprobante['LugarExpedicion'];
                      $MetodoPago = $cfdiComprobante['MetodoPago'];
                  }
            }
            /*foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina:Nomina//nomina:Percepciones//nomina:Percepcion') as $Percepcion){
               $nombreper = $Percepcion['TipoPercepcion'];
               //$nombre = $Receptor['nombre'];
            }*/
            if(($tipo == 'I' or $tipo == 'E' or $tipo == 'ingreso' or $tipo == 'egreso') and $serie != 'NOMINA'){
                        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor) {
                            if($version == '3.2'){
                                $rfc= $Receptor['rfc'];
                                $nombre_recep = $Receptor['nombre'];
                                $usoCFDI = '';
                            }elseif($version == '3.3'){
                                $rfc= $Receptor['Rfc'];
                                $nombre_recep=$Receptor['Nombre'];
                                $usoCFDI =$Receptor['UsoCFDI'];
                             }
                        }
                        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
                            if($version == '3.2'){
                                $rfce = $Emisor['rfc'];
                                $nombreE = '';
                                $regimen = '';  
                            }elseif($version == '3.3'){
                                $rfce = $Emisor['Rfc'];
                                $nombreE = $Emisor['Nombre'];
                                $regimen = $Emisor['RegimenFiscal'];
                            }
                        }
                                $recep_calle='';
                                $recep_noExterior='';
                                $recep_noInterior='';
                                $recep_colonia='';
                                $recep_municipio='';
                                $recep_estado='';
                                $recep_pais='';
                                $recep_cp='';
                        foreach($xml->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $Emisor_dir){
                            if($version == '3.2'){
                                $recep_calle=$Emisor_dir['calle'];
                                $recep_noExterior=$Emisor_dir['noExterior'];
                                $recep_noInterior=$Emisor_dir['noInterior'];
                                $recep_colonia=$Emisor_dir['colonia'];
                                $recep_municipio=$Emisor_dir['municipio'];
                                $recep_estado=$Emisor_dir['estado'];
                                $recep_pais=$Emisor_dir['pais'];
                                $recep_cp=$Emisor_dir['codigoPostal'];
                            }elseif($version == '3.3'){
                                $recep_calle='';
                                $recep_noExterior='';
                                $recep_noInterior='';
                                $recep_colonia='';
                                $recep_municipio='';
                                $recep_estado='';
                                $recep_pais='';
                                $recep_cp='';
                            }
                        }
                        $this->query="INSERT INTO XML_CLIENTES (IDcliente, RFC, NOMBRE, CALLE, EXTERIOR, INTERIOR, COLONIA, MUNICIPIO, ESTADO, PAIS, CP, TIPO)
                                          VALUES (NULL, '$rfc', '$nombre_recep', '$recep_calle', '$recep_noExterior', '$recep_noInterior', '$recep_colonia', '$recep_municipio', '$recep_estado', '$recep_pais', '$recep_cp', '$tipo' )";
                            $rs=$this->grabaBD();   
                            //echo $this->query;                        
                        foreach($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto){
                            if($version ==  '3.2'){
                                $unidad = $Concepto['unidad'];
                                $importe = $Concepto['importe'];
                                $cantidad = $Concepto['cantidad'];
                                $descripcion = htmlentities($Concepto['descripcion']);
                                $valor = $Concepto['valorUnitario'];
                                $claveSat='';
                                $claveUni='';
                                $partida[] =array($unidad, $importe, $cantidad, $descripcion, $valor,$claveSat, $claveUni); 
                            }elseif($version =='3.3'){
                                $unidad = $Concepto['Unidad'];
                                $importe = $Concepto['Importe'];
                                $cantidad = $Concepto['Cantidad'];
                                $descripcion = htmlentities($Concepto['Descripcion']);
                                $valor = $Concepto['ValorUnitario'];
                                $claveSat=$Concepto['ClaveProdServ'];
                                $claveUni=$Concepto['ClaveUnidad'];
                                $partida[]=array($unidad, $importe, $cantidad, $descripcion, $valor, $claveSat, $claveUni); 
                            }
                            
                        }
                        /// Manejo del total de los impuestos:
                        /// Esta linea de codigo obtiene todos los nodos llamados "Traslado"
                        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){    
                            if($version == '3.2'){
                                $tasa = $Traslado['tasa'];
                                $impNombre =$Traslado['impuesto'];
                                $importe = $Traslado['importe'];
                                $tipoFactor = '';
                                $impuestos[]=array(0 => $impNombre,1 => $tasa, 2 =>$importe,3=>$tipoFactor);
                            }elseif($version == '3.3'){
                                $tasa = $Traslado['TasaOCuota'];
                                $impNombre =$Traslado['Impuesto'];
                                $importe = $Traslado['Importe'];
                                $tipoFactor =$Traslado['TipoFactor'];
                                $impuestos[]=array(0 => $impNombre,1 => $tasa, 2 =>$importe,3=>$tipoFactor);
                            }
                            //var_dump($Traslado);      
                           //$impuesto = $Traslado['impuesto'];
                        }
                        foreach($xml->xpath('//cfdi:Conceptos//cfdi:Concepto//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $TrasladoPartida){
                            if($version ==  '3.2'){
                                $base='';
                                $parImpuesto='';
                                $parTipoFact='';
                                $parTasaCuota='';
                                $parImpImporte='';
                                $partidaImp[]=array($base, $parImpuesto, $parTipoFact, $parTasaCuota, $parImpImporte); 
                            }elseif($version =='3.3'){
                                $base = $TrasladoPartida['Base'];
                                $parImpuesto= $TrasladoPartida['Impuesto'];
                                $parTipoFact = $TrasladoPartida['TipoFactor'];
                                $parTasaCuota = $TrasladoPartida['TasaOCuota'];
                                $parImpImporte = $TrasladoPartida['Importe'];
                                $partidaImp[]=array($base, $parImpuesto, $parTipoFact, $parTasaCuota, $parImpImporte); 
                            }
                        }   
                        /*
                        echo 'Traslado del Documento: <br/>';
                        var_dump($partidaImp);
                        echo 'Trasaldos de las partidas. br/>';
                        var_dump($impuestos);
                       */
                        //HASTA AQUI TODA LA INFORMACION ES LEIDA E IMPRESA CORRECTAMENTE
                        //ESTA ULTIMA PARTE ES LA QUE GENERA ERROR, AL PARECER NO ENCUENTRA EL NODO
                        foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
                           $fecha = $tfd['FechaTimbrado']; 
                           $fecha = str_replace("T", " ", $fecha); 
                           $uuid = $tfd['UUID'];
                           $noNoCertificadoSAT = $tfd['NoCertificadoSAT'];
                           $RfcProvCertif=$tfd['RfcProvCertif'];
                           $SelloCFD=$tfd['SelloCFD'];
                           $SelloSAT=$tfd['SelloSAT'];
                           $version = $tfd['Version'];
                           $rfcprov = $tfd['RfcProvCertif'];
                        }
                        /*
                        foreach ($xml->xpath() as $key => $value) {
                            # code...
                        }
                        */
                        if(empty($descuento)){
                            $descuento = 0;
                        }
                        if($tipo2 == 'F'){
                            $this->query = "INSERT INTO XML_DATA (UUID, CLIENTE, SUBTOTAL, IMPORTE, FOLIO, SERIE, FECHA, RFCE, DESCUENTO, STATUS, TIPO, NOCERTIFICADOSAT, SELLOCFD, SELLOSAT, FECHATIMBRADO, CERTIFICADO, SELLO, versionSAT, no_cert_contr, rfcprov,formaPago, LugarExpedicion, metodoPago, moneda, TipoCambio, FILE)";
                            $this->query.= "VALUES ('$uuid', '$rfc', '$subtotal', '$total', '$folio', '$serie', '$fecha', '$rfce', $descuento, 'S', '$tipo', '$noNoCertificadoSAT', '$SelloCFD', '$SelloSAT', '$fecha','$Certificado', '$Sello', '$version', '$noCert', '$rfcprov', '$formaPago', '$LugarExpedicion', '$MetodoPago', '$moneda', $tc, '$archivo')";

                            if($rfce == 'FPE980326GH9'){
                                copy($archivo, "C:\\xampp\\htdocs\\Facturas\\facturaPegaso\\FP".$folio.".xml"); 
                            }
                            //echo "<p>query: ".$this->query."</p>";
                            //$respuesta = $this->grabaDB();
                            if($respuesta = $this->grabaBD() === false){
                            echo 'error';
                            }
                            $i=1;
                            //echo 'Valor del arreglo'.var_dump($impuestos);
                            if(!isset($impuestos)){
                                //echo $uuid.' --- '.$tipo.'  ---<br/>';
                            }else{
                                foreach ($impuestos as $key){
                                    $nombre = $key[0];
                                    $tasa = $key[1];
                                    $importe = $key[2];
                                    $tipoFactor = $key[3];
                                    $this->query = "INSERT INTO XML_IMPUESTOS values (null,'$nombre', '$tasa', $importe, $i, '$uuid', ('$serie'||'-'||'$folio'), '$tipoFactor')";
                                    $rs=$this->grabaBD();          
                                    $i=$i+1;    
                                //echo $this->query;
                                }
                            }
                            //echo 'Valor del arrego partida'.var_dump($partida);
                            $i=1;
                            foreach ($partida as $data){
                                $unidad = $data[0];
                                $importe = $data[1];
                                $cantidad = $data[2];
                                $descripcion = str_replace(array("\\", "¨", "º", "-", "~",
                                             "#", "@", "|", "!", "\"",
                                             "·", "$", "%", "&", "/",
                                             "(", ")", "?", "'", "¡",
                                             "¿", "[", "^", "<code>", "]",
                                             "+", "}", "{", "¨", "´",
                                             ">", "< ", ";", ",", ":",
                                             ".", " "),' ',$data[3]);
                                $unitario=$data[4];
                                $cvesat = $data[5];
                                $unisat = $data[6];
                                $this->query = "INSERT INTO XML_PARTIDAS (id, unidad, importe, cantidad, partida, descripcion, unitario, uuid, documento, cliente_SAE, rfc, fecha, descuento, cve_art, cve_clpv, unitario_original, CLAVE_SAT, UNIDAD_SAT) values (null, '$unidad', $importe, $cantidad, $i, '$descripcion', $unitario, '$uuid', ('$serie'||'-'||'$folio'), '', '$rfc', '$fecha', $descuento, '', '', $unitario, '$cvesat','$unisat')";
                                //echo $this->query;
                                $rs=$this->grabaBD();          
                                $i=$i+1;
                            }
                        }elseif($tipo2 == 'C'){
                            $this->query = "INSERT INTO XML_DATA_CANCELADOS (UUID, CLIENTE, SUBTOTAL, IMPORTE, FOLIO, SERIE, FECHA, RFCE, DESCUENTO, STATUS, TIPO, FILE )";
                            $this->query.= "VALUES ('$uuid', '$rfc', '$subtotal', '$total', '$folio', '$serie', '$fecha', '$rfce', $descuento, 'C', '$tipo2', '$archivo')";
                            //echo "<p>query: ".$this->query."</p>";
                            //$respuesta = $this->grabaDB();
                            $respuesta = $this->grabaBD();
                        }
                    }   
                    //return;// $respuesta;
            }

        if($tipo == 'N'){
                foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){
                    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor) {
                            if($version == '3.2'){
                                $rfc= $Receptor['rfc'];
                                $nombre_recep = $Receptor['nombre'];
                                $usoCFDI = '';
                            }elseif($version == '3.3'){
                                $rfc= $Receptor['Rfc'];
                                $nombre_recep=$Receptor['Nombre'];
                                $usoCFDI =$Receptor['UsoCFDI'];
                             }
                            $this->query="INSERT INTO XML_EMPLEADOS (IDE, RFC, NOMBRE, USOCFDI ) VALUES (NULL, '$rfc', '$nombre_recep', '$usoCFDI')";
                            $this->grabaBD();
                    }
                    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina12:Receptor') as $Nomina12Receptor) {
                            if($version == '3.2'){
                                $rfc= $Nomina12Receptor['rfc'];
                                $nombre_recep = $Nomina12Receptor['nombre'];
                                $usoCFDI = '';
                            }elseif($version == '3.3'){
                                $curp= $Nomina12Receptor['Curp'];
                                $numss=$Nomina12Receptor['NumSeguridadSocial'];
                                $FechaInicioRelLaboral =$Nomina12Receptor['FechaInicioRelLaboral'];
                                $Antiguedad=$Nomina12Receptor['Antigüedad']; 
                                $TipoContrato=$Nomina12Receptor['TipoContrato'];
                                $Sindicalizado=$Nomina12Receptor['Sindicalizado'];
                                $TipoJornada=$Nomina12Receptor['TipoJornada'];
                                $TipoRegimen=$Nomina12Receptor['TipoRegimen'];
                                $NumEmpleado= $Nomina12Receptor['NumEmpleado'];
                                $Departamento= $Nomina12Receptor['Departamento'];
                                $Puesto= $Nomina12Receptor['Puesto'];
                                $RiesgoPuesto= $Nomina12Receptor['RiesgoPuesto'];
                                $PeriodicidadPago= $Nomina12Receptor['PeriodicidadPago'];
                                $SalarioBaseCotApor= $Nomina12Receptor['SalarioBaseCotApor'];
                                $SalarioDiarioIntegrado= $Nomina12Receptor['SalarioDiarioIntegrado'];
                                $ClaveEntFed=$Nomina12Receptor['ClaveEntFed'];
                             }
                            $this->query="INSERT INTO XML_NOMINA_RECEPTOR (ID, CURP, NumSeguridadSocial, 
                                                        FechaInicioRelLaboral,
                                                        Antiguedad, 
                                                        TipoContrato, 
                                                        Sindicalizado, 
                                                        TipoJornada, 
                                                        TipoRegimen, 
                                                        NumEmpleado, 
                                                        Departamento, 
                                                        Puesto, 
                                                        RiesgoPuesto, 
                                                        PeriodicidadPago, 
                                                        SalarioBaseCotApor, 
                                                        SalarioDiarioIntegrado, 
                                                        ClaveEntFed, 
                                                        Archivo
                                                         ) 
                                    VALUES (NULL, '$curp', '$numss', '$FechaInicioRelLaboral',
                                                '$Antiguedad', 
                                                '$TipoContrato', 
                                                '$Sindicalizado', 
                                                '$TipoJornada', 
                                                '$TipoRegimen', 
                                                '$NumEmpleado', 
                                                '$Departamento', 
                                                '$Puesto', 
                                                '$RiesgoPuesto', 
                                                '$PeriodicidadPago', 
                                                '$SalarioBaseCotApor', 
                                                '$SalarioDiarioIntegrado', 
                                                '$ClaveEntFed',
                                                '$archivo'
                                                )";
                            $this->grabaBD();
                    }

                }
        }

     }


    function calcularImpuestos(){
        $data=array();
        $this->query="SELECT * FROM XML_DATA WHERE STATUS = 'S'";
        $rs=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($rs)){
            $data[]=$tsArray;
        }
        foreach ($data as $key) {
            $data2 = array();
            $this->query="SELECT IMPUESTO, TASA, SUM(MONTO) as monto, (impuesto||tasa) as campo FROM XML_IMPUESTOS I WHERE I.UUID = '$key->UUID' group by IMPUESTO, TASA";
            $res = $this->EjecutaQuerySimple();
            
            while ($tsArray2 = ibase_fetch_object($res)) {
                $data2[]=$tsArray2;             
            }

            if(count($data2) > 0){
                foreach ($data2 as $row) {
                $campo = str_replace('.', '', $row->CAMPO);
                $monto = $row->MONTO;
                if($campo == 'IVA000'){
                    $monto = "'S'";
                }
                if($campo == 'IEPS000'){
                    $monto = "'S'";
                }
                
                $this->query="UPDATE XML_DATA SET $campo = $monto where UUID = '$key->UUID'";
                $result=$this->queryActualiza();
                
                if($result < 1){
                    $this->query="INSERT INTO XML_EXCEPCION (ID, UUID, TIPO) VALUES (NULL, '$key->UUID', 'IMP')";
                    $this->grabaBD();
                }
                unset($data2);
                }
                $this->query="UPDATE XML_DATA SET STATUS = 'P' WHERE UUID = '$key->UUID'";
                $this->queryActualiza();    
            }
        }
    }

    function verXMLSP($uuid){
        $data=array();
        if(!empty($uuid)){
            $uuid = "and uuid = '".$uuid."'"; 
        }
        $this->query="SELECT FIRST 1000  x.* , (select first 1 nombre from xml_clientes where rfc = cliente) as nombre,
                        (select sum(monto) from xml_impuestos where TASA = 0.160000 and IMPUESTO = '002'  AND uuid = x.UUID) AS iva1,
                        (select sum(monto) from xml_impuestos where TASA != 0.160000 and IMPUESTO = '002'  AND uuid = x.UUID) AS retiva,
                        (select sum(monto) from xml_impuestos where TASA != 0.160000 and IMPUESTO = '001'  AND uuid = x.UUID) AS isr,
                        (select sum(monto) from xml_impuestos where TASA != 0.160000 and IMPUESTO = '001'  AND uuid = x.UUID) AS retisr
                        FROM XML_DATA x WHERE  (STATUS = 'P' OR STATUS  = 'S') $uuid";
        $res=$this->EjecutaQuerySimple();
        //echo $this->query;
        while($tsArray = ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return ($data);
    }

    function verGastos($doco){
        $data=array();
        $this->query="SELECT * FROM FTC_GASTOS_COMPRA WHERE DOCUMENTO = '$doco' and status < 9 ";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray = ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function eliminarGasto($idg, $doco){
        $mensaje = 'Se ha eliminado el gasto';
        $this->query="SELECT * FROM FTC_GASTOS_COMPRA WHERE DOCUMENTO = '$doco' and id = $idg";
        $res=$this->EjecutaQuerySimple();
        $row=ibase_fetch_object($res);
        $status = $row->STATUS;
        if($status <= 2){
            $this->query="UPDATE FTC_GASTOS_COMPRA SET STATUS = 9 WHERE ID = $idg and DOCUMENTO ='$doco'";
            $this->EjecutaQuerySimple();
            if($row->TIPO == 'Impuestos Aduanales'){
                $this->query="UPDATE PAR_COMPO06 SET COSTO_ADUANA = 0 WHERE CVE_DOC = '$doco'";
                $this->EjecutaQuerySimple();
            }
        }else{
            $mensaje='El Gasto ya ha sido aprobado o pagado, favor de revisar con Cuentas por Pagar';
        }
        return $mensaje;
    }

    function recalcularCosto(){
        $this->query="SELECT * FROM FACTF06 WHERE STATUS <> 'C' and fecha_doc >= '01.10.2017' order by cve_doc";
        $rs=$this->EjecutaQuerySimple();

        while($tsarray = ibase_fetch_object($rs)){
            $data[]=$tsarray;
        }

        foreach ($data as $key) {
            $this->query="SELECT cve_art, num_mov, tipo_elem, tipo_prod  FROM PAR_FACTF06 WHERE CVE_DOC = '$key->CVE_DOC' and tipo_prod = 'P'";
            $rs1=$this->EjecutaQuerySimple();

                while($tsarray2 = ibase_fetch_object($rs1)){
                    $data1[]=$tsarray2;
                }

                foreach ($data1 as $key2) {
                    $nummov=$key2->NUM_MOV;
                    $art = $key2->CVE_ART;

                    if($nummov > 0 ){
                        $this->query="SELECT ult_costo from inve06 where cve_art = '$art'";
                        $rs2=$this->EjecutaQuerySimple();
                        $row=ibase_fetch_object($rs2);
                        $costo = $row->ULT_COSTO;

                        $this->query="UPDATE MINVE06 SET COSTO = $costo WHERE NUM_MOV = $nummov";
                        $rs4 =$this->EjecutaQuerySimple();
                    }
                }
        }
        
    }


    function recalcularKardex(){
        $data2=array();
        //$this->query="";
        $this->query="SELECT * FROM INVE06 WHERE TIPO_ELE = 'P'";
        $rs=$this->EjecutaQuerySimple();
        while ($tsarray=ibase_fetch_object($rs)){
            $data[]=$tsarray;
       } 
       
        for ($i=1; $i < 4 ; $i++) { /// Almacenes inicimos con el 1 y hasta el 3   
           foreach ($data as $key) { // leemos los 28 productos.
               unset($data2);   /// destruimos data para que no quede almacenado en Memoria
               $existencia = 0; /// Limpiamos la existencia.  

               $clave = $key->CVE_ART;  // obtenemos la clave del articulo.
               $this->query="SELECT * FROM MINVE06 WHERE CVE_ART = '$clave' and almacen = $i order by fecha_docu asc, num_mov asc"; ///seleccionamos todos los movimientos del Articulo con el almacen 1;                
               $rs2=$this->EjecutaQuerySimple();
               while($tsArray=ibase_fetch_object($rs2)){
                    $data2[]=$tsArray;
               }
               /// si existen los movimientos por almacen se calculan y leen.
               if(isset($data2)){
                   foreach ($data2 as $key2){
                   $this->query="SELECT iif(MAX(ID) is null, 0 , max(id)) as id FROM FTC_KARDEX where articulo = '$key2->CVE_ART' and almacen = $i";
                   $rs=$this->EjecutaQuerySimple();
                   $row = ibase_fetch_object($rs);
                   $id = $row->ID; 
                   $existencia = $key2->CANT;

                   if($id != 0){
                    $this->query="SELECT EXISTENCIA FROM FTC_KARDEX WHERE ID = $id and almacen = $i";
                    $rs=$this->EjecutaQuerySimple();
                    $row2=ibase_fetch_object($rs);
                    $canto =$row2->EXISTENCIA; 
                    $existencia = $canto + ($key2->CANT * $key2->SIGNO);
                   }

                   $this->query="INSERT INTO FTC_KARDEX (ID, ARTICULO, CONCEPTO, CANTIDAD, SIGNO, EXISTENCIA, PROCESADO, STATUS, NUM_MOV, almacen ) VALUES(NULL, '$key2->CVE_ART', $key2->CVE_CPTO, $key2->CANT, $key2->SIGNO, $existencia, 0, 0, $key2->NUM_MOV, $i)";
                   $this->EjecutaQuerySimple();
               } 
            }      
        }
       }
       return;
    }


    function recostearInt($doco){
        $this->query="SELECT * FROM COMPO06 WHERE CVE_DOC ='$doco'";
        $res=$this->EjecutaQuerySimple();
        $val=ibase_fetch_object($res);
        if($val->COSTEO == 'F'){
            return $mensaje =array("status"=>"no","mensaje"=>"Ya procesada");
        }else{
            $this->query="UPDATE COMPO06 SET COSTEO ='P' WHERE CVE_DOC = '$doco'";
            $this->queryActualiza();
        }
        $this->query="SELECT * FROM COMPC01 WHERE DOC_ANT = '$doco'";
        $res=$this->EjecutaQuerySimple();
        $row=ibase_fetch_object($res);
        $compra = $row->CVE_DOC; /// Obtenemos el documento de la compra.
        $fecha_compra = $row->FECHAELAB;
        $this->query="SELECT i.* FROM INVE06 i left join par_compc01 oc on oc.cve_art = i.cve_art
             WHERE oc.CVE_DOC =  '$compra'";
        $rs=$this->EjecutaQuerySimple();
        while ($tsArray = ibase_fetch_object($rs)){
            $data[]=$tsArray;
        }
        $ln=0;

        foreach ($data as $inve){
            $ln++;
            echo ('Se trabaja el articulo: '.$inve->CVE_ART.'<br/>');
            $this->query="SELECT first 1 * FROM MINVE06 WHERE REFER = '$compra' 
                            AND CVE_ART = '$inve->CVE_ART' 
                            and costeado_ff is null 
                            ORDER BY NUM_MOV ASC ";
            $res=$this->EjecutaQuerySimple();
            while ($tsarray=ibase_fetch_object($res)){
                $data2[]=$tsarray;
            }
        
            $art=$inve->CVE_ART;
            $sigCompra=$this->sigCompra($data2,$compra, $art);
            $antCompra=$this->antCompra($data2,$compra, $art);
            $nuevo=$this->revisaNuevo($data2, $compra, $art);
            $m=$this->recosteoProducto($art);
            $data2=$nuevo;
            if(count($data2) > 0){
                foreach ($data2 as $min1 ){
                    $costoInicial=$min1->COSTO_PROM_INI;
                    $mov = $min1->NUM_MOV;
                    $art = $min1->CVE_ART;
                    if($costoInicial == 0){
                        $costoInicial = $min1->COSTO_PROM_FIN;
                        /// Si el costo inicial es 0 quiere decir que no hay productos anteiores, por lo cual no es necesario el recosteo si no mas bien actualizar el costo con los datos de la compra.
                        $this->query="SELECT FIRST 1 * FROM PAR_COMPO06 WHERE CVE_DOC = '$doco' AND COSTEO IS NULL and CVE_ART='$min1->CVE_ART'";
                        $result=$this->EjecutaQuerySimple();
                        $row =ibase_fetch_object($result);
                        $cantidad = $row->CANT;
                        $costoOriginal = $row->COST;
                        $costoAdicional = $row->COSTO_ADICIONAL_TOTAL;
                        $nuevaExistencia = $cantidad + $min1->EXISTENCIA;
                        //echo 'Obtenemos la informacion desde la partida: '.$this->query.'<p>';
                        $costoMovimiento = $costoOriginal + ($costoAdicional / $cantidad);
                        $nuevoCosto = $costoMovimiento;
                        //echo 'Cantidad: '.$cantidad.' Costo de la partida: '.$costoOriginal.'  Costo adicional: '.$costoAdicional.' Costo Total: '.$costoMovimiento.'<br/>';
                        //$nuevoCosto = (($cantidad * $costoMovimiento));
                        //echo 'Costo Nuevo: '.$nuevoCosto.'<br/>';
                        /// Se maneja antes de impuestos. 
                        // Primero actualizamos la compra.
                        $this->query="UPDATE MINVE06 SET COSTO = $costoMovimiento, COSTO_PROM_FIN = $costoMovimiento, COSTO_PROM_GRAL = $costoMovimiento, COSTO_NUEVO = $costoMovimiento,  costeado_ff = 1 WHERE NUM_MOV >= $mov and NUM_MOV < $sigCompra and cve_art = '$art' and cve_cpto = 1 ";
                        $this->EjecutaQuerySimple();
                        /// Segundo actualizamos los Movimientos que no son de compra con los costos
                        $this->query="UPDATE MINVE06 SET COSTO = $costoMovimiento, COSTO_PROM_INI = $costoMovimiento, COSTO_PROM_FIN = $costoMovimiento, COSTO_PROM_GRAL = $costoMovimiento, COSTO_NUEVO = $costoMovimiento,  costeado_ff = 1 WHERE NUM_MOV >= $mov and NUM_MOV < $sigCompra and cve_art = '$art' and cve_cpto <> 1";
                        $this->EjecutaQuerySimple();
                        //echo 'Actualizacion de Costo: '.$this->query.'<br/>';

                        $this->actualizaCostoVentas($art, $mov, $costoMovimiento, $sigCompra);

                    }else{// No es nuevo, este producto tiene compras anteriores.
                    $baseCosto = $costoInicial * $min1->EXISTENCIA;
                    echo '<br/>Producto con un costo Anterior<br/>';
                    echo ('Este es el costo actual del producto: '.$min1->CVE_ART.'--> Costo promedio Inicial:'.$costoInicial.' -->Cantidad Inicial-->'.$min1->EXISTENCIA.' Base para el calculo del costo = '.$baseCosto.'<br/>');
                        if( ($min1->TIPO_DOC  == 'r' || $min1->TIPO_DOC  == 'c')  and $min1->CVE_CPTO == 1){  ///SI DETECTA UNA ENTRADA r es de recepcion y concepto 1 es de compra...
                            $costoBase = 0;
                            // OBTENEMOS LOS VALORES DE LA CANTIDAD Y COSTO DESDE LA TABLA DE PARTIDAS DE RECEPCIONES.
                            $this->query="SELECT FIRST 1 * FROM PAR_COMPO06 WHERE CVE_DOC = '$doco' AND COSTEO IS NULL and CVE_ART='$min1->CVE_ART'";
                            $result=$this->EjecutaQuerySimple();
                            $row =ibase_fetch_object($result);
                            $cantidad = $row->CANT;
                            $costoOriginal = $row->COST;
                            $costoAdicional = $row->COSTO_ADICIONAL_TOTAL;
                            $nuevaExistencia = $cantidad + $min1->EXISTENCIA;
                            //echo 'Obtenemos la informacion desde la partida: '.$this->query.'<p>';
                            $costoMovimiento = $costoOriginal + ($costoAdicional / $cantidad);
                            echo 'Cantidad: '.$cantidad.' Costo de la partida: '.$costoOriginal.'  Costo adicional: '.$costoAdicional.' Costo Total: '.$costoMovimiento.'<br/>';
                            $nuevoCosto = (($cantidad * $costoMovimiento) + ($baseCosto))/$nuevaExistencia;
                            echo 'Costo Nuevo: '.$nuevoCosto.'<br/>';
                            /// BUSCAMOS SI HAY OTRAS RECEPCIONES MAS ANTIGUAS.
                            //$this->query="SELECT * FROM PAR_COMPO06 WHERE NUM_MOV < $min1->NUM_MOV AND CVE_ART = '$min1->CVE_ART'";
                            //$resultado=$this->EjecutaQuerySimple();
                            //$row2=ibase_fetch_object($resultado);
                            //echo 'Buscamos documento anterior : '.$this->query.'<p>';
                            //if(!empty($row2)){  //// SI HAY RECEPCIONES MAS ANTIGUA//
                            //    /// OBTENEMOS COSTO Y EXISTENCIA  DEL ULTIMO MOVIMIENTO CALCULADO.//
                            //    $this->query="SELECT  MAX(num_mov) AS umc FROM MINVE06 WHERE CVE_ART = '$min1->CVE_ART' AND COSTO_NUEVO IS NOT //NULL";
                            //    $rs=$this->EjecutaQuerySimple();
                            //    $row4 = ibase_fetch_object($rs);
                            //    echo 'Obtenemos el ultimo movimiento: '.$this->query.'<p> Ultimo Movimiento: '.$row4->UMC.'<p>';
                            //    $this->query="SELECT * FROM MINVE06 WHERE NUM_MOV = $row4->UMC";
                            //    $rs = $this->EjecutaQuerySimple();
                            //    $row6 = ibase_fetch_object($rs);
                            //    $costoBase = $row6->COSTO_NUEVO;
                            //    $existBase = $row6->EXISTENCIA_CALCULADA;
                            //    $base = $costoBase * $existBase;
                            //    $costoNuevo = (($base) + ($costo * $min1->CANT)) / ($existBase + $min1->CANT);
                            //    echo 'Obtenemos los datos del ultimo movimiento : '.$this->query.'<p>';
                            //    $this->query = "UPDATE minve06 set COSTO_NUEVO = $costoNuevo, costeado_ff = 1 where num_mov = $min1->NUM_MOV";
                            //    $this->EjecutaQuerySimple();
                            //    echo 'Actualizamos con el costo : '.$this->query.'<p>';             
                            //}else{
                                $this->query="SELECT FIRST 1 * FROM MINVE06 WHERE CVE_ART = '$min1->CVE_ART' AND (TIPO_DOC = 'c' or TIPO_DOC = 'r') and num_mov > $min1->NUM_MOV";
                                $res=$this->EjecutaQuerySimple();
                                $row2=ibase_fetch_object($res);
                                if(empty($row2)){
                                    $campo = '';
                                }else{
                                    $campo = ' AND NUM_MOV < '.$row2->NUM_MOV;
                                }
                                $this->query="UPDATE MINVE06 SET COSTO_NUEVO = $nuevoCosto, costeado_ff = 1 where cve_art ='$min1->CVE_ART' AND num_mov >= $min1->NUM_MOV $campo ";
                                $this->EjecutaQuerySimple();
                                echo 'Actualizacion de Cosro: '.$this->query.'<br/>';
                                $this->query="UPDATE MINVE06 SET COSTO = $nuevoCosto, COSTO_PROM_FIN = $nuevoCosto, COSTO_PROM_GRAL = $nuevoCosto, COSTO_NUEVO = $nuevoCosto,  costeado_ff = 1 WHERE NUM_MOV >= $mov and NUM_MOV < $sigCompra and cve_art = '$art' and cve_cpto = 1 ";
                                $this->EjecutaQuerySimple();
                                /// Segundo actualizamos los Movimientos que no son de compra con los costos
                                $this->query="UPDATE MINVE06 SET COSTO = $nuevoCosto, COSTO_PROM_INI = $nuevoCosto, COSTO_PROM_FIN = $nuevoCosto, COSTO_PROM_GRAL = $nuevoCosto, COSTO_NUEVO = $nuevoCosto,  costeado_ff = 1 WHERE NUM_MOV >= $mov and NUM_MOV < $sigCompra and cve_art = '$art' and cve_cpto <> 1";
                                $this->EjecutaQuerySimple();

                                $this->actualizaCostoVentas($art, $mov, $nuevoCosto, $sigCompra);
                            //}
                            // 500.00 + 480.00 = 980.00 / 2  = 490.00
                            /// costo base por cantidad actual + entradas por costo nuevo, entre cantidad final:
                        }else{
                            //echo 'Entra a costo simple <p>';
                            $this->query="SELECT MAX(NUM_MOV) AS ULTIMO_MOVIMIENTO from minve06 where cve_art = '$min1->CVE_ART' and num_mov < $min1->NUM_MOV";
                            $res= $this->EjecutaQuerySimple();
                            $row3 = ibase_fetch_object($res);
                            $umov = $row3->ULTIMO_MOVIMIENTO; 
                          //  echo 'No es recepcion, seleccionamos el ultimo movimiento'.$this->query;
                            $this->query="UPDATE MINVE06 SET COSTO_NUEVO = (select COSTO_NUEVO FROM MINVE06 WHERE NUM_MOV = $umov), costeado_ff  = 1, costo = (select COSTO_NUEVO FROM MINVE06 WHERE NUM_MOV = $umov)  where num_mov = $min1->NUM_MOV";
                            $ok=$this->grabaBD();
                            if(empty($ok)){
                             //   echo 'error: '.$this->query;
                            }
                            //echo 'Actualizacmos minve : '.$this->query.'<p>';
                        }
                    }
                unset($data2);
                }
            }

            $this->query="UPDATE INVE06 SET 
                       COSTO_PROM = (SELECT FIRST 1  COSTO FROM MINVE06 WHERE CVE_ART = '$art' ORDER BY NUM_MOV DESC ), 
                        ULT_COSTO = (SELECT FIRST 1 COSTO FROM MINVE06 WHERE CVE_ART = '$art' AND tipo_DOC = 'r' AND (COSTO IS NOT NULL OR COSTO >0) ORDER BY FECHA_DOCU DESC) 
                        WHERE CVE_ART = '$art'";
            $this->grabaBD();

            $this->query="INSERT INTO FTC_COSTEO_INT (ID, CVE_ART, ORDEN_COMPRA, COMPRA, COSTO_ANTERIOR, COSTO_DOC, COSTO_CALCULADO, NUEVO_COSTO, MOV_INICIAL, MOV_FINAL, FECHA, STATUS) 
                        VALUES (NULL, '$art', '$doco', '$compra', $costoInicial, $costoOriginal, $costoMovimiento, $nuevoCosto , $mov, $sigCompra, current_date, 0)";
                    $this->grabaBD();
            unset($data);
        }

        $this->query="UPDATE COMPO06 SET COSTEO ='F' WHERE CVE_DOC = '$doco' and costeo = 'P'";
            $this->queryActualiza();


       return $mensaje=array("status"=>'ok', "mensaje"=>'Se procesaron'.$ln.' productos con exito');
    }

    function revisaNuevo($data2, $compra, $inve){
        //// Revisamos para saber si es la primera vez que entra el producto al almacen.
        foreach ($data2 as $min1) {
            $art=$min1->CVE_ART;
            $mov=$min1->NUM_MOV;
            $this->query="SELECT count(num_mov) as movimientos FROM MINVE06 WHERE CVE_ART = '$art' and num_mov < $mov ";
            $res=$this->EjecutaQuerySimple();
            $rowM=ibase_fetch_object($res);
            $movs=$rowM->MOVIMIENTOS;
            if($movs == 0){
                $this->query="UPDATE MINVE06 SET COSTO_PROM_INI = 0, COSTO_PROM_fin = 0  WHERE NUM_MOV = $mov";
                $this->queryActualiza();
            }

            $this->query="SELECT first 1 * FROM MINVE06 WHERE REFER = '$compra' 
                            AND CVE_ART = '$inve' 
                            and costeado_ff is null 
                            ORDER BY NUM_MOV ASC ";
            $res=$this->EjecutaQuerySimple();
            while ($tsarray=ibase_fetch_object($res)){
                $data3[]=$tsarray;
            }
        }
        return $data3;
    }

    function actualizaCostoVentas($art, $mov, $costoMovimiento, $sigCompra){
        $this->query="SELECT count(num_mov) as COMPRAS FROM MINVE06 WHERE CVE_ART = '$art' and num_mov = $sigCompra and cve_cpto = 1";
        $res=$this->EjecutaQuerySimple();
        $row=ibase_fetch_object($res);
        $compras = $row->COMPRAS;
        if($compras){
            echo 'Ya no hay compras';
        }
        if($mov==$sigCompra or $compras==0){
            $this->query="UPDATE PAR_FACTR06 SET COST=$costoMovimiento where num_mov > $mov and cve_art ='$art'";
            $this->queryActualiza(); 
        }else{
            $this->query="UPDATE PAR_FACTR06 SET COST=$costoMovimiento where num_mov >= $mov and num_mov < $sigCompra and cve_art ='$art'";
            $this->queryActualiza();
        }
        return;    
    }

    function recosteoProducto($art){
        $this->query="SELECT cve_art,
                     CANT, EXIST_G, EXIST_G - CANT AS EXIST_ACT,
                    num_mov, refer, costo, costo_prom_ini, costo_prom_fin, costo_prom_gral
                    from minve06 where cve_art = '$art' and cve_cpto = 1  order by num_mov";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[] =$tsArray; 
        }
        $a=0;
        foreach ($data as $key){
            /// obtenermos el primer dato
            if($a==0){
                echo 'es el primer movimiento.<br/>';
                $nmi = $key->NUM_MOV;   //3878
                $cpi = $key->COSTO_PROM_INI;  ///1580.55
                $cpf = $key->COSTO_PROM_FIN;    /// 1573.09
                $a++;
            }else{
                $ni = $nmi;
                $nmi = $key->NUM_MOV;
                echo 'Es la linea'.$a;
                $cost=$key->EXIST_ACT * $cpf;
                $costn=$key->CANT * $key->COSTO;
                $contpf=($cost+$costn) / ($key->EXIST_G); 
                echo ' Formula cost: '.$cost.' coston: '.$costn.' ,contpf: '.$contpf; 
                $this->query="UPDATE MINVE06 SET COSTO_PROM_INI = $cpf, COSTO_PROM_FIN = $contpf where num_mov = $nmi and fechaelab >= '01.08.2018'";
                $this->grabaBD();
                $cpf = $contpf;
                echo ' Rango Inicial  '.$ni.' final '.$nmi.'<br/>';
                $a++;
                ////
                $this->query = "UPDATE MINVE06 SET COSTO_PROM_INI= $cpf, COSTO_PROM_FIN = $contpf where num_mov >$ni and num_mov <$nmi and cve_cpto <> 1";
                $this->grabaBD();
                $this->query = "UPDATE PAR_FACTR06 SET COST = $cpf where num_mov >$ni and num_mov <$nmi";
                $this->grabaBD();
                ////
            }
        }
        /// datos necesario = primer numero del movimiento , ultimo numero del movimiento.
    }

    function sigCompra($data2, $compra, $art){
        foreach ($data2 as $key) {
            $mov=$key->NUM_MOV;
        }
        $this->query="SELECT first 1 * FROM MINVE06 WHERE CVE_ART = '$art' and num_mov > $mov AND CVE_CPTO=1 ORDER BY NUM_MOV asc";
        echo '<br/>Obtenemos el numero de la siguiente compra, que es cuando se tiene que recostear: '.$this->query.'<br/>';
        $res=$this->EjecutaQuerySimple();
        $row =ibase_fetch_object($res);
        if($row){
            echo '<br/>tiene datos<br/>';
            $mov=$row->NUM_MOV; 
        }
        return $mov;
    }

    function antCompra($data2, $compra , $art){
        foreach ($data2 as $key) {
            $mov=$key->NUM_MOV;
        }
        $this->query="SELECT first 1 * FROM MINVE06 WHERE CVE_ART = '$art' and num_mov < $mov AND CVE_CPTO=1 ORDER BY NUM_MOV desc";
        $res=$this->EjecutaQuerySimple();
        $row =ibase_fetch_object($res);
        if($row){
           $this->query="UPDATE MINVE06 SET COSTO_PROM_INI = $row->COSTO_PROM_FIN WHERE NUM_MOV = $mov";
           $this->queryActualiza();
           $mov=$row->NUM_MOV; 
        }
        return $mov;
    }

    function sincCxP($info){
        $error=array();
        $mensajeE=array();
        $d = count($info);
        $i = 0;
        foreach ($info as $key) {
            $this->query="INSERT INTO PAGA_M06 (CVE_PROV, REFER, NUM_CARGO, NUM_CPTO, CVE_FOLIO, CVE_OBS, NO_FACTURA, DOCTO, IMPORTE, FECHA_APLI, FECHA_VENC, AFEC_COI, NUM_MONED, TCAMBIO, IMPMON_EXT, FECHAELAB, CTLPOL, TIPO_MOV, CVE_BITA, SIGNO, CVE_AUT, USUARIO, ENTREGADA, FECHA_ENTREGA, REF_SIST, STATUS ) 
                VALUES ('$key->CVE_PROV', '$key->REFER', $key->NUM_CARGO, $key->NUM_CPTO, '$key->CVE_FOLIO', $key->CVE_OBS, '$key->NO_FACTURA', '$key->DOCTO', $key->IMPORTE, '$key->FECHA_APLI', '$key->FECHA_VENC', '$key->AFEC_COI', $key->NUM_MONED, $key->TCAMBIO, $key->IMPMON_EXT, '$key->FECHAELAB', null, '$key->TIPO_MOV',null, $key->SIGNO,null, $key->USUARIO, null, null, 'T', '$key->STATUS')";
            if(@$this->grabaBD()){
                $i++;
            }else{
                //echo '<br/> Error, no se pudo insertar el gasto'.$this->query.'<br/>';
                $error[]=$this->query;
            }    
        }
        if(count($error)>0){
            $mensaje=array("status"=>'no', "errores"=>$error, "mensaje"=>'<br/>Se agregaron '.$i.' de '.$d);
        }else{
            $mensaje=array("status"=>'ok',"mensaje"=>'<br/>Se agregaron '.$i.' de '.$d);
        }
        return $mensaje;
    }



    function traePagos(){
        $data=array();
        $this->query="SELECT * FROM PAGA_DET06 WHERE CVE_PROV CONTAINING('T') AND  BENEFICIARIO IS NULL";
        $res=$this->EjecutaQuerySimple();
        while ($tsarray=ibase_fetch_object($res)){
            $data[]=$tsarray;
        }

        $this->query="UPDATE PAGA_DET06 SET BENEFICIARIO = 'Tienda Partimar' where BENEFICIARIO is null and cve_prov containing('T')";
        $this->queryActualiza();
        
        return $data;
    }

    function cabeceraDoc($doc){
        $this->query="SELECT * FROM FACTR06 R LEFT JOIN CLIE06 C ON C.CLAVE = R.CVE_CLPV WHERE trim(CVE_DOC) = trim('$doc')";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function detalleDoc($doc){
        $this->query="SELECT * FROM PAR_FACTR06 P  LEFT JOIN INVE06 I ON I.CVE_ART = P.CVE_ART WHERE trim(CVE_DOC) = trim('$doc')";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

}