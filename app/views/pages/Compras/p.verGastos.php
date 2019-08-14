<br/>
<br/>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Lista de Solicitudes pendientes de Impresión.
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-aplicapago">
                                    <thead>
                                        <tr>
                                            <th>No Documento</th>
                                            <th>Tipo Gasto</th>
                                            <th>CxP</th>
                                            <th>Proveedor</th>
                                            <th>Fecha</th>
                                            <th>Importe</th>
                                            <th>Moneda</th>
                                            <th>Tipo de Cambio</th>
                                            <th>Status</th>
                                            <th>Ver Documentos</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        $total = 0;
                                        foreach ($info as $data):
                                            $status = '';
                                            if($data->STATUS == 0){
                                                $status = 'Nuevo';
                                            }elseif($data->STATUS == 1){
                                                $status = 'Revisado CxP';
                                            }elseif($data->STATUS  == 2){
                                                $status = 'Rechazado por CxP';
                                            }elseif($data->STATUS == 3){
                                                $status = 'Programado por CxP';
                                            }elseif($data->STATUS == 4){
                                                $status = 'Pagado';
                                            }elseif($data->STAUTS = 8){
                                                $status = 'Cancelado';
                                            }
                                            
                                            $total = $total + ($data->COSTO * $data->TC);                                        
                                        ?>
                                         <tr>
                                            <td><?php echo $data->DOCUMENTO;?></td>
                                            <td><?php echo $data->TIPO?></td>
                                            <td><?php echo $data->CXP==1? 'Si':'No'?></td>
                                            <td><?php echo '('.$data->CLAVE.')'.$data->PROVEEDOR;?></td>
                                            <td><?php echo $data->FECHA;?></td>
                                            <td align="right"><?php echo '<font color="blue"> $ '.number_format($data->COSTO * $data->TC,2).'</font>';?><br/><?php echo ($data->MONEDA <> 'MNX')? "<font color='red'>".number_format($data->COSTO,2).$data->MONEDA."</font>":'' ?></td>
                                            <td><?php echo $data->MONEDA;?></td>
                                            <td><?php echo $data->TC;?></td>
                                            <td><?php echo $status;?></td>
                                            <td> </td>
                                            <td>
                                            <form action="index.php" method="post">
                                                <button name="elimimarGasto" value = "enviar" type="submit" class="btn btn-danger"> Eliminar</button>
                                                <input name="doco" type="hidden" value="<?php echo $data->DOCUMENTO?>"/>
                                                <input type="hidden" name="idg" value="<?php echo $data->ID?>">
                                            </form>      
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>

                                 </tbody>
                                 <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><font size="4pxs"><?php echo '<font color="blue">$ '.number_format($total,2).'</font>'?></font></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                </table>
                                    <br/>
                                    <button class="btn btn-warning" onclick="actualizar()">Actualiza</button>
                                    <br/>
                            </div>
                      </div>
            </div>
        </div>
</div>
<br />
<script type="text/javascript">
    function actualizar(){
        location.reload(true);
    }

</script>