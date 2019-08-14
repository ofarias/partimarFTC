
 <?php 

 <td>
    <input name="fecha" type="text" required="required" class="date" value="<?php echo $fecha?>" />
 </td>

  <button name="ingresarPago" type="submit" value="enviar" class ="btn btn-success" onclick="ocultar(mnt.value)" id= "btnPago"  style="display:inline;">Agregar</button> 

<input type="number" step="any" class="form-control" name="desc2" id="desc2" value="0.00" placeholder ="Descuento 2" min="0" max="100" onChange="CostoTotal(costo_prov.value,desc1.value,this.value,desc3.value,desc4.value,iva);"/><br>


  <!--Modified by GDELEON 3/Ago/2016-->
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>

  $(document).ready(function() {
    $(".date").datepicker({dateFormat: 'dd.mm.yy'});
  } );

  function ocultar(a){
    if(a == ''){
      //alert('El valor esta vacio' + a);
    }else{
      //alert('Presiono el boton con valor : '+ a);
      document.getElementById('btnPago').classList.add('hide');
      //document.getElementById('formCliente').style.display='block
    }
  }
  
</script>





 <a href="index.php?action=docsSucursal&cvecl=<?php echo $sc->CVE_CLPV?>" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;"> <?php echo '('.$sc->CVE_CLPV.')'.$sc->NOMBRE?> </a>
 


Ocualtar por ID con style:

document.getElementById('modificar').style.display = 'none';
document.getElementById('modificar').style.display = 'hidden';
document.getElementById('modificar').style.display = 'inline';
window.onload=function(){
        var r = document.getElementById('restric').value;
        var c = document.getElementById('cort').value;
    if(r > 0){
        alert('El cliente tiene '+ r + ' documentos con vencimiento de mas de 45 dias, se puede solicitar la restriccion');
      document.getElementById('btnRestr').style.display='inline';
    }
    if(c > 0){
         alert('El cliente tiene '+ c + ' documentos con vencimiento de mas de 45 dias, se puede solicitar el corte de credito');
      document.getElementById('btnCorte').style.display='inline';
     }
  }


Refirect 

function guardaCargoFinanciero($monto, $fecha, $banco){
    	session_cache_limiter('private_no_expire');        
     	if (isset($_SESSION['user'])) {            
             $data = new pegaso;
             $pagina = $this->load_template('Pagos');        	            
             ob_start();
             $registro=$data->guardaCargoFinanciero($monto, $fecha, $banco);
             &maestro={$maestro}
             $redireccionar = "regCargosFinancieros";
             $pagina=$this->load_template('Pedidos');
             $html = $this->load_page('app/views/pages/p.redirectform.php');
             include 'app/views/pages/p.redirectform.php';
             $this->view_page($pagina);                     
     	} else {
             $e = "Favor de Iniciar Sesión";
             header('Location: index.php?action=login&e=' . urlencode($e));
             exit;
     	}	
    }





Colores 
  $color="style='background-color:brown;'";
  <tr class="odd gradeX" <?php echo $color;?> id="<?php echo $i;?>">

?>



Ver Orden de compra Original 


<a href="index.php?action=detalleOC&doco=<?php echo $data->CVE_DOC?>" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;" >Ver Original</a>


Ver Historial de ID
<a href="index.php?action=historiaIDPREOC&id=<?php echo $data->ID_PREOC?>" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;"> <?php echo $data->ID_PREOC?></a>




evitar en enter en los formularios:

en la etiqueta <input onkeypress="return pulsar(event)/>
en el Javascript
 function pulsar(e) { 
  tecla = (document.all) ? e.keyCode :e.which; 
  return (tecla!=13); 
} 

saltos en los tittle =  &#10;