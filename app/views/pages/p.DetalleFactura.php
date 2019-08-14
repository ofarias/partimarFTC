<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                Revision de Facturacion Crescendo.
                        </div>
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Factura</th>
                                            <th>Fecha Factura</th>
                                            <th>Cliente</th>
                                            <th>SubTotal</th>
                                            <th>Impuestos IVA</th> 
                                            <th>Impuestos IEPS</th>      
                                            <th>Descuentos</th>
                                            <th>Total</th>
                                            <th>UUID</th>
                                            <th>RFC CLIENTE</th>
                                            <th>Cambio de cliente</th>
                                            <th>Ejecutar Accion</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($cabecera as $data): 
                                        ?>
                                       <tr>
                                            <td><?php echo $data->SERIE.$data->FOLIO;?></td>
                                            <td><?php echo $data->FECHA;?></td>
                                            <td><?php echo $data->NOMBRE;?></td>
                                            <td><?php echo '$ '.number_format(($data->IMPORTE / 1.16)-$data->IEPS,2);?></td>
                                            <td><?php echo '$ '.number_format($data->IVA,2);?></td>
                                            <td><?php echo '$ '.number_format($data->IEPS,2);?></td>
                                            <td><?php echo '$ '.number_format($data->DESCUENTO,2);?></td>
                                            <td><?php echo '$ '.number_format(($data->IMPORTE ),2);?></td>
                                            <td><?php echo $data->UUID;?></td>
                                            <td><?php echo $data->CLIENTE;?></td>
                                            <form action="index.php" method="post">
                                            <td>
                                                <select name="ncliente" >
                                                    <option value="old"> Seleccione nuevo cliente</option>
                                                    <?php foreach ($cliente as $key): ?>
                                                        <option value='<?php echo $key->CLAVE?>'><?php echo trim($key->CLAVE).'-'.substr(trim($key->NOMBRE),0,35)?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </td>
                                             <input type="hidden" name="id" value="<?php echo $data->ID?>">
                                             <td>
                                                <select  name="nstatus" required="required">
                                                    <option value="">--Elige Nuevo Status--</option>
                                                    <option value ="insertSAE">Guardar en SAE</option>
                                                    <option value ="eliminaFACT">Rechazar Facturar</option>
                                                </select>
                                                <button name="ejecutaAcccion" value="enviar" type="submit">Ejecutar</button>
                                             </td>
                                             </form>
                                            </tr> 
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                      </div>
            </div>  
        </div>
</div>
<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                Partidas de la Factura.
                        </div>
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Partida</th>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Descripcion</th>
                                            <th>Precio <br/> Unitario ($)</th>
                                            <th>Impuestos IVA % <br/>  ($) Cantidad * Unitario * IVA </th>
                                            <th>Impuestos IEPS % <br/> ($) Cantidad * Unitario * IEPS </th>
                                            <th>Descuentos</th>
                                            <th>Total</th>
                                            <th>Nuevo Total <br/> Cant * Unitario + IVA + IEPS</th>
                                            </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($detalle as $data): 
                                        ?>
                                       <tr>
                                            <td><?php echo $data->PARTIDA;?></td>
                                            <td><?php echo $data->CVE_ART;?></td>
                                            <td><?php echo $data->CANTIDAD;?></td>
                                            <td><?php echo $data->DESCRIPCION;?></td>
                                            <form action="index.php" method="post">
                                            <input type="hidden" name="subTotal" value="<?php echo ($data->CANTIDAD * $data->UNITARIO)?>" id="base_<?php echo $data->PARTIDA?>" >
                                            <input type="hidden" name="cantidad" value="<?php echo $data->CANTIDAD?>" id="cantidad_<?php echo $data->PARTIDA?>" >
                                            <td><input type="number" step="any" name="precio" min="0" max="9999999" value="<?php echo $data->UNITARIO;?>" onchange="actSaldo(this.value, <?php echo $data->PARTIDA?>)" id="unitario_<?php echo $data->PARTIDA?>"  >  </td>
                                            <td><input type="number" step="any" name="impIVA" min="0" max="100" value="<?php echo (empty($data->IVA_TASA))? '0':$data->IVA_TASA?>" onchange="actSaldo(this.value,<?php echo $data->PARTIDA?>)" id="iva_<?php echo $data->PARTIDA?>"  > % </td>
                                            <td><input type="number" step="any" name="impIEPS" min="0" max="100" value="<?php echo (empty($data->IEPS_TASA))? '0':$data->IEPS_TASA?>" onchange="actSaldo(this.value,<?php echo $data->PARTIDA?>)" id="ieps_<?php echo $data->PARTIDA?>" > % </td>
                                            <td><?php echo '$ '.number_format($data->DESCUENTO,2);?></td>
                                            <td><?php echo '$ '.number_format($data->IMPORTE,2);?></td>
                                            <td><input type="number" name="tot" readonly="readonly" id="tot_<?php echo $data->PARTIDA?>" value="<?php echo (!empty($data->NTOT))? $data->NTOT:$data->IMPORTE ?>"/></td>
                                            <input type="hidden" name="docf" value="<?php echo $docf?>">
                                            <input type="hidden" name="documento" value="<?php echo $data->DOCUMENTO?>">
                                            <td>
                                               <input type="hidden" name="idDetalle" value="<?php echo $data->ID?>" >
                                               <button name="actImp" value="<?php echo $data->PARTIDA?>" type="submit" id="btn_<?php echo $data->PARTIDA?>" onclick="ocultar(this.value)"  > Guardar</button>
                                            </td>
                                            </form>
                                       </tr> 
                                      
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                      </div>
            </div>
        </div>
</div>
<script>


        function actSaldo(me, partida){
            var cantidad = document.getElementById('cantidad_'+partida).value;
            var unitario = document.getElementById('unitario_'+partida).value;
            var iva=document.getElementById('iva_'+partida).value;
            var ieps=document.getElementById('ieps_'+partida).value;
            var base=document.getElementById('base_'+partida).value;
            var iva2 = (iva / 100) * parseFloat(base,2);
            var subtotal = parseFloat(base,2) + parseFloat(iva2,2);
            
            var subTotal = cantidad * unitario;
            var montoIva = subTotal * (iva / 100);
            var montoIeps = subTotal * (ieps / 100);
            var Total = subTotal + montoIva + montoIeps;


            ieps = (ieps / 100 ) * base;
            var subtotal2 = subtotal + ieps;
            document.getElementById('tot_'+partida).value =Total.toFixed(2);
        } 


        function ocualtar(me){
            if(me == ''){
      //alert('El valor esta vacio' + a);
            }else{
              //alert('Presiono el boton con valor : '+ a);
              document.getElementById('btn_'+me).classList.add('hide');
              //document.getElementById('formCliente').style.display='block
            }
        }

</script>