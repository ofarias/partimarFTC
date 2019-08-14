<br /><br />

<label>Usuario: <?php echo $usuario?></label>

<!--<p><a class="btn-warning" href="index.php?action=verPedidos&tipo=p" target="popup">Rango de Fechas</a></p>-->
<p><a class="btn-warning" href="index.php?action=verPedidos&tipo=30" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;">Ver 30 dias</a></p>
<p><a class="btn-warning" href="index.php?action=verPedidos&tipo=p" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;">Ver Pendientes</a></p>

<?php   
$a='';
$o='';
        if($tipoUsuario=='alma'){
            $a='hidden';
        }else{
            $o='hidden';
        }
?>


<div class="row todos <?php echo $a?>" >
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Remisiones pendientes de Facturar.
                        </div>
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables-oc">
                                    <thead>
                                        <tr>
                                            <th>Pedido <br/>Observaciones</th>
                                            <th>Cliente</th>
                                            <th>Fecha <br/>Elaboracion</th>
                                            <th>Importe</th>
                                            <th>Status <br/> Doc</th>
                                            <th>Dias <br/>Atraso</th>
                                            <th>Status <br/> Almacen</th>
                                            <th>Factura </th>
                                            <th>Tipo de <br/> Enlace </th>
                                            <th>Pendientes <br/> De Facturar</th>
                                            <th>Liberado por <br/> Fecha Liberacion </th>
                                            <th>Ejecutar</th>
                                            <?php if($tipoUsuario == 'alma'){?>
                                            <th>Preparar</th>
                                            <?php }?>
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($pedidos as $data): 
                                        $color = ''; 
                                        $tds = '';
                                        $te = '';
                                        $status_a= '';
                                        $statusSig='';
                                        $obs='Sin Observaciones';
                                        if (empty($data->STATUS_ALMACEN)){
                                            $color = "style='background-color: #ffffcc;'";    
                                            $status_a = 'En Espaera de CxC';
                                            $statusSig = 'Liberar';
                                        }elseif($data->STATUS_ALMACEN == 'Liberado'){
                                            $color = "style='background-color: #A9F5BC;'";    
                                            $status_a = 'Liberado';
                                            $statusSig = 'Recibir';
                                        }elseif($data->STATUS_ALMACEN == 'Facturado'){
                                            $color = "style='background-color: #A9F5BC;'";    
                                            $status_a = 'Facturado';
                                            $statusSig = 'Facturado';
                                        }elseif ($data->STATUS_ALMACEN == 'EN PROCESO') {
                                            $color = "style='background-color: #A9F5BC;'";    
                                            $status_a = 'En Proceso';
                                            $statusSig = 'En Proceso';
                                        }elseif($data->STATUS_ALMACEN == 'LISTO'){
                                            $color = "style='background-color: #A9F5BC;'";    
                                            $status_a = 'Listo Almacen';
                                            $statusSig = 'Listo Almacen';
                                        }elseif($data->STATUS_ALMACEN == 'Rechazado'){
                                            $color = "style='background-color: #b3b3ff;'";    
                                            $status_a = 'Rechazado';
                                            $statusSig = 'Rechazado';
                                        }

                                        if($data->TIP_DOC_SIG == 'R'){
                                            $tds = 'Remision';
                                            $color = "style='background-color:#b3e6ff;'";
                                        }elseif($data->TIP_DOC_SIG == 'F'){
                                            $tds = 'Factura';
                                            $color = "style='background-color: #b3e6ff;'";
                                        }
                                        if($data->ENLAZADO == 'T'){
                                            $te = 'Todo';
                                        }elseif($data->ENLAZADO = 'P'){
                                            $te = 'Parcial';
                                        }elseif($data->ENLAZADO = 'O' or empty($data->ENLAZADO)){
                                            $te = 'Sin Enlace';
                                        }

                                        if(!empty($data->COMENTARIOS)){
                                            $obs = $data->COMENTARIOS;
                                            $obs = str_replace(',', '&#10;', $obs);
                                        }

                                        if($obs!='Sin Observaciones' and !empty($data->RECHAZO)){
                                            $obs .= ', Motivo del Rechazo: '.$data->RECHAZO;
                                        }elseif($obs=='Sin Observaciones' and !empty($data->RECHAZO)){
                                            $obs = ', Motivo del Rechazo: '.$data->RECHAZO;
                                        }

                                        if($data->STATUS == 'C'){
                                            $color ="style='background-color:#ff9999'";
                                        }
                                        ?>
                                       <tr class="odd gradeX" <?php echo $color;?> title='<?php echo $obs?>'>           
                                            <td><?php echo $data->CVE_DOC;?><br/>
                                            <?php if(!empty($data->CVE_OBS)){?>
                                            <a href="index.php?action=verObservaciones&obs=<?php echo $data->CVE_OBS?>&doc=<?php echo $data->CVE_DOC?>" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;"><font color='blue'>Observaciones</font></a>
                                                <br/><b><?php echo 'Credito: '.$data->DIASCRED?></b>
                                            <?php }?>
                                            </td>
                                            <td><?php echo '('.$data->CVE_CLPV.')'.$data->NOMBRE;?><br/>
                                                <?php if($data->ARCHIVOS > 0){?>
                                                    <a href="index.php?action=verArchivos&tipo=<?php echo $data->TIP_DOC?>&doc=<?php echo $data->CVE_DOC?>" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;"> <font color='red'><i class="fa fa-file" ></i> <?php echo $data->ARCHIVOS?></font></a>

                                                <?php }?>
                                                <?php if($data->FP == 1){?>
                                                    <a href="/DocRemision/<?php echo $data->NOMBRE_FP?>" download="/DocRemision/<?php echo ($data->NOMBRE_FP)?>"> <font color='green'><i class="fa fa-money fa-2x" ></i></font></a>
                                                <?php }elseif($data->FP > 1){?>
                                                    <a href="/DocRemision/<?php echo $data->NOMBRE_FP?>" download="/DocRemision/<?php echo ($data->NOMBRE_FP)?>"> <font color='green'><i class="fa fa-money fa-2x" ></i></font></a>
                                                    <a href="index.php?action=verArchivos&tipo=<?php echo $data->TIP_DOC?>&doc=<?php echo $data->CVE_DOC?>" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;"> <font color='blue' size="4.5 pxs"><?php echo '('.$data->FP.')'?></font></a>                                                   
                                                <?php }?>    
                                            <?php if($tipoUsuario == 'venta'){?>
                                                <form action="uploadOC.php" method="post" enctype="multipart/form-data">
                                                <input type="file" name="fileToUpload" id="fileToUpload" required="required">
                                                <input type="hidden" name="doc" value="<?php echo $data->CVE_DOC?>">
                                                <input type="hidden" name="tipodoc" value="<?php echo $data->TIP_DOC?>">
                                                <input type="submit" value="Subir Documento" name="submit">
                                                </form>
                                            <?php }?>
                                            <input type="text" name="obs" value="<?php echo (!empty($data->OBS))? $data->OBS:''?>" placeholder="Observaciones Documento" maxlength="80" size="80" onchange="observaciones(this.value, '<?php echo $data->CVE_DOC?>')">
                                            </td>
                                            <td><?php echo substr($data->FECHAELAB,0,10).'<br/>'.substr($data->FECHAELAB,11,10);?></td>
                                            <td><?php echo '$ '.number_format($data->IMPORTE,2);?></td>
                                            <td><?php echo $data->STATUS;?></td>
                                            <td><?php echo $data->DIAS;?></td>
                                            <td><?php echo $status_a;?></td>
                                            <td><?php echo $data->DOC_SIG.'<br/>'.$tds;?></td>
                                            <td><?php echo $te;?></td>
                                            <td><?php echo $data->PXS;?></td>
                                            <td><?php echo $data->USUARIO_LIBERA.'<br/>'.$data->FECHA_LIBERA?></td>
                                            <td>
                                                <input id="l_<?php echo $data->CVE_DOC?>" type="checkbox" name="doc" value="Autorizar" <?php echo ($statusSig=='Liberar' or $statusSig == 'Rechazado')? '':'checked disabled' ?> documento="<?php echo $data->CVE_DOC?>"  class="liberar" usuario="<?php echo $usuario?>" tipo="l"/><?php echo $statusSig =='Rechazado'? 'Liberar':$statusSig ?>
                                                <br/>
                                            <?php if($statusSig=='Liberar' or $statusSig == 'Rechazado'){?>
                                                <input id="r_<?php echo $data->CVE_DOC?>" type="checkbox" name="doc" value="Rechazar" <?php echo ($statusSig=='Liberar' or $statusSig == 'Rechazado')? '':'checked disabled' ?> documento="<?php echo $data->CVE_DOC?>"  class="liberar" usuario="<?php echo $usuario?>" tipo="r"/>Rechazar
                                                <?php }?>
                                            </td>
                                            <?php if($tipoUsuario == 'alma'){?>
                                                <td>
                                                    <input type="button" class="btn-small btn-info preparar" info="<?php echo $data->CVE_DOC?>" value="Peparar"><br/>
                                                    <input type="button" class="btn-small btn-warning imprime" info="<?php echo $data->CVE_DOC?>" value="Imprimir">
                                                </td>
                                            <?php }?>
                                            </tr>
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                      </div>
            </div>
        </div>
</div>
</div>

<div class="row <?php echo $o?> " >
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Remisiones Autorizadas Pendientes de Preparar.
                        </div>
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables-oc">
                                    <thead>
                                        <tr>
                                            <th>Pedido <br/>Observaciones</th>
                                            <th>Cliente</th>
                                            <th>Fecha <br/>Elaboracion</th>
                                            <th>Importe</th>
                                            <th>Status <br/> Doc</th>
                                            <th>Dias <br/>Atraso</th>
                                            <th>Status <br/> Almacen</th>
                                            <th>Factura </th>
                                            <th>Tipo de <br/> Enlace </th>
                                            <th>Pendientes <br/> De Facturar</th>
                                            <th>Liberado por <br/> Fecha Liberacion </th>
                                            <th>Ejecutar</th>
                                            <?php if($tipoUsuario == 'alma'){?>
                                            <th>Preparar</th>
                                            <?php }?>
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($pedidos as $data): 
                                        $color = ''; 
                                        $tds = '';
                                        $te = '';
                                        $status_a= '';
                                        $statusSig='';
                                        $obs='Sin Observaciones';
                                        if (empty($data->STATUS_ALMACEN)){
                                            $color = "style='background-color: #ffffcc;'";    
                                            $status_a = 'En Espaera de CxC';
                                            $statusSig = 'Liberar';
                                        }elseif($data->STATUS_ALMACEN == 'Liberado'){
                                            $color = "style='background-color: #A9F5BC;'";    
                                            $status_a = 'Liberado';
                                            $statusSig = 'Recibir';
                                        }elseif($data->STATUS_ALMACEN == 'Facturado'){
                                            $color = "style='background-color: #A9F5BC;'";    
                                            $status_a = 'Facturado';
                                            $statusSig = 'Facturado';
                                        }elseif ($data->STATUS_ALMACEN == 'EN PROCESO') {
                                            $color = "style='background-color: #A9F5BC;'";    
                                            $status_a = 'En Proceso';
                                            $statusSig = 'En Proceso';
                                        }elseif($data->STATUS_ALMACEN == 'LISTO'){
                                            $color = "style='background-color: #A9F5BC;'";    
                                            $status_a = 'Listo Almacen';
                                            $statusSig = 'Listo Almacen';
                                        }elseif($data->STATUS_ALMACEN == 'Rechazado'){
                                            $color = "style='background-color: #b3b3ff;'";    
                                            $status_a = 'Rechazado';
                                            $statusSig = 'Rechazado';
                                        }

                                        if($data->TIP_DOC_SIG == 'R'){
                                            $tds = 'Remision';
                                            $color = "style='background-color:#b3e6ff;'";
                                        }elseif($data->TIP_DOC_SIG == 'F'){
                                            $tds = 'Factura';
                                            $color = "style='background-color: #b3e6ff;'";
                                        }
                                        if($data->ENLAZADO == 'T'){
                                            $te = 'Todo';
                                        }elseif($data->ENLAZADO = 'P'){
                                            $te = 'Parcial';
                                        }elseif($data->ENLAZADO = 'O' or empty($data->ENLAZADO)){
                                            $te = 'Sin Enlace';
                                        }

                                        if(!empty($data->COMENTARIOS)){
                                            $obs = $data->COMENTARIOS;
                                            $obs = str_replace(',', '&#10;', $obs);
                                        }

                                        if($obs!='Sin Observaciones' and !empty($data->RECHAZO)){
                                            $obs .= ', Motivo del Rechazo: '.$data->RECHAZO;
                                        }elseif($obs=='Sin Observaciones' and !empty($data->RECHAZO)){
                                            $obs = ', Motivo del Rechazo: '.$data->RECHAZO;
                                        }

                                        if($data->STATUS == 'C'){
                                            $color ="style='background-color:#ff9999'";
                                        }
                                        ?>
                                    <?php if($status_a=='Liberado'  or $status_a=='En Proceso'){?>
                                       <tr class="odd gradeX" <?php echo $color;?> title='<?php echo $obs?>'>           
                                            <td><?php echo $data->CVE_DOC;?><br/>
                                            <?php if(!empty($data->CVE_OBS)){?>
                                            <a href="index.php?action=verObservaciones&obs=<?php echo $data->CVE_OBS?>&doc=<?php echo $data->CVE_DOC?>" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;"><font color='blue'>Observaciones</font></a>
                                                <br/><b><?php echo 'Credito: '.$data->DIASCRED?></b>
                                            <?php }?>
                                            </td>
                                            <td><?php echo '('.$data->CVE_CLPV.')'.$data->NOMBRE;?><br/>
                                                <?php if($data->ARCHIVOS > 0){?>
                                                    <a href="index.php?action=verArchivos&tipo=<?php echo $data->TIP_DOC?>&doc=<?php echo $data->CVE_DOC?>" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;"> <font color='red'><i class="fa fa-file" ></i> <?php echo $data->ARCHIVOS?></font></a>

                                                <?php }?>
                                                <?php if($data->FP == 1){?>
                                                    <a href="/DocRemision/<?php echo $data->NOMBRE_FP?>" download="/DocRemision/<?php echo ($data->NOMBRE_FP)?>"> <font color='green'><i class="fa fa-money fa-2x" ></i></font></a>
                                                <?php }elseif($data->FP > 1){?>
                                                    <a href="/DocRemision/<?php echo $data->NOMBRE_FP?>" download="/DocRemision/<?php echo ($data->NOMBRE_FP)?>"> <font color='green'><i class="fa fa-money fa-2x" ></i></font></a>
                                                    <a href="index.php?action=verArchivos&tipo=<?php echo $data->TIP_DOC?>&doc=<?php echo $data->CVE_DOC?>" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;"> <font color='blue' size="4.5 pxs"><?php echo '('.$data->FP.')'?></font></a>                                                   
                                                <?php }?>    
                                            <?php if($tipoUsuario == 'venta'){?>
                                                <form action="uploadOC.php" method="post" enctype="multipart/form-data">
                                                <input type="file" name="fileToUpload" id="fileToUpload" required="required">
                                                <input type="hidden" name="doc" value="<?php echo $data->CVE_DOC?>">
                                                <input type="hidden" name="tipodoc" value="<?php echo $data->TIP_DOC?>">
                                                <input type="submit" value="Subir Documento" name="submit">
                                                </form>
                                            <?php }?>
                                                 <input type="text" name="obs" value="<?php echo (!empty($data->OBS))? $data->OBS:''?>" placeholder="Observaciones Documento" maxlength="80" size="80" onchange="observaciones(this.value, '<?php echo $data->CVE_DOC?>')">
                                            </td>
                                            <td><?php echo substr($data->FECHAELAB,0,10).'<br/>'.substr($data->FECHAELAB,11,10);?></td>
                                            <td><?php echo '$ '.number_format($data->IMPORTE,2);?></td>
                                            <td><?php echo $data->STATUS;?></td>
                                            <td><?php echo $data->DIAS;?></td>
                                            <td><?php echo $status_a;?></td>
                                            <td><?php echo $data->DOC_SIG.'<br/>'.$tds;?></td>
                                            <td><?php echo $te;?></td>
                                            <td><?php echo $data->PXS;?></td>
                                            <td><?php echo $data->USUARIO_LIBERA.'<br/>'.$data->FECHA_LIBERA?></td>
                                            <td>
                                                <input id="l_<?php echo $data->CVE_DOC?>" type="checkbox" name="doc" value="Autorizar" <?php echo ($statusSig=='Liberar')? '':'checked disabled' ?> documento="<?php echo $data->CVE_DOC?>"  class="liberar" usuario="<?php echo $usuario?>" tipo="l"/><?php echo $statusSig?>
                                                <br/>
                                                <?php if($statusSig=='Liberar'){?>
                                                <input id="r_<?php echo $data->CVE_DOC?>" type="checkbox" name="doc" value="Rechazar" <?php echo ($statusSig=='Liberar')? '':'checked disabled' ?> documento="<?php echo $data->CVE_DOC?>"  class="liberar" usuario="<?php echo $usuario?>" tipo="r"/>Rechazar
                                                <?php }?>
                                            </td>
                                            <?php if($tipoUsuario == 'alma'){?>
                                                <td>
                                                    <input type="button" class="btn-small btn-info preparar" info="<?php echo $data->CVE_DOC?>" value="Peparar"><br/>
                                                    <input type="button" class="btn-small btn-warning imprime" info="<?php echo $data->CVE_DOC?>" value="Imprimir">
                                                </td>
                                            <?php }?>
                                            </tr>
                                        <?php }?>
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                      </div>
            </div>
        </div>
</div>
</div>
<input type="hidden" name="algo" value="Poooooooo" id="pop">

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script type="text/javascript">

    setTimeout('document.location.reload()', 360000) ;

    function observaciones(obs, doc){
        if(confirm('La observacion no podra cambiarse, desea guardarla?')){
            
            $.ajax({
                url:'index.php',
                type:'post',
                dataType:'json',
                data:{guardaObs:obs, doc:doc},
                success:function(data){
                    alert('Se guardo la informacion');
                }

            });
        }
    }

    $('.preparar').click(function(){
        var doc = $(this).attr('info');
        if(confirm("Desea preparar la remision " + doc)){
            $.ajax({
            url:'index.php',
            type:'post',
            dataType:'json',
            data:{revisaRemision:1,doc:doc},
            success:function(data){
                if(data.status == 'ok'){
                    window.open("index.php?action=preparaRemision&doc="+doc+"&tipo=nueva", "Preparar Remision","width=1600,height=1000");
                    return false;
                }else if(data.status = 'bloqueada'){
                        if(confirm('La Remision esta bloqueda por ' + data.usuario + ', desde el  '+ data.fecha + ' desea desbloquearla? los cambios del usuario ' + data.usuario + ' se perderan')){
                           window.open("index.php?action=preparaRemision&doc="+doc+"&tipo=desbloquear", "Preparar Remision","width=1600,height=1000");
                            return false; 
                        }
                }
            }
            });    
        }
    });

    $('.imprime').click(function(){
        var doc = $(this).attr('info');
        if(confirm("Desea imprimir la remision " + doc)){
            $.ajax({
            url:'index.php',
            type:'post',
            dataType:'json',
            data:{revisaRemision:1,doc:doc},
            success:function(data){
                if(data.status == 'ok'){
                    window.open("index.php?action=imprimeRemision&doc="+doc+"&tipo=nueva", "Preparar Remision","width=1600,height=1000");
                    return false;
                }else if(data.status = 'bloqueada'){
                        if(confirm('La Remision esta bloqueda por ' + data.usuario + ', desde el  '+ data.fecha + ' desea desbloquearla? los cambios del usuario ' + data.usuario + ' se perderan')){
                           window.open("index.php?action=imprimeRemision&doc="+doc+"&tipo=desbloquear", "Preparar Remision","width=1600,height=1000");
                            return false; 
                        }
                }
            }
            });    
        }
    });

    $('.liberar').click(function(){
        var doc =$(this).attr('documento');
        var usuario = $(this).attr('usuario');
        var tipo=$(this).attr('tipo');
        var motivo = '';
        if(tipo =='r'){
            var t ='Rechazar' 
        }else{
            var t ='Liberar' 
        }
        if(usuario=='Angelica Minuto'|| usuario == 'Manuel'){
            if(confirm('Desea '+ t +' el documento: ' + doc )){
            if(tipo == 'r'){
            $.confirm({
            columnClass: 'col-md-8',
            title: 'Rechazo de Remisiones',
            content: 'Motivo de Rechazo de Remision' + 
            '<form action="index.php" class="formName">' +
            '<div class="form-group">'+
            'Motivo: <br/><input name="motivo" type="text" placeholder="Motivo del Rechazo de la Remision" size ="100" class="mot"> <br/>' +
            '</div><br/><br/>'+
            '</form>',
                buttons: {
                formSubmit: {
                text: 'Rechazo de Remision',
                btnClass: 'btn-blue',
                action: function () {
                    var motivo = this.$content.find('.mot').val(); 
                    //var maestr = this.$content.find('mae').val(); 
                    if(motivo == ''){
                        $.alert('Dede de colocar el motivo del Rechazo...');
                        return false;
                    }else{
                        $.ajax({
                        url:'index.php',
                        type: 'post',
                        dataType: 'json',
                        data:{autorizaDoc:doc, tipo, motivo},
                        success:function(data){
                            if(data.status == 'ok'){
                                document.getElementById('l_'+doc).classList.add('hide');
                                alert('Se ha '+ data.sta +' por' + data.usuario + data.fecha);   
                            }else if(data.status == 'c'){
                                alert('La remision : '+ doc + ' ha sido cancelada y no se puede liberar.');
                                location.reload(true);
                            }
                        }
                        });
                    }
                   }
            },
            cancelar:function (){
                document.getElementById('l_'+doc).checked=0;
                document.getElementById('r_'+doc).checked=0;
            },
            }
        });
        }else{
                $.ajax({
                url:'index.php',
                type: 'post',
                dataType: 'json',
                data:{autorizaDoc:doc, tipo, motivo},
                success:function(data){
                    if(data.status == 'ok'){
                        document.getElementById('r_'+doc).classList.add('hide');
                        alert('Se ha '+ data.sta +' por' + data.usuario + data.fecha);   
                    }else if(data.status == 'c'){
                        alert('La remision : '+ doc + ' ha sido cancelada y no se puede liberar.');
                        document.getElementById('l_'+doc).checked=0;
                        document.getElementById('r_'+doc).checked=0;
                        location.reload(true);
                    }
                }
                });  
        }
               
            }else{
                document.getElementById('l_'+doc).checked=0;
                document.getElementById('r_'+doc).checked=0;
            }
        }else{
            document.getElementById('l_'+doc).checked=0;
            document.getElementById('r_'+doc).checked=0;
            alert('El usuario ' + usuario +  ' no esta autorizado para la liberacion...');
       }
    });

</script>

