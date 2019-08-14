<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                Revision de Facturacion.
                        </div>
                        <!-- /.panel-heading -->
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables-verFacturas">
                                    <thead>
                                        <tr>
                                            <!--<th>Todos: <input type="checkbox" name="marcarTodo" id="marcarTodo" /></th>-->
                                            <th>CLAVE / NOMBRE</th>
                                            <th>CERTIFICADO</th>
                                            <th>DIRECCION</th>
                                            <th>ENVIO/RECOLECCION</th>
                                            <th>TIPOS PAGO</th>
                                            <th>RESPONSABLE COMPRA</th>
                                            <th>FECHA CERTIFICACION</th>
                                            <th>FALLOS</th>
                                            <th>ULTIMO FALLO</th>
                                            <th>EDITAR</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($proveedores as $data):
                                            if($data->ENVIO == 1){
                                                $envio = 'Si';
                                            }else{
                                                $envio = 'No';
                                            }                 
                                            if($data->RECOLECCION == 1){
                                                $rec = 'Si';
                                            }else{
                                                $rec = 'No';
                                            }
                                            if($data->TP_CHEQUE == 'Si'){
                                                $tch = ' Cheque';
                                            }else{
                                                $tch ='';
                                            }
                                            if($data->TP_EFECTIVO == 'Si'){
                                                $tef =' Efectivo';
                                            }else{
                                                $tef = '';
                                            }
                                            if($data->TP_CREDITO == 'Si'){
                                                $tcr = ' Credito';
                                            }else{
                                                $tcr = '';
                                            }
                                            if($data->TP_TRANSFERENCIA == 'Si'){
                                                $ttr = ' Transferencia';
                                            }else{
                                                $ttr = '';
                                            }
                                        ?>
                                       <tr>
                                            <td><?php echo '('.$data->CLAVE.')'.$data->NOMBRE;?></td>
                                            <td><?php echo $data->CERTIFICADO?></td>
                                            <td><?php echo $data->CALLE.' '.$data->NUMEXT.' '.$data->COLONIA.' '.$data->ESTADO;?></td>
                                            <td><?php echo $envio.' / '.$rec;?></td>
                                            <td><?php echo $tch.'/'.$tef.' / '.$tcr.'/'.$ttr;?></td>
                                            <td><?php echo $data->RESP_COMPRA;?></td>
                                            <td><?php echo $data->FECHA_CERT;?></td>
                                            <td><?php echo $data->FALLOS?></td>
                                            <td><?php echo $data->FECHA_ULT_FALLO?></td>
                                            <form action="index.php" method="post">
                                            <input type="hidden" name="idprov" value="<?php echo $data->CLAVE;?>"/>
                                            <td>
                                            <button name="editaProveedor" type="submit" value="enviar " class= "btn btn-warning"> 
                                            Editar Datos    
                                            </button>
                                             </td> 
                                            </form>
                                        </tr> 
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                            <!-- /.table-responsive -->
                      </div>
            </div>
        </div>
    </div>
</div>
