<br/>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Orden</th>
                                            <th>Fecha</th>
                                            <th>Proveedor</th>
                                            <th>RFC</th>
                                            
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        foreach ($recepciones as $data):
                                            
                                        ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $data->ORDEN;?></td>
                                            <td><?php echo $data->FECHAELAB?></td>
                                            <td><?php echo '('.$data->CVE_PROV.') '.$data->NOMBRE?></td>
                                            <td><?php echo $data->RFC?></td>               
                                        </tr>
                                 </tbody>
                          
                                 <?php endforeach; ?>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                      </div>
            </div>
        </div>
    </div>
<br />
<br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Productos de la Orden de compra.
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-aplicapago">
                                    <thead>
                                        <tr>
                                            <th>Partida</th>
                                            <th>Clave</th>
                                            <th>Descripcion</th>
                                            <th>Cantidad <br/> Orden</th>
                                            <th>Pendiente <br/> Orden </th>
                                            <th>Costo <br/> Unitario Bruto </th>
                                            <th>Costo <br> Total Bruto</th>
                                            <th>Valor IVA</th>
                                            <th>Costo Neto</th>
                                            <th>Unidad <br/> de Medida </th>
                                            <th>Clave <br/> Proveedor</th>
                                            <th>Identificador <br/> Unico Ventas</th>
                                            <th>Cantidad <br/> Recepcion</th>
                                            <th>Precio <br/> Lista</th>
                                            <th>Desc 1</th>
                                            <th>Desc 2 </th>
                                            <th>Desc 3 </th>
                                            <th>Desc Fin</th>
                                            <th>Costo Total</th>
                                            <th>Finalizar</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                            $subtotal = 0;
                                            $iva = 0;
                                            $total =0;
                                        foreach ($orden as $data):
                                            $subtotal = $subtotal +  ($data->COSTO);
                                            $color ='';
                                            if($data->CANT_RECIBIDA == 0){
                                                $color = "style='background-color:#ffc2b3'";
                                            }elseif ($data->CANT_RECIBIDA < $data->CANTIDAD) {
                                                $color ="style= 'background-color:#b3ffff'";
                                            }elseif ($data->CANT_RECIBIDA == $data->CANTIDAD) {
                                                $color = "style= 'background-color:#d9ffb3'";
                                            }
                                            ?>
                                        <tr title="<?php echo 'Cliente del Pedido: '.$data->CLIENTE.' de la cotizacion '.$data->COTIZACION.' con Fecha del '.$data->FECHASOL.' Cantidad Original Ventas :'.$data->CANT_ORIG ?>" class="odd gradeX" <?php echo $color;?>>
                                            <td align="center"><?php echo $data->PARTIDA;?> <br/></td>
                                            <td><?php echo $data->ART?></td>
                                            <td><?php echo $data->DESCRIPCION?></td>
                                            <td><?php echo $data->CANTIDAD ;?></td>
                                            <td><?php echo $data->PXR?></td>
                                            <td align="right"><?php echo '$ '.number_format($data->COSTO,2);?></td>
                                            <td align="right"><?php echo '$ '.number_format($data->COSTO * $data->CANTIDAD,2);?></td>
                                            <td align="right"><?php echo '$ '.number_format(($data->COSTO *$data->CANTIDAD ) * .16,2);?></td>  
                                            <td align="right"><?php echo '$ '.number_format( ($data->COSTO * $data->CANTIDAD)*1.16,2)?></td>      
                                            <td><?php echo $data->UM?></td>
                                            <td><?php echo $data->CVE_PROD?></td>
                                            <td><a href="index.php?action=historiaIDPREOC&id=<?php echo $data->IDPREOC?>" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;"> <?php echo $data->IDPREOC?></a></td>
                                            <form action="index.php" method="post">
                                            <td><?php echo $data->CANT_RECIBIDA?></td>
                                             <td>
                                                <input type="number" name="precioLista" max="99999999" required="required" min="0" onkeypress="return pulsar(event)" onchange="actPrecio(this.value, <?php echo $data->PARTIDA?>)">
                                            </td> 
                                            <td><input type="number" name="desc1" min="0" max="100" value="0"></td>
                                            <td><input type="number" name="desc2" min="0" max="100" value="0"></td>
                                            <td><input type="number" name="desc3" min="0" max="100" value="0"></td>
                                            <td><input type="number" name="desc4" min="0" max="100" value="0"></td>
                                            <td>
                                                <input type="number" name="totalCosto" max="99999999" min="0" disabled="disabled" >
                                            </td>
                                            <td>
                                                <input type="hidden" name="idp" value="<?php echo $data->IDPREOC?>" >
                                                <input type="hidden" name="doco" value="<?php echo $data->CVE_DOC?>" >
                                                <input type="hidden" name="partida" value="<?php echo $data->PARTIDA?>" >
                                                <button type="submit" name="recibeParOC" value="enviar"> Recibir </button>
                                            </td> 
                                            
                                            </form>                                    
                                        </tr>
                                        <?php endforeach; ?>
                                 </tbody>
                                 <tfoot>
                                     <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td align="right">SubTotal</td>
                                            <td align="right"><?php echo '$ '.number_format($subtotal,2)?></td>
                                     </tr>
                                     <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td align="right">IVA</td>
                                            <td align="right"><?php echo '$ '.number_format($subtotal*.16,2)?></td>
                                     </tr>
                                     <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td align="right">Total</td>
                                            <td align="right"><?php echo '$ '.number_format($subtotal*1.16,2)?></td>
                                     </tr>
                                 </tfoot>
                                </table>
                            </div>
                      </div>
            </div>
        </div>
    </div>
<br />

<div>
    <form action="index.php" method="post">
    <input type="hidden" name="doco" value="<?php echo $doco?>">
    <label <?php echo ($cierre==0)? "title='Se generara la Recepcion'":"title='El boton se habilitara hasta que todo tenga valor en la columna recibido.'"?> >
     <button name="cerrarRecepcion" value="enviar" type="submit" id="val" <?php echo ($cierre == 0)? '':"disabled"?> > Cerrar Recepcion </button>
     <button name="cancelaRecepcion" value="enviar" type="submit" > Cancelar </button>
     </label>
    </form>
</div>

<script type="text/javascript">

    function actPrecio(me, id){
        alert('id que envia'+ me + 'Partida' + id);    
        //document.getElementById('')
    } 


    function validador(){
        document.getElementById('val').classList.add('hide');
    }

    function rechazar(ids, desc){
        //var recWindow = window.open("index.php?action=verSolProdVentas","Mensaje","width=200,height=100")
        //recWindow.document.write("<p> Esta es la ventana</p>")
        var id = ids;
        alert('Se rechazara la Solicitud de alta del Producto :' + desc );
        window.open('index.php?action=rechazarSol&ids='+ids,"","width=800,height=800")
    }

    function pulsar(e) { 
        tecla = (document.all) ? e.keyCode :e.which; 
         return (tecla!=13); 
    } 



</script>
