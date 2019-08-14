<?php

require_once 'app/model/databaseT.php';

/*Clase para hacer uso de database*/
class tienda extends database_tienda{
	/*Comprueba datos de login*/
	function AccesoLogin($user, $pass){
		$u = $user;
			$this->query = "SELECT ID, USER_LOGIN, USER_PASS, USER_ROL, LETRA, LETRA2, LETRA3, LETRA4, LETRA5, LETRA6, NUMERO_LETRAS, NOMBRE, CC, CR, aux_comp, COORDINADOR_COMP
						FROM PG_USERS 
						WHERE USER_LOGIN = '$u' and USER_PASS = '$pass'"; /*Contraseña va encriptada con MD5*/
		 	$log = $this->QueryObtieneDatos();
		 
			$this->query = "SELECT ID, USER_LOGIN, USER_PASS, USER_ROL, LETRA, LETRA2, LETRA3, LETRA4, LETRA5, LETRA6, NUMERO_LETRAS, NOMBRE, CC, CR, aux_comp, COORDINADOR_COMP
						FROM PG_USERS 
						WHERE USER_LOGIN = '$u' and USER_PASS = '$pass'"; /*Contraseña va encriptada con MD5*/
		 	$log2 = $this->EjecutaQuerySimple();
		 
		 	while ($tsArray = ibase_fetch_object($log2)){
		 		$data[]=$tsArray;
		 	}

			if(isset($log) > 0){
				/*Creamos variable de sesion*/
					$_SESSION['user'] = $log;
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

     function verAuxSaldosCxc($fi, $ff){
        $this->query="SELECT  * from clie03";
        $rs=$this->EjecutaQuerySimple();

        while($tsarray=ibase_fetch_object($rs)){
            $data[]=$tsarray;
        }

        foreach($data as $key){
            $this->query="SELECT clave, nombre,
                            (select iif(sum(importe) is null or sum(importe) = 0, 0, sum(importe) /1.16) from cuen_m03 where fecha_apli between '01.01.2017' and '$fi' and tipo_mov= 'C' and trim(cve_clie)  = trim('$key->CLAVE')) as Cargos_iniciales,
                            (select iif(sum(importe) is null or sum(importe) = 0, 0, sum(importe) /1.16) from cuen_det03 where fecha_apli between '01.01.2017' and '$fi' and tipo_mov = 'A' and num_cpto >= 23  and trim(cve_clie)  = trim('$key->CLAVE')) as Pagos_iniciales,
                            (select iif(sum(importe) is null or sum(importe) = 0, 0, sum(importe) /1.16) from cuen_m03 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'C' and trim(cve_clie)  = trim('$key->CLAVE')) as cargos,
                            (select iif(sum(importe) is null or sum(importe) = 0, 0, sum(importe) /1.16) from cuen_det03  where fecha_apli between '$fi' and '$ff' and tipo_mov = 'A' and trim(cve_clie)  = trim('$key->CLAVE')) as pagos
                            from clie03 where trim(clave) = trim('$key->CLAVE')";
            $rs=$this->EjecutaQuerySimple();
            while($tsarray=ibase_fetch_object($rs)){
                $data2[]=$tsarray;
            } 
        }
        return $data2;
    }

    function saldoFinal($fi, $ff){

            $this->query="SELECT iif(sum(importe)is null, 0, sum(importe)/ 1.16) AS CARGOS_INICIALES from cuen_m03 where fecha_apli between '01.01.2017' and '$fi' and tipo_mov= 'C'";
            $rs=$this->EjecutaQuerySimple();
            $row =ibase_fetch_object($rs);
            $ci = $row->CARGOS_INICIALES;

            $this->query="SELECT iif(sum(importe) is null, 0, sum(importe)/1.16) as Pagos_iniciales from cuen_det03 where fecha_apli between '01.01.2017' and '$fi' and tipo_mov = 'A'";
            $rs2 = $this->EjecutaQuerySimple();
            $row2 = ibase_fetch_object($rs2);
            $pi = $row2->PAGOS_INICIALES;

            $this->query="SELECT sum(importe)/1.16 as cargos from cuen_m03 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'C'";
            $rs3 = $this->EjecutaQuerySimple();
            $row3 = ibase_fetch_object($rs3);
            $cargos = $row3->CARGOS;

            $this->query="SELECT sum(importe)/1.16 as pagos from cuen_det03 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'A'";
            $rs4 = $this->EjecutaQuerySimple();
            $row4 = ibase_fetch_object($rs4);
            $pagos = $row4->PAGOS;

          //  echo 'Consulta Pagos: '.$this->query.'<p>';
          //  echo 'Cargos Iniciales: $ '.$ci.'<p>';
          //  echo 'Pagos Iniciales: $ '.$pi.'<p>';
          //  echo 'Cargos: $ '.$cargos.'<p>';
          //  echo 'Pagos: $ '.$pagos.'<p>';

            $totalSaldo = $ci - $pi + $cargos - $pagos;

        return $totalSaldo;
    }


    function saldoVentasBrutas($fi, $ff){

   
        $this->query="SELECT 
                        iif( (sum(importe)is null or sum(importe) = 0), 0 , sum(importe) / 1.16) as cargos from cuen_m03 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'C' and num_cpto = 1";
            $rs3 = $this->EjecutaQuerySimple();
            $row3 = ibase_fetch_object($rs3);
            $cargos = $row3->CARGOS;
            //echo 'Detecto el cambio';
            return ;
    }

    function ventasBrutas($fi, $ff){
             /// brutas es todas las ventas - los abonos que no son transferencias
        $data2=array();
        $this->query= "SELECT * FROM CLIE03 WHERE TIPO_EMPRESA = 'M'";
        $rs=$this->EjecutaQuerySimple();
        while($tsarray=ibase_fetch_object($rs)){
            $data[]=$tsarray;
        }
        $facutras = 0;
        foreach ($data as $clie) {
            $cliente = $clie->CLAVE;

            $this->query="SELECT CLAVE, nombre,
                            (select iif( (sum(importe)is null or sum(importe) = 0), 0 , sum(importe) / 1.16) as fact from cuen_m03 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'C' and num_cpto = 1  and trim(cve_clie) = trim('$cliente') ) as Facturas,
                            (select iif( (sum(importe)is null or sum(importe) = 0), 0 , sum(importe) / 1.16) as ncs from cuen_det03 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'C' and num_cpto =16 and trim(cve_clie) = trim('$cliente') ) as imptNC,                            
                            (select iif( (sum(importe)is null or sum(importe) = 0), 0 , sum(importe) / 1.16) as cnp from cuen_det03 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'A' and num_cpto >= 23 and trim(cve_clie) = trim('$cliente')) as AbonosNoPagos
                            from Clie03
                            where trim(clave) = trim('$cliente')";
                             //echo $this->query.'<p>';

            
            $rs2=$this->EjecutaQuerySimple();
            while($tsarray= ibase_fetch_object($rs2)){
                $data2[]=$tsarray;
            }
        }



        return $data2;
    }


    function saldoVentasNetas($fi, $ff){
        $this->query="SELECT  iif(sum(importe/1.16) is null,0,sum(importe/1.16)) as cargos from cuen_m03 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'C'";
            $rs3 = $this->EjecutaQuerySimple();
            $row3 = ibase_fetch_object($rs3);
            $cargos = $row3->CARGOS;

        $this->query="SELECT iif(sum(importe/1.16) is null,0,sum(importe/1.16)) as pagos from cuen_m03 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'A'";
        $rs2=$this->EjecutaQuerySimple();
        $row2 = ibase_fetch_object($rs2);
        $abonosM = $row2->PAGOS;

        $this->query="SELECT iif(sum(importe/1.16) is null,0,sum(importe/1.16)) as pagos from cuen_det03 where fecha_apli between '$fi' and '$ff' and tipo_mov = 'A' 
            and num_cpto >= 23";
        $rs=$this->EjecutaQuerySimple();
        $row = ibase_fetch_object($rs);
        $abonosD = $row->PAGOS;


        $total = $cargos - $abonosM - $abonosD;
        
            return $total;

    }

    function ventasXproducto($fi, $ff){
        $data=array();
        $this->query="SELECT pf.cve_art, max(descr), sum(cant) as vendido, avg(pf.prec) as precioprom, max(costo_prom) as costoprom,
                        (SELECT PRECIO FROM PRECIO_X_PROD03 WHERE CVE_ART=pf.cve_art AND CVE_PRECIO = 1) AS PRECIOPUBLICO,
                        (Sum(cant) *  avg(pf.prec)) AS tOTALVENDIDO, (SUM(cant) * max(costo_prom)) as TotalCosto,
                        ((Sum(cant) *  avg(pf.prec)) - (SUM(cant) * max(costo_prom))) AS UTILIDAD,
                        (((Sum(cant) *  iif(avg(pf.prec) is null or avg(pf.prec) =0, 1, avg(pf.prec))) / ( SUM(cant) * iif(max(costo_prom) is null or max(costo_prom) = 0, 1,max(costo_prom))) -1)) * 100 AS PORCENTAJE
                        from par_factf03 pf
                        left join factf03 f on pf.cve_doc = f.cve_doc
                        left join inve03 i on i.cve_art = pf.cve_art
                        where f.fecha_doc >= '01.01.2018'
                        group by pf.cve_art";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        
        return $data;
    }

    function sincCxP(){
        ///  CVE_PROV, REFER , NUM_CPTO, NUM_CARGO INDICE UNICO.
        $data=array();
        $this->query="SELECT ('T'||trim(cve_prov)) as cve_prov, p.* from paga_m03 p where ref_sist is null";
        $res=$this->EjecutaQuerySimple();
        while ($tsarray=ibase_fetch_object($res)){
            $data[]=$tsarray;
        }
        $this->query="UPDATE paga_m03 set  ref_sist = 'T'";
        $this->EjecutaQuerySimple();
        return $data;
    }

    function insertaPagos($info){
        $i=0;
        $e=0;
        $errores=array();
        foreach ($info as $key) {
            $this->query="INSERT INTO PAGA_DET03 (CVE_PROV, REFER, NUM_CPTO, NUM_CARGO, ID_MOV, CVE_FOLIO, CVE_OBS, NO_FACTURA, DOCTO, IMPORTE, FECHA_APLI, FECHA_VENC, AFEC_COI, NUM_MONED, TCAMBIO, IMPMON_EXT, FECHAELAB, CTLPOL, CVE_BITA, TIPO_MOV, SIGNO, CVE_AUT, USUARIO, REF_SIST, NO_PARTIDA, REFBANCO_ORIGEN, REFBANCO_DEST, NUMCTAPAGO_ORIGEN, NUMCTAPAGO_DESTINO, NUMCHEQUE, BENEFICIARIO ) VALUES ('$key->CVE_PROV', '$key->REFER', $key->NUM_CPTO, $key->NUM_CARGO, $key->ID_MOV, '$key->CVE_FOLIO', $key->CVE_OBS, '$key->NO_FACTURA', '$key->DOCTO', $key->IMPORTE, '$key->FECHA_APLI', '$key->FECHA_VENC', 'A', $key->NUM_MONED, $key->TCAMBIO, $key->IMPMON_EXT, '$key->FECHAELAB', null, null, '$key->TIPO_MOV', $key->SIGNO, null, null, '$key->REF_SIST', $key->NO_PARTIDA, '$key->REFBANCO_ORIGEN', '$key->REFBANCO_DEST', '$key->NUMCTAPAGO_ORIGEN', '$key->NUMCTAPAGO_DESTINO', '$key->NUMCHEQUE', 'Pago Partimar')";
            if(@$this->grabaBD()){
                $i++;
            }else{
                $e++;
                echo "<br/>error al grabar:-> ".$this->query.'<br/>';
                $errores[]=$this->query;
            }
        }
        
        if(count($errores) > 0){
            $mensaje=array("status"=>'no', "errores"=>$errores, "mensaje"=>'existen '.$e.' errores');
        }else{
            $mensaje=array("status"=>'ok');
        }

        $this->query="UPDATE paga_det03 set cve_prov = (lpad( substring(cve_prov from 2 for 10), 10)) where cve_prov containing('T')";
        $this->EjecutaQuerySimple();

        return $mensaje;
    }

}