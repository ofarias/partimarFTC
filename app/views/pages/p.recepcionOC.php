<br/>
<br/>
<div class="row">
                <div class="col-lg-12">
                <div>
                <label> Impresion de Recepciones</label>
                <a class="btn btn-success" href="index.php?action=impresionRecepcion" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;" > Imprimir Recepcion</a>
                </div> 
                <br/>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Lista de Solicitudes de Productos de Ventas.
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-aplicapago">
                                    <thead>
                                        <tr>
                                            <th>Orde de <br/> compra</th>
                                            <th>Clave / Nombre </th>
                                            <th>Usuario</th>
                                            <th>Cantidad Solicitada <br/> Cantidad Pendiente</th>
                                            <th>Costo <br/> Bruto </th>
                                            <th>Valor IVA</th>
                                            <th>Costo Neto</th>
                                            <th>Contiene <br/> Urgencia</th>
                                            <th>Recibir <br/> Mercancia</th>
                                            <th>Rechazar <br/> Orden de Compra</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        foreach ($ordenes as $data):

                                            ?>
                                        <tr>
                                            <td><?php echo $data->CVE_DOC;?></td>
                                            <td><?php echo $data->CVE_CLPV. '/ '.$data->NOMBRE;?></td>
                                            <td><?php echo $data->USUARIO ;?></td>
                                            <td><?php echo $data->CANTIDAD.' / '.$data->PXR?></td>
                                            <td><?php echo '$ '.number_format($data->COSTO,2);?></td>
                                            <td><?php echo '$ '.number_format($data->COSTO *.16,2);?></td>
                                            <td><?php echo '$ '.number_format($data->COSTO * 1.16,2);?></td>
                                            <td><?php echo $data->URGENCIA?></td>
                                            <td>
                                                <form action="index.php" method="post">
                                                    <input name="doco" type="hidden" value="<?php echo $data->CVE_DOC?>"/>
                                                    <button name="recibirOC" type="submit" value="enviar" <?php echo $data->STATUS_RECEPCION==9? 'class="btn btn-danger"':'class="btn btn-info"'?>  <?php echo ($data->STATUS_RECEPCION ==9)? 'disabled=disabled':''?> > <?php echo ($data->STATUS_RECEPCION == 9)? 'EN USO ':'Recibir Mercancia'?></button>
                                                    <br/>
                                                </form>   
                                            </td>
                                            <td>
                                                <form action="index.php" method="post">
                                                    <input type="hidden" name="doco" value="<?php echo $data->CVE_DOC?>" />
                                                    <button name="rechazarOC" type="submit" value="enviar" class="btn btn-danger"  <?php echo ($data->STATUS_RECEPCION ==9)? 'disabled=disabled':''?> >
                                                        Rechazar OC
                                                    </button>
                                                </form>
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
