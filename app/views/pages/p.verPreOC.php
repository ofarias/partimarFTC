<br/>
<br/>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Lista de Solicitudes de Productos de Ventas.
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-aplicapago">
                                    <thead>
                                        <tr>
                                            <th>Folio</th>
                                            <th>Clave / Nombre </th>
                                            <th>Usuario</th>
                                            <th>Costo <br/> Bruto </th>
                                            <th>Valor IVA</th>
                                            <th>Costo Neto</th>
                                            <th></th>
                                            <th>Status Actual </th>
                                            <th>Contiene <br/> Urgencia</th>
                                            <th>Imprimir</th>
                                            <th>Ver / Editar</th>
                                            <th>Cancelar</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        foreach ($preoc as $data):
                                            $ids=$data->ID;
                                            ?>
                                        <tr>
                                            <td><?php echo $data->CVE_DOC;?></td>
                                            <td><?php echo $data->CVE_PROV. '/ '.$data->NOMBRE;?></td>
                                            <td><?php echo $data->USUARIO ;?></td>
                                            <td><?php echo '$ '.number_format($data->COSTO,2);?></td>
                                            <td><?php echo '$ '.number_format($data->TOTAL_IVA,2);?></td>
                                            <td><?php echo '$ '.number_format($data->COSTO_TOTAL,2);?></td>
                                            <td></td>
                                            <td><?php echo $data->STATUS?></td>
                                            <td><?php echo $data->URGENCIA?></td>
                                            <td>
                                                <form action="index.php" method="post">
                                                    <input name="idpoc" type="hidden" value="<?php echo $data->CVE_DOC?>"/>
                                                    <button name="impPOC" type="submit" value="enviar" class="btn btn-info"> Imprimir</button>
                                                    <br/>
                                                </form>   
                                                <form action="index.php" method="post">
                                                    <input type="hidden" name="preoc" value ="$data->ID"/>
                                                    <input type="email" name="correoProv" value ="<?php echo $data->CORREO?>" required = "required"/>
                                                    <button name="correo" class="btn btn-success"> Enviar</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="index.php" method="post">
                                                    <input type="hidden" name="doco" value="<?php echo $data->CVE_DOC?>" />
                                                    <button name="confirmarPreOC" type="submit" value="enviar" class="btn btn-info">
                                                       <?php echo ($data->STATUS == 'A')? '':'Confirmar'?>    
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <input class="btn btn-danger" name="verSol" onClick="rechazar(<?php echo $data->ID?>, desc.value)" value="Cancelar">
                                                <!--<button name="rechazarSol" value="enviar" type = "submit" class="btn btn-danger">Rechazar</button>-->
                                            </td> 
                                               
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
    
    function rechazar(ids, desc){
        //var recWindow = window.open("index.php?action=verSolProdVentas","Mensaje","width=200,height=100")
        //recWindow.document.write("<p> Esta es la ventana</p>")
        var id = ids;
        alert('Se rechazara la Solicitud de alta del Producto :' + desc );
        window.open('index.php?action=rechazarSol&ids='+ids,"","width=800,height=800")
    }

</script>
