<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Informacion General del Producto <?php echo $id?>
                        </div>
                        <!-- /.panel-heading -->
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Descripcion</th>
                                            <th> Cotizacion </th>
                                            <th> Status </th>
                                            <th> Fecha Cotizacion </th>
                                            <th> Cantidad <br/> del Pedido</th>
                                            <th> Cantidad <br/> en OC </th>
                                            <th> Cantidad <br/> por solicitar</th>
                                            <th> Cantidad <br/> Recepcionada </th>
                                            <th> Pendiente de <br/> Recepcionar </th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($historico as $data):  
                                        ?>
                                       <tr>
                                            <td>
                                                 <?php echo $data->NOMPROD;?>
                                            </td>
                                            <td><?php echo $data->COTIZA;?></td>
                                            <td><?php echo $data->STATUS?></td>
                                            <td><?php echo $data->FECHASOL;?></td>
                                            <td align="center"><?php echo ($data->CANT_ORIG);?></td>
                                            <td><?php echo $data->ORDENADO ;?></td>
                                            <td><?php echo $data->REST?></td>
                                            <td><?php echo $data->RECEPCION?></td>
                                            <td><?php echo $data->REC_FALTANTE;?></td>
                                        
                                        </tr>
                                            
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                      </div>
            </div>
        </div>
    </div>
</div>


<?php if(count($ordenes)>0){?>
<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Ordenes de compra del id <?php echo $id?>
                        </div>
                        <!-- /.panel-heading -->
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Orden de <br/> Compra</th>
                                            <th>Partida</th>
                                            <th>ID</th>
                                            <th>Status</th>
                                            <th>Cantidad</th>
                                            <th>Pendientes <br/> de Validar</th>
                                            <th>Descripcion</th>
                                            
                                            <!--<th>Cant</th>-->
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($ordenes as $data):
                                                $status  = 'OK';
                                                if($data->STATUS == 'L' or $data->STATUS == 'D'){
                                                        $status = 'Liberado por Suministros';
                                                }    
                                        ?>
                                       <tr class="odd gradeX" style='background-color:#F5A9A9;' >
                                            <td align="center"><?php echo $data->CVE_DOC;?></td>
                                            <td align="center"><?php echo $data->NUM_PAR;?></td>
                                            <td align="center"><?php echo $data->ID_PREOC?></td>
                                            <td><?php echo $status?></td>
                                            <td><?php echo $data->CANT?></td>
                                            <td align="center"><?php echo $data->PXR;?></td>
                                            <td><?php echo empty($data->NOMBREFTC)? $data->NOMBREINVE:$data->NOMBREFTC?></td>
                                            
                                        </tr>
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                      </div>
            </div>
        </div>
    </div>
</div>

<?php }?>

<?php if(count($recepciones)>0){?>

<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Partidas Recepcionadas de la Orden de compra <?php echo $doco?>
                        </div>
                        <!-- /.panel-heading -->
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Recepcion de <br/> Compra</th>
                                            <th>Partida Recepcion</th>
                                            <th>ID Producto</th>
                                            <th>Descripcion</th>
                                            <th>Cantidad</th>
                                            <th>Importe</th>
                                            <!--<th>Cant</th>-->
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($recepciones as $data):  
                                        ?>
                                       <tr class="odd gradeX" style='background-color:#CED8F6;'>
                                            <td align="center"><?php echo $data->CVE_DOC;?></td>
                                            <td align="center"><?php echo $data->NUM_PAR;?></td>
                                            <td align="center"><?php echo $data->ID_PREOC?></td>
                                            <td><?php echo empty($data->NOMBREFTC)? $data->NOMBREINVE:$data->NOMBREFTC?></td>
                                            <td align="center"><?php echo $data->CANT;?></td>
                                            <td align="center"><?php echo '$ '.number_format($data->TOT_PARTIDA,2);?></td>   
                                        </tr>
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                      </div>
            </div>
        </div>
    </div>
</div>

<?php }?>

<?php if(count($validaciones)>0){?>
<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Validaciones.
                        </div>
                        <!-- /.panel-heading -->
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Orden de Compra</th>
                                            <th>Recepcion <br/> Validada </th>
                                            <th>Partida Orden de compra</th>
                                            <th>ID Producto</th>
                                            <th>Descripcion</th>
                                           
                                            <th>Cantidad Validada <br/> De la Recepcion </th>
                                            <th>Cantidad Acumulada <br/> Total Validado </th>
                                            <th>Costo Validado x Unidad</th>
                                            <!--<th>Cant</th>-->
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($validaciones as $data):  

                                            $color = "style='background-color:#CEF6D8;'";
                                            if($data->CANT_OC > $data->CANT_VALIDADA){
                                                $color = "style='background-color:#F5D0A9'";
                                            }
                                        ?>
                                       <tr class="odd gradeX" <?php echo $color?>>
                                            <td align="center"><?php echo $data->DOCUMENTO;?></td>
                                            <td align="center"><?php echo empty($data->DOC_RECEPCION)? 'FALLIDO':$data->DOC_RECEPCION; ?></td>
                                            <td align="center"><?php echo $data->PARTIDA;?></td>
                                            <td align="center"><?php echo $data->ID_PREOC?></td>
                                            <td><?php echo empty($data->NOMBREFTC)? $data->NOMBREINVE:$data->NOMBREFTC?></td>
                                            
                                            <td align="center"><?php echo $data->CANT_VALIDADA;?></td>
                                            <td align="center"><?php echo $data->TOTALVALIDACIONES?></td>
                                            <td align="center"><?php echo '$ '.number_format($data->COSTO_VALIDADO,2);?></td>
                                               
                                        </tr>
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                      </div>
            </div>
        </div>
    </div>
</div>
<?php }?>
