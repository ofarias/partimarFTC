<?php 
	require_once 'app/model/database.php';

	class ventasExternas extends database{

	function verCatalogoProductos(){
			$usuario = $_SESSION['user']->NOMBRE;
			$almacen =$_SESSION['user']->LETRA;

			$this->query="SELECT I.*, M.*, A.DESCR AS NOMBRE, (SELECT MAX(FCHCADUC) FROM LTPD06 L1 WHERE L1.CVE_ART = I.CVE_ART AND L1.CANTIDAD > 0 ) AS CADUCIDADMAXIMA, (SELECT MIN(FCHCADUC) FROM LTPD06 L2 WHERE L2.CVE_ART = I.CVE_ART AND L2.CANTIDAD > 0) AS CADUCIDADMINIMA, 
					COALESCE((SELECT CANTIDAD FROM FTC_DOCS_VENTAS WHERE usuario = '$usuario' and CLAVE = I.CVE_ART and status = 0), 0) AS H, 
					CL.CAMPLIB15, CL.CAMPLIB10, COALESCE( (SELECT UNIDAD FROM FTC_DOCS_VENTAS WHERE usuario = '$usuario' and CLAVE = I.CVE_ART and status = 0), 'pza') as UNIDAD
						FROM MULT06 M 
							LEFT JOIN INVE06 I ON I.CVE_ART = M.CVE_ART 
							LEFT JOIN ALMACENES06 A ON A.CVE_ALM = M.CVE_ALM
							LEFT JOIN INVE_CLIB06 CL ON CL.CVE_PROD = M.CVE_ART
							WHERE M.CVE_ALM = $almacen AND I.TIPO_ELE = 'P'";
			$res=$this->EjecutaQuerySimple();
			while ($tsArray = ibase_fetch_object($res)) {
				$data[]=$tsArray;
			}
			return $data;

		}

	function seleccionar($prod, $cant, $tipo){
        $usuario = $_SESSION['user']->NOMBRE;
        $mensaje ='';
        $this->query="SELECT * FROM FTC_DOCS_VENTAS WHERE usuario='$usuario' and clave='$prod' and status = 0";
        $rs=$this->EjecutaQuerySimple();
        $row=ibase_fetch_object($rs);
        if($row){
            if($tipo == 'b'){
                $this->query="UPDATE FTC_DOCS_VENTAS SET STATUS = 9 WHERE usuario='$usuario' and clave = '$prod' and status = 0 ";
                $this->queryActualiza();
            }elseif($tipo=='a'){
            	if($cant == 'caja' ){
            		$this->query="UPDATE FTC_DOCS_VENTAS SET UNIDAD = '$cant', EXISTENCIAS=(SELECT EXIST FROM MULT06 WHERE CVE_ART='$prod' and CVE_ALM=1) where usuario='$usuario' and clave = '$prod' and status = 0";	
            	}elseif($cant=='pza') {
            		$this->query="UPDATE FTC_DOCS_VENTAS SET UNIDAD = '$cant', EXISTENCIAS=(SELECT EXIST FROM MULT06 WHERE CVE_ART='$prod' and CVE_ALM=1) where usuario='$usuario' and clave = '$prod' and status = 0";	
            	}else{
            		$this->query="UPDATE FTC_DOCS_VENTAS SET CANTIDAD = $cant, EXISTENCIAS=(SELECT EXIST FROM MULT06 WHERE CVE_ART='$prod' and CVE_ALM=1) where usuario='$usuario' and clave = '$prod' and status = 0";	
            	}
                $this->queryActualiza();
            }
            exit('Existe el producto y se tiene que actualizar');
        }else{
            $this->query="INSERT INTO FTC_DOCS_VENTAS (ID, USUARIO, CANTIDAD, CLAVE, FECHA, STATUS, EXISTENCIAS, UNIDAD)
            			VALUES (NULL, '$usuario', $cant, '$prod', current_timestamp, 0, (SELECT EXIST FROM MULT06 WHERE CVE_ART='$prod' and CVE_ALM=1), 'pza')";
            $this->grabaBD();
            exit('No Existe el producto y se inserta la nueva partida');
        }
        return $mensaje;
    }

    function correoVentas(){
        $usuario=$_SESSION['user']->NOMBRE;
        $data= array();
        $this->query="SELECT s.*, i.descr from FTC_DOCS_VENTAS s left join inve06 i on i.CVE_ART = s.clave 
                        where s.status = 0 and s.usuario='$usuario'";
        $rs=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($rs)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function limpiaProductos($clie, $correo){
        $usuario=$_SESSION['user']->NOMBRE; 
        $this->query="UPDATE FTC_DOCS_VENTAS SET correos = '$clie'||', '||'$correo', status=8 where usuario='$usuario' and status = 0";
        $this->EjecutaQuerySimple();
        return;
    }
}