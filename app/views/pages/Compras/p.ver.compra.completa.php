<br/>
<br/>
<style type="text/css">
    #number {
        width: 9em;
    }
    #number2{
        width: 5em;
    }   
</style>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Detalla de Ordenes de Compra.
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>No Documento</th>
                                            <th>Proveedor</th>
                                            <th>Fecha</th>
                                            <th>Importe</th>
                                            <th>Moneda</th>
                                            <th>Tipo de Cambio</th>
                                            <th>Documento Anterior</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        foreach ($compras as $data): 
                                            $doc = $data->CVE_DOC;
                                            $tipoDoc = $data->TIP_DOC;
                                            $cimp = $data->C_IMPUESTO;
                                            $moneda = '';
                                            if($data->NUM_MONED == 2){
                                                $moneda='Dolares';
                                            }elseif($data->NUM_MONED == 1){
                                                $moneda = 'Pesos';
                                            }elseif ($data->NUM_MONED == 3) {
                                                $moneda = 'Dolares';
                                            }
                                        ?>
                                        <tr>
                                            <td><?php echo $data->CVE_DOC;?></td>
                                            <td><?php echo $data->NOMBRE;?></td>
                                            <td><?php echo $data->FECHAELAB;?></td>
                                            <td align="right"><?php echo '$ '.number_format($data->IMPORTE,2);?></td>
                                            <td><?php echo $moneda;?></td>
                                            <td><?php echo $data->TIPCAMB;?></td>
                                            <td><?php echo $data->DOC_ANT;?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                 </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                      </div>
            </div>
        </div>
</div>
<br/>
<div>
    <label title="Se creara la cuenta por cobrar Automaticamente">Agregar gastos a la Orden de Compra</label>
        <form action="uploadCompCompras.php" method="post" enctype="multipart/form-data" id="gasto">
        <br/>
            <p> Seleccione el tipo de Gasto: <select name="tipo" required="required" id="tipoGasto">
                <option value="Flete Maritimo">Flete Maritimo</option>
                <option value="Flete Terrestre">Flete Terrestre</option>
                <option value="Gastos Aduanales">Gastos Aduanales</option>
                <option value="Seguro">Seguro</option>
                <option value="Dictamen">Dictamen</option>
                <!--<option value="Pedimento">Pedimento</option>-->
                <option value="Impuestos Aduanales">Impuestos Aduanales (Producto) </option>
                <option value="Imp Otro">Imp. Aduanales (otros)</option>
                <option value="otros">Otros</option>
            </select></p>
            <p>Costo: <input type="number" name="costo" step="any" min="0" max="999999" id="costo" required="required">  
                &nbsp;&nbsp; Moneda: &nbsp;&nbsp;
                <select name="moneda" required="required" class="moneda">
                    <option value="">Seleccione una moneda</option>
                    <option value="MNX">MNX Pesos</option>
                    <option value="USD"> USD Dolares </option>
                    <option value="EU">EU Euros</option>
                </select>
                &nbsp;&nbsp;<label id="tcl" class="hidden">Tipo de Cambio : <label id="m"></label></label>&nbsp;&nbsp; 
                <input type="number" name="tc" min="0" max="28" step="any" value="1" required="required" placeholder="T.C." id="tc" class="hidden">
            </p>
            <p>Genera Cuentas por Pagar: <input type="checkbox" name="cxp"></p>
            <p title="Si Se selecciona se desglozara el IVA del total del costo"> Gasto Fiscal : <input id="fiscal" type="checkbox" name="impuesto" onchange="xml()"></p>
            <p><input type="file" name="fileToUpload" id="fileToUpload" required="required"></p>
            <div id="xml"></div>
            <input type="hidden" name="doc" value="<?php echo $doc?>">
            <input type="hidden" name="tipodoc" value="<?php echo $tipoDoc?>">
            <p><select name="proveedor" required="required">
                <option>Seleccionar un proveedor</option>
                <?php foreach ($proveedores as $key): ?>
                    <option value="<?php echo $key->CLAVE?>"><?php echo $key->NOMBRE.'('.$key->CLAVE.') '?></option>
                <?php endforeach ?>
            </select></p>
            <input type="submit" value="Subir Documento" name="submit" class="btn btn-success">
        <br/>
        </form>
</div>
<br/>
<br/>

<?php 
      $ga = 0;
if(count($gastos)>0){?>
<?php $total = 0;
    foreach($gastos as $key):
        $total +=$key->MONTO;
        if($key->TIPO == 'Impuestos Aduanales'){
            $ga += $key->MONTO;
        }
    ?>
    <p> <?php echo '<font size="2.5pxs">'.$key->TIPO.'</font> : <font color="blue">$ '.number_format($key->MONTO,2).'</font>'?> <?php echo 'Archivos:'.$key->ARCHIVOS?> <?php echo 'Archivos XML: '.$key->XMLS?> </p>
<?php endforeach; ?>
    
    <p><a href="index.php?action=verGastos&doco=<?php echo $doc?>" class="btn btn-info" target="popup"onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;">Consultar Gastos </a></p>


    <p><font size="3.5pxs">Total Gastos:</font> <font color="red" size="3.5pxs"><b><?php echo '$ '.number_format($total,2)?></b></font></p>
<?php }?>


<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Partidas de la Recepcion .
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                  <font size="4pxs" face="arial"><b> Monto de los Gastos de Aduana a repartir:</b>
                                  <label id="ga" monto="<?php echo $ga?>" doc="<?php echo $doc?>"></font>
                                    <font color="blue" size="4pxs"> <b><?php echo ' $ '.number_format($ga,2)?></b></font></label><br/>
                                  <label id="rep">Repartido: <font color="red"><?php echo '$ '.number_format($repartido,2)?></font></label> <br/>
                                  <label id="res"> Restante: <font color="blue">0.00 </font></label>
                                   <br/>
                                   <br/>
                                    <thead>
                                        <tr>
                                            <th>Partida</th>
                                            <th>Clave <!--<br/> Pedimento--> </th>
                                            <th>Descripcion</th>
                                            <th>Costo MN <br/> Pieza </th>
                                            <th>Costo <?php echo $moneda?><br/> Pieza</th>
                                            <th>IVA</th>
                                            <th>Unidad Entrada</th>
                                            <th>Almacen</th>
                                            <th>Cantidad</th>
                                            <!--<th>Costo FOB USD </th>-->
                                            <th>Total Flete</th>
                                            <th>Total Flete Terrestre</th>
                                            <th>Dictamen y Etiquetas</th>
                                            <th>Total Agente Aduanal</th>
                                            <th>Total Seguro</th>
                                            <th>Total Impuestos</th>
                                            <th>IGI</th>
                                            <th>Desc x Partida %</th>
                                            <th>Costo Adicional <br/>x Pieza</th>
                                            <th>Costo Total</th>
                                            <!--<th>Guardar</th>-->
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        foreach ($partidas as $data): 
                                            
                                        ?>
                                        <tr>
                                            <form action="index.php" method="post" id = "cfb">
                                                <td><?php echo $data->NUM_PAR;?></td>
                                                <td><?php echo $data->CVE_ART;?> 
                                                <!--<input name="pedimento" type="text" maxlength="30" value=<?php echo (empty($data->PED))? "":$data->PED ?> > </td>-->
                                                <td><?php echo $data->DESCR;?></td>
                                                <td align="right"><?php echo '$ '.number_format($data->COST,2);?></td>
                                                <td align="right"><?php echo '$ '.number_format(($data->COST/$data->TIP_CAM),2)?></td>
                                                <td align="right"><?php echo '$ '.number_format($data->TOTIMP4,2);?></td>
                                                
                                                <td><?php echo $data->UNI_VENTA;?></td>
                                                <td><?php echo $data->NUM_ALM;?></td>
                                                <td><?php echo $data->CANT;?></td>
                                               <!--
                                                <td>
                                                    <input type="number" step="any" name="cfob"  placeholder="Costo Fob" value="<?php echo $data->COSTO_FOB?>">
                                                </td>-->
                                               <td><?php echo '$ '.number_format($data->COSTO_FLETE_MARITIMO,2)?></td>
                                               <td><?php echo '$ '.number_format($data->COSTO_FLETE,2)?></td>
                                               <td><?php echo '$ '.number_format($data->COSTO_DICTAMEN,2)?></td>
                                                <td><?php echo '$ '.number_format($data->COSTO_ADUANA,2)?></td>
                                                <td><?php echo '$ '.number_format($data->COSTO_SEGURO,2)?></td>
                                                <td><?php echo '$ '.number_format($data->COSTO_IMPUESTO,2)?></td>
                                                <td>
                                                    <input type="hidden" name="docr" value="<?php echo $docr?>">
                                                    <input type="number" step= "any" name="tc" placeholder="Gasto Aduanales" value="<?php echo empty($data->COSTO_IGI)? '0.00':number_format($data->COSTO_IGI,2,".","") ?>" class="ga" part="<?php echo $data->NUM_PAR?>">
                                                    <input type="hidden" name="par" value="<?php echo $data->NUM_PAR?>">
                                                </td>
                                                <td>
                                                    <?php echo $data->DESCU?>
                                                </td>
                                                <td>
                                                    <?php echo '$ '.number_format($data->COSTO_ADICIONAL_TOTAL,2).'<br/> $ '.number_format($data->COSTO_ADICIONAL_TOTAL/ $data->CANT,2)?>
                                                </td>
                                                <td> 
                                                <?php echo '$ '.number_format($data->COSTO_ADICIONAL_TOTAL + ($data->COST*$data->CANT),2).'<br/> $ '.number_format( ($data->COSTO_ADICIONAL_TOTAL/ $data->CANT ) + ($data->COST) ,2)?>  
                                                </td>
                                                <!--<td>
                                                    <select required="required" name="tipoPartida">
                                                        <option value="metro3"> Valor de la Mercancia </option>
                                                        <option value="ponderacion"> Ponderacion </option>
                                                    </select>
                                                </td>-->
                                            </form>
                                            
                                        </tr>
                                        <?php endforeach; ?>
                                 </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                      </div>
            </div>
        </div>
</div>
   <a class="btn btn-warning" value="enviar" href="index.php?action=finalizaCosteo&docr=<?php echo $docr?>" name="finalizaCosteo" > Finalizar</a> 

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script src="path/to/accounting.js"></script>
<script>

        $(".moneda").change(function(){
            var cambio = $(this).val();
            var mostrar = document.getElementById('tc');
            var text = document.getElementById('tcl');
            var m = document.getElementById('m');
            if(cambio == 'MNX'){
                 text.classList.add('hidden');
                 document.getElementById('tc').classList.add('hidden');
                 mostrar.value=1;                
            }else if(cambio == ''){
                alert('debe de seleccionar una opcion del menu');
            }else{
                //alert('Se ha cambiado a :' + cambio);
                text.classList.remove('hidden');
                m.innerHTML=cambio;
                document.getElementById('tc').classList.remove('hidden');
           
            }
            
        })


        $(document).ready(function(){
            var folios = 0;
            $("input.ga").each(function(index){
                folios+= parseFloat(this.value,2);
            });
            var monto = document.getElementById('ga').getAttribute('monto');
            document.getElementById('rep').innerHTML='Repartido <font color ="red" size="4pxs"> $' + (folios) +'</color>';
            document.getElementById('res').innerHTML='Restante <font color="blue" size="4pxs"> $' +(monto - folios).toFixed(2) + '</color>';
        })

        $("input.ga").change(function(){
            var folios = 0;
            var part = $(this).attr('part');
            var montop = $(this).val();
            $("input.ga").each(function(index){
                folios+= parseFloat(this.value,2);
            });
            alert('Valor de los folios' + folios);
            var monto = document.getElementById('ga').getAttribute('monto');
            var doc = document.getElementById('ga').getAttribute('doc');
            var saldo = monto-folios;

            if(confirm('Se asigna un gasto de aduana de: $ '+ montop + ' a la partida ' + part ));
            $.ajax({
                url:'index.php',
                type:'post',
                dataType:'json',
                data:{gastoAduana:doc, part:part, monto:montop, saldo:saldo},
                success:function(data){
                    if(data.status == 'ok'){
                        alert('Se Guardo el gasto a la partida');
                    }
                }
            });

            document.getElementById('rep').innerHTML='Repartido <font color ="red" size="4pxs"> $' + (folios) +'</color>';
            document.getElementById('res').innerHTML='Restante <font color="blue" size="4pxs"> $' + (monto - folios).toFixed(2) + '</color>';
        })


        $("#boton").click(function(){
                var form = document.getElementById("gasto");
                alert("esta seguro de guardar el Gasto?");
        })

        function xml(){
            var opcion = document.getElementById("fiscal");
            e = document.getElementById("xml");
            if(opcion.checked){
                //alert('Seleccionado');
                var i = document.createElement('input');
                i.type="file";
                i.name="fileToUploadXML";
                i.id="fileToUploadXML";
                i.required="true";
                e.appendChild(i);
                var text = document.createElement('p');
                text.id="texto";
                text.innerHTML='<b>Seleccionar el Archivo XML, por favor.</b>';
                e.appendChild(text);
            }else{
                i=document.getElementById('fileToUploadXML');
                t=document.getElementById('texto');
                e.removeChild(i);
                e.removeChild(t);
                //alert('No seleccionado');
            }
        }



</script>