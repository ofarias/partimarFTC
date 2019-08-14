<br /><br />
<?php  
    if(isset($_REQUEST->ok)){
        echo 'Se guardo la orden:';
    } 
?>
        <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Preparar Remision
                        </div>
                        <!-- /.panel-heading -->
                           <div class="panel-body">
                            <div class="table-responsive">
                            
                            <input type="hidden" value="" name="preOrdenDeCompra"/> <!-- Asi se llama en el Index -->
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>REMISION</th>
                                            <th>PARTIDA</th>
                                            <th>CLAVE</th>
                                            <th>CANTIDAD</th>
                                            <th>DESCRIPCION</th>
                                            <th>PRECIO</th>
                                            <th>PREPARADO</th>
                                            <th>FALTANTE</th>
                                            <th>Preparar</th>
                                           
                                        </tr>
                                    </thead>   
                                   
                                  <tbody>
                                        <?php
                                        foreach ($partidas as $data):                                                         
                                            $partidas = $data->PARTIDA;
                                            $color='';
                                            if($data->FALTANTE == 0){
                                                $color = "style='background-color:#b3e6ff'";
                                            }elseif($data->FALTANTE > 0 and $data->FALTANTE < $data->CANTIDAD){
                                                $color = "style='background-color:#ffccff'";
                                            }
                                        ?>
                                        <tr class="odd gradeX" <?php echo $color?>>
                                            <td><?php echo $data->DOC?></td>
                                            <td><?php echo $data->PARTIDA;?><input type="hidden" name="CVE_DOC" value="<?php echo $data->COTIZA;?>"/></td>
                                            <td><?php echo $data->CLAVE;?></td>
                                            <td><?php echo $data->CANTIDAD;?></td>
                                            <td onClick="test1()"><?php echo $data->DESCRIPCION;?></td>
                                            <td><?php echo '$ '.number_format($data->PRECIO, 2);?></td>
                                            <td><?php echo $data->PREPARADO;?></td>
                                            <td><?php echo $data->FALTANTE;?></td>
                                            <td><input type="number" step="any" min="0" max="<?php echo $data->FALTANTE?>" name="preparar" 
                                                id="preparar_<?php echo $data->PARTIDA?>" value="" 
                                                onchange="preparar(this.value, <?php echo $data->PARTIDA?>,'<?php echo $data->DOC?>',<?php echo $data->FALTANTE?>)" /> 
                                            </td>                      
                                        </tr>
                                        <?php endforeach; ?>
                                        <input type="hidden" name="partidas" value="<?php echo $partidas?>" id="partidas">
                                 </tbody>
                                 </table>
                                 <center>
                            </div>
                      </div>
            </div>
        </div>
</div>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="http://bootboxjs.com/bootbox.js"></script>
<script type="text/javascript">

function preparar(preparar, partida, doc, faltante){
    var faltante = parseFloat(faltante);
    var preparar = parseFloat(preparar);
    var partidas = document.getElementById('partidas').value;
    if(faltante == 0 || preparar > faltante || preparar < 0){
        alert("No se puede preparar mas de lo solicitado o menos que 0 ");
        document.getElementById('preparar_'+partida).value='';
    }else if(faltante == preparar){
        if(confirm("Desea preparar xxx" +  faltante +", "+ partida +", " + doc)){
            $.ajax({
                url:'index.php',
                type:'post',
                dataType:'json',
                data:{asignaMaterial:1,doc:doc,cant:preparar,partida:partida, partidas:partidas},
                success:function(data){
                    location.reload(true);
                }
            });
        }
    }else{
        if(confirm("Se detecto un faltante de " +  (parseFloat(faltante,2) - parseFloat(preparar,2)) + ' Desea notificar el faltante a ventas?')){
            $.ajax({
                url:'index.php',
                type:'post',
                dataType:'json',
                data:{asignaMaterial:1,doc:doc,cant:preparar,partida:partida, partidas:partidas},
                success:function(data){
                    location.reload(true);
                }
            });
            alert('Se prepara la parcialidad y se avisa a ventas');
        }else{
            $.ajax({
                url:'index.php',
                type:'post',
                dataType:'json',
                data:{asignaMaterial:1,doc:doc,cant:preparar,partida:partida, partidas:partidas},
                success:function(data){
                    location.reload(true);
                }
            });
        }
    }
}


function sinCosto(idsel){
    alert("EL PRODUCTO NO TIENE COSTO, FAVOR DE SOLICITAR A COSTOS. GRACIAS");
    document.getElementById(idsel).checked="";
}

function ocultar(){
    document.getElementById('GOC').classList.add('hide');
}

$("#GOC").click(function() {
    if($(".Selct").is(":checked")){
        document.frmOrdCom.submit();
//OpenWarning("Estamos trabajando en esta seccion");
    }else{
        OpenWarning("Seleccione al menos una Partida");
    }
});

$("#marcarTodo").change(function () {
    if ($(this).is(':checked')) {
        $("#seleccionHabilitados input[type=checkbox]").prop('checked', true); 

    } else {
        $("#seleccionHabilitados input[type=checkbox]").prop('checked', false);
    }
});

    var tot = parseFloat("0");
    var seleccionados = parseInt("0");  
    $("input[type=checkbox]").on("click", function(){

       
        var monto = $(this).attr("monto");
        //monto = monto.replace("$", "");
        //monto = monto.replace(",", "");     
        monto = parseFloat(monto);        
        if(this.checked){
           
            tot+=monto;
          
          } else {
            tot-=monto;
        }
        //alert("Total: "+total);
        t = parseFloat(tot).toFixed(2);
        $("#totales_check").text(t);
        st = parseFloat(t / 1.16).toFixed(2);
        $("#subtotal_check").text(st);
        iva  = parseFloat(t-(t /1.16)).toFixed(2);
        $("#iva_check").text(iva)
        seleccionados = $("input:checked").length;
        $("#seleccion_cr").val(seleccionados);
        $("#total").val(tot); 
    });

function validaCant(a,b,c,d){
    //alert('Esta Cambiando:' + this + 'por'+ rest);
    var cantn = parseFloat(a);
    var cantm = parseFloat(b);
    alert('Nueva Cantidad: ' + cantn + ' Cantidad Maxima: '+ cantm + 'preoc valor' + c + ' Clave proveedor:' + d);
    
    if(cantn > cantm ){
        document.getElementById("cantidad_"+c).value=cantm;
        OpenWarning("Cantidad capturada excede el faltante");
        document.getElementById("cantidad_"+c).focus();
        return false;
    }else{

         $.ajax({
            type: 'GET',
            url: 'index.php',
            data: 'action=actualizaCanti&cantnu='+a+'&idpreoc='+c+'&idprov='+d,   
            success: function(data){
                    if(data=="ok"){
                                        OpenSuccess("Se cambio la cantidad, por seguridad la cantidad restante  se pasara a la gerencia de compra hasta realizar la Orden de compra actual....",d);
                                  }else{
                                        OpenWarning("No se pudo actualizar, intente mas tarde");
                                  } 
                               }
        });
    }
}

function OpenWarning(mensaje) {
                var mensaje = mensaje || "Algo no salió como se esperaba...";
                bootbox.dialog({
                    message: mensaje,
                    title: "<i class=\"fa fa-warning warning\"></i>",
                    className: "modal modal-message modal-warning fade",
                    buttons: {
                        "OK": {
                            className: "btn btn-warning",
                            callback: function () {
                            }
                        }
                    }
                });
}

function OpenSuccess(mensaje,d) {
                var mensaje = mensaje || "Todo bien...";
                bootbox.dialog({
                    message: mensaje,
                    title: "<i class=\"fa fa-check success\"></i>",
                    className: "modal modal-message modal-success fade",
                    buttons: {
                        "OK": {
                            className: "btn btn-success",
                            callback: function () {
                                 window.location="index.php?action=verCanasta&idprov=" + d;
                            }
                        }
                    }
                });
}
</script>