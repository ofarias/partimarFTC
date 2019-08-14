<br/>
<br/>
<p><?php echo "Usuario <b>".$usuario."</b>"?></p>
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
                                            <th>Ln</th>
                                            <th>Tipo</th>
                                            <th>Docuemnto </th>
                                            <th>Importe</th>
                                            <th>Fecha <br/> Elaboracion</th>
                                            <th>Proveedor</th>
                                            <th>Enlazado</th>
                                            <th>Procesar</th>
                                            <?php if($usuario == 'XML test'){?>
                                            <th>Recosteo</th>
                                            <th>Detalle de Costeo</th>
                                            <th>Otra Moneria</th>
                                            <?php }?>  

                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php
                                            $LN=0; 
                                        foreach ($compras as $data): 
                                            $LN++;
                                            $color='';
                                            if(!empty($data->DOC_SIG)){
                                                $color = "style=background-color:#85c1e9";
                                            }
                                            ?>
                                        <tr <?php echo $color?> title="<?php echo 'Compra: '.$data->DOC_SIG?>">
                                            <td><?php echo $LN;?></td>
                                            <td><?php echo $data->TIP_DOC;?></td>
                                            <td><?php echo $data->CVE_DOC;?></td>
                                            <td align="right"><?php echo '$ '.number_format($data->IMPORTE,2);?></td>
                                            <td><?php echo $data->FECHAELAB;?></td>
                                            <td><?php echo '('.$data->CVE_CLPV.')'.$data->NOMBRE;?></td>
                                            <td align="center"><?php echo $data->ENLAZADO;?></td>
                                            <td>
                                            <form action="index.php" method="post">
                                                <input name="docr" type="hidden" value="<?php echo $data->CVE_DOC?>"/>
                                                <input type="hidden" name="tipo" value="<?php echo $data->TIP_DOC?>">
                                                <button name="costeoRecepcion" value = "enviar" type="submit" class="btn btn-danger">Capturar Costos</button>
                                            </form>
                                            </td>
                                            <?php if($usuario == 'XML test'){?>
                                                <td><a onclick="recosteo(<?php echo$LN?>,'<?php echo $data->CVE_DOC?>')">Recosteo</a></td>
                                                <td><a>Detalle</a></td>
                                                <td><a>Monerias</a></td>
                                            <?php }?>
                                                  
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
<br />


<script type="text/javascript">
        function recosteo(ln, doco){
            alert('Se intentara recostear los productos del documento: ' + doco);
            $.ajax({
                url:'index.php',
                type:'post',
                dataType:'json',
                data:{recostearInt:doco},
                success:function(data){
                    alert('Favor de revisar el resultado en Detalle');
                }
            })

        }


</script>