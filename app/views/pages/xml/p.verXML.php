<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
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
                                            <th>CLASIFICAR</th>                                            
                                       </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php foreach ($infoCabecera as $key): 
                                            $color='';
                                            if($key->TIPO == 'I'){
                                                $tipo = 'Ingreso';
                                                $color =  'style="background-color:orange"';
                                            }elseif ($key->TIPO =='E') {
                                                $tipo = 'Egreso';
                                                $color = 'style="background-color:yellow"';
                                            }
                                        ?>
                                        <tr class="odd gradeX" <?php echo $color ?> >
                                         <!--<tr class="odd gradeX" style='background-color:yellow;' >-->
                                            <td> <?php echo $key->UUID ?> </td>
                                            <td><?php echo $tipo?></td>
                                            <td><?php echo $key->SERIE.$key->FOLIO?></td>
                                            <td><?php echo $key->FECHA;?> </td>
                                            <td><?php echo '('.$key->CLIENTE.')  '.$key->NOMBRE;?></td>
                                            <td><?php echo '$ '.number_format($key->SUBTOTAL,2);?></td>
                                            <td><?php echo '$ '.number_format($key->IVA1,2);?></td>
                                            <td><?php echo '$ '.number_format($key->RETIVA,2);?></td>
                                            <td><?php echo '$ '.number_format($key->ISR,2);?></td>
                                            <td><?php echo '$ '.number_format($key->RETISR,2);?></td>
                                            <td><?php echo '$ '.number_format($key->IMPORTE,2);?> </td>
                                            <td> <select>
                                                    <option>Cuentas COI </option>
                                                </select>
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

<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Documento</th>
                                            <th>Partida</th>
                                            <th>Unidad</th>
                                            <th>Cantidad</th>
                                            <th>Descripcion</th>
                                            <th>Unitario</th>
                                            <th>Importe</th>
                                            <th>Clave SAT</th>
                                            <th>Unidad SAT</th>                                            
                                       </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php foreach ($info as $key): 
                                            $color='';
                                        ?>
                                        <tr class="odd gradeX" <?php echo $color ?> >
                                         <!--<tr class="odd gradeX" style='background-color:yellow;' >-->
                                            <td> <?php echo $key->DOCUMENTO ?> </td>
                                            <td><?php echo $key->PARTIDA?></td>
                                            <td><?php echo $key->UNIDAD;?> </td>
                                            <td><?php echo $key->CANTIDAD;?></td>
                                            <td><?php echo $key->DESCRIPCION?></td>
                                            <td><?php echo '$ '.number_format($key->UNITARIO,6);?></td>
                                            <td><?php echo '$ '.number_format($key->IMPORTE,6);?> </td>
                                            <td><?php echo $key->CLAVE_SAT.'<br/><font color ="blue" size="1.5pxs">'.$key->DESCSAT.'</font>'?></td>
                                            <td><?php echo $key->UNIDAD_SAT?></td>
                                            <td>
                                                <select>
                                                    <option>Cuentas COI </option>
                                                </select>
                                            </td>
                                        </tr>
                                      
                                        <?php endforeach; ?>
                                 </tbody>
                                </table>
                            </div>
                      </div>
            </div>
        </div>
</div>
