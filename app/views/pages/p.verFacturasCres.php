<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                Revision de Facturacion.
                        </div>
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables-verFacturas">
                                    <thead>
                                        <tr>
                                            <th>Factura</th>
                                            <th>Fecha Factura</th>
                                            <th>Cliente</th>
                                            <th>SubTotal</th>
                                            <th>Impuestos</th>
                                            <th>Descuentos</th>
                                            <th>Total</th>
                                            <th>UUID</th>
                                            <th>RFC CLIENTE</th>
                                            <th>Accion</th>
                                            <th>Ver Partidas <br/> de Factura</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($facturas as $data): 
                                        ?>
                                       <tr>
                                            <td><?php echo $data->SERIE.$data->FOLIO;?></td>
                                            <td><?php echo $data->FECHA;?></td>
                                            <td><?php echo $data->NOMBRE;?></td>
                                            <td><?php echo '$ '.number_format($data->SUBTOTAL,2);?></td>
                                            <td><?php echo '$ '.number_format($data->IVA,2);?></td>
                                            <td><?php echo '$ '.number_format($data->DESCUENTO,2);?></td>
                                            <td><?php echo '$ '.number_format($data->IMPORTE,2);?></td>
                                            <td><?php echo $data->UUID;?></td>
                                            <td><?php echo $data->CLIENTE;?></td>
                                            <form action="index.php" method="post">
                                             <input type="hidden" name="id" value="<?php echo $data->ID?>">
                                             <td>
                                                <select  name="nstatus" required="required">
                                                    <option value="">--Elige Nuevo Status--</option>
                                                    <option value ="insertSAE">Guardar en SAE</option>
                                                    <option value ="eliminaFACT">Rechazar Facturar</option>
                                                </select>
                                                <button name="ejecutaAcccion2" value="<?php echo $data->ID?>" type="submit" onclick="ocultar(this.value)" id="btn_<?php echo $data->ID?>">Ejecutar</button>
                                             </td>
                                             </form>
                                             <td>
                                                 <a href="index.php?action=DetalleFactura&docf=<?php echo $data->ID?>" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;"  class="btn btn-info" > Ver Partidas</a>
                                             </td>
                                            </tr> 
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                      </div>
            </div>
        </div>
</div>

<script>
    function ocultar(a){
    if(a == ''){
      
    }else{
      //alert('Presiono el boton con valor : '+ a);
      document.getElementById('btn_'+a).classList.add('hide');
      //document.getElementById('formCliente').style.display='block
    }
  }
</script>