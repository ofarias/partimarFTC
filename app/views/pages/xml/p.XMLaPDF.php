<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-facturasxml">
                                    <thead>
                                        <tr>
                                            <th>UUID</th>
                                            <th>TIPO</th>
                                            <th>FOLIO</th>
                                            <th>FECHA</th>
                                            <th>CLIENTE</th>
                                            <th>SUBTOTAL</th>
                                            <th>IVA</th>
                                            <th>RETENCION <br/>IVA</th>
                                            <th>ISR</th>
                                            <th>RETENCION <br/>ISR</th>
                                            <th>TOTAL</th>
                                            <th>Descargar</th>                                            
                                       </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php foreach ($info as $key): 
                                            $color='';
                                            if($key->TIPO == 'I'){
                                                $tipo = 'Ingreso';
                                                $color =  'style="background-color:orange"';
                                            }elseif ($key->TIPO =='E') {
                                                $tipo = 'Egreso';
                                                $color = 'style="background-color:yellow"';
                                            }
                                            $file = substr($key->FILE,36,100);
                                        ?>
                                        <tr class="odd gradeX" <?php echo $color ?> >
                                         <!--<tr class="odd gradeX" style='background-color:yellow;' >-->
                                            <td> <?php echo $key->UUID ?> </td>
                                            <td><?php echo $tipo?></td>
                                            <td><?php echo $key->SERIE.$key->FOLIO?><br/> <?php echo $key->PREFACT?></td>
                                            <td><?php echo $key->FECHA;?> </td>
                                            <td><?php echo '('.$key->CLIENTE.')  '.$key->NOMBRE;?></td>
                                            <td><?php echo '$ '.number_format($key->SUBTOTAL,2);?></td>
                                            <td><?php echo '$ '.number_format($key->IVA1,2);?></td>
                                            <td><?php echo '$ '.number_format($key->RETIVA,2);?></td>
                                            <td><?php echo '$ '.number_format($key->ISR,2);?></td>
                                            <td><?php echo '$ '.number_format($key->RETISR,2);?></td>
                                            <td><?php echo '$ '.number_format($key->IMPORTE,2);?> </td>
                                            <td><a href="/Facturas/facturaPegaso/<?php echo $key->SERIE.$key->FOLIO.'.xml'?>" download>  <img border='0' src='app/views/images/xml.jpg' width='25' height='30'></a>
                                            <form action="index.php" method="POST">
                                                <input type="hidden" name="factura" value="<?php echo $key->SERIE.$key->FOLIO?>">
                                                <button name="imprimeFact" value="enviar" type="submit"><img border='0' src='app/views/images/pdf.jpg' width='25' height='30'></button>
                                            </form>
                                            <a href="index.php?action=addenda">addenda</a>
                                            </td>
                                        </tr>
                                        </form>
                                        <?php endforeach; ?>
                                 </tbody>  
                                </table>
                            </div>
                      </div>
            </div>
        </div>
</div>