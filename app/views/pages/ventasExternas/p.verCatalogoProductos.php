<br/>
<br/>
<p ><font color="#ffd6cc" style="background-color:black">Sin Existencia</font></p>
<p ><font color="#ffffcc" style="background-color:black">de 1 a 9</font></p>
<p><font color="#b3ffff" style="background-color:black">de 10 a 99</font></p>
<p><font color="#ccffcc" style="background-color:black">de 100 a 999</font></p>
<p><font color="#99ff99" style="background-color:black"> mas de 1000</font></p>

<?php echo $_SESSION['user']->NOMBRE?>
<?php echo $_SESSION['user']->LETRA?>
<form action="index.php" method="post">
<p><input type="email" multiple name="correo" placeholder="Correo Electronico, para varias direcciones separar con comas ejemplo alguien@dominio.com, alguie2@dominio2.com" id="correo" size="200" required></p>
<p><input type="text" placeholder="Nombre del cliente" id="cliente" name="cliente" size="200" required></p>
<p><button onclick="enviar(correo.value)" name="enviaCorreoVentas">Enviar</button></p>
</form>
<br/>
<div class="row" >
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Lista de Productos de Ventas.
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-aplicapago">
                                    <thead>
                                        <tr>
                                            <th>Sel</th>
                                            <th>Clave</th>
                                            <th>Descripcion <br/> Marca</th>
                                            <th>Existencia<br/> Solicitar</th>
                                            <th>Linea<br/> Tipo</th>
                                            <th>Almacen</th>
                                            <th>Caducidad<bR/><font color="red">Minima</font> --><font color="blue">Maxima</font></th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        $i = 0;
                                        foreach ($info as $data): 
                                            $color = '';
                                            $i++;
                                            if($data->STATUS == 'A'){
                                                $status = 'Activo';
                                            }elseif ($data->STATUS == 'B') {
                                                $status = 'Baja';
                                                $color='style="background-color:#ff99cc"';
                                            }elseif($data->STATUS == 'M'){
                                                $status = 'Espera Modificacion';
                                            }
                                            if($data->EXIST==0){
                                                $color='style="background-color:#ffd6cc"'; 
                                            }elseif ($data->EXIST>0 AND $data->EXIST<=9){
                                                $color='style="background-color:#ffffcc"';
                                            }elseif($data->EXIST >9 AND $data->EXIST<=99){
                                                $color='style="background-color:#b3ffff"';
                                            }elseif($data->EXIST >99 AND $data->EXIST<=999){
                                                $color='style="background-color:#ccffcc"';
                                            }elseif($data->EXIST >999 ){
                                                $color='style="background-color:#99ff99"';
                                            }
                                        ?>
                                        <tr <?php echo $color?>>
                                            <td><input  type="checkbox" <?php echo $data->H>0? 'checked':''?> name="prod[]" value="<?php echo $data->CVE_ART.','.$i?>" onchange="seleccionar('<?php echo $data->CVE_ART?>', <?php echo $i?>)" id="ln_<?php echo $i?>"></td>
                                            <td><?php echo $data->CVE_ART;?></td>
                                            <td><?php echo $data->DESCR.'<br/><b>'.$data->CAMPLIB10.'<b/>';?></td>
                                            
                                            <td align="center" title="Debe de seleccionar para poder editar la cantidad"><?php echo number_format($data->EXIST,0,".","");?><br/>
                                                <input type="radio" name="unidad_<?php echo $i?>" value="caja" <?php echo $data->UNIDAD=='caja'? 'checked="checked"':''?> onchange="actualizaUnidad(this.value, '<?php echo $data->CVE_ART?>',<?php echo $i?>)">Caja&nbsp;&nbsp;
                                                <input type="radio" name="unidad_<?php echo $i?>" value="pza" <?php echo $data->UNIDAD=='pza'? 'checked="checked"':''?> onchange="actualizaUnidad(this.value, '<?php echo $data->CVE_ART?>',<?php echo $i?>)">Pieza
                                                <br/> 
                                            <input type="number" name="cantidad" id="cant_<?php echo $i?>" <?php echo $data->H>0? '':'readonly'?> onchange="actualizar(this.value,'<?php echo $data->CVE_ART?>', <?php echo $i?> )" value="<?php echo $data->H>0? $data->H:''?>" ></td>
                                            <td><?php echo $data->LIN_PROD.'--'.$data->UNI_EMP.'<br/>'.$data->CAMPLIB15?></td>
                                            <td><?php echo $data->NOMBRE?></td>
                                            <td><?php echo '<font color="red">'.$data->CADUCIDADMINIMA.'</font>--><font color="blue">'.$data->CADUCIDADMAXIMA.'</font>'?></td>          
                                        <?php endforeach; ?>
                                 </tbody>
                                </table>
                            </div>
                      </div>
                </div>
        </div>
</div>
<br />
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="http://bootboxjs.com/bootbox.js"></script>
<script type="text/javascript">

    function seleccionar(prod, i){
        if($("#ln_"+i).prop('checked')){
            tipo = 'g';
            var cant = document.getElementById('cant_'+i);
            cant.value=1;
            cant.readOnly=false;
            cant = 1;
            var unidad= $('input:radio[name=unidad_'+i+']:checked').val();
        }else{
            tipo = 'b';
            var cant = document.getElementById('cant_'+i);
            cant.value=1;
            cant.readOnly=false;
            cant = 0;
        }
        $.ajax({    
            url:'index.php',
            type:'post',
            dataType:'json',
            data:{seleccionar:tipo, prod:prod, cant:cant},
            success:function(data){
                if(data.status == 'ok'){
                    alert('Se agrego el producto');
                }else{
                    alert('Se elimino el producto');
                }  
            }
        });
    }

    function actualizar(cant, prod,i ){
        var tipo = 'a';
         $.ajax({    
            url:'index.php',
            type:'post',
            dataType:'json',
            data:{seleccionar:tipo, prod:prod, cant:cant},
            success:function(data){
                if(data.status == 'ok'){
                }else{
                }  
            }
        });
    }

    function actualizaUnidad(cant, prod, i){
        var tipo = 'a';
         $.ajax({    
            url:'index.php',
            type:'post',
            dataType:'json',
            data:{seleccionar:tipo, prod:prod, cant:cant},
            success:function(data){
                if(data.status == 'ok'){
                }else{
                }  
            }
        });   
    }
   
    function enviar(c){
        var prodSel = document.getElementById('prod').value;
        //alert('Enviar correo' + c + prodSel);
        prodSel.forEach(function(element) {
          console.log(element);
        });
    }

        $(".cvesat1").autocomplete({
            source: "index.php?cvesat=1",
            minLength: 3,
            select: function(event, ui){
            }
        })

        $("select.unisat").change(function(){
            var nuni = $(this).val();
            var prod = $(this).attr('prod');
            var nvacve = '';
            var idpreoc = $(this).attr('idp');
            var cveact = $(this).attr('orig');
            if(nuni == 'a'){
                alert('Seleccione un valor');        
            }else{
                $.ajax({
                    url:'index.php',
                    type:'POST',
                    dataType:'json',
                    data:{gcvesat:prod,cvesat:nvacve,idpreoc:idpreoc,nuni:nuni,tipo:'uni'},
                    success:function(data){
                        if(data.status == 'ok'){
                            alert('Se actualizo el producto, ahora ya se puede facturar');
                            location.reload(true)
                        }else{
                            alert(data.mensaje);
                            document.getElementById('ln_'+idpreoc).value=cveact;
                        }
                    }
                });
            }
        })

        $("input.cvesat1").change(function(){
            var nuni = '';
            var prod = $(this).attr('prod');
            var nvacve = $(this).val();
            var idpreoc = $(this).attr('idp');
            var cveact = $(this).attr('orig');
            if(confirm('Desea asignar el codigo del SAT: '+ nvacve +', al producto: ' + prod)){   
                $.ajax({
                    url:'index.php',
                    type:'POST',
                    dataType:'json',
                    data:{gcvesat:prod,cvesat:nvacve,idpreoc:idpreoc, nuni:nuni, tipo:'cve'},
                    success:function(data){
                        if(data.status == 'ok'){
                            alert('Se actualizo el producto, ahora ya se puede facturar');
                            location.reload(true)
                        }else{
                            alert(data.mensaje);
                            document.getElementById('ln_'+idpreoc).value=cveact;
                        }
                    }
                });
            }else{
                document.getElementById('ln_'+idpreoc).value='';
            }
        })    

     $("#descripcion").autocomplete({
        source: "index.v.php?descAuto=1",
        minLength: 3,
        select: function(event, ui){
        }
    })

     $(".bajaArticulo").click(function(){
        var ids = $(this).attr('valor');
            if(confirm('Esta seguro de dar de baja el Articulo "PGS'+ids+'"' ))
            $.ajax({
                url:'index.php',
                type:'POST',
                dataType:'json',
                data:{editarArticulo:ids, tipo:'b'},
                success:function(data){
                    if(data.status == 'ok' && data.BAJA == 'SI'){
                        alert('Se ha eliminado el producto'); 
                        //location.reload(true);
                    }else{
                        var cotizacion = '';
                        var vendedores = '';
                        data.cotizaciones.forEach(function(element) {
                            cotizacion = cotizacion + element.cotizacion + '\n';
                            vendedores = vendedores + element.vendedor + '\n';
                        });
                        alert('El producto se encuentra en produccion en las cortizacion(es) \n'+ cotizacion + ' de los vededor(es) \n' + vendedores +'y no se puede eliminar');
                    }
                }
            });
     });

     $(".editar").click(function(){
            var ids = $(this).attr('valor');
            $.ajax({
              url:'index.php',
              type:'POST',
              dataType:'json',
              data:{editarArticulo:ids, tipo:'e'},
              success:function(data){
                  if(data.status= 'ok' && data.cotizaciones.length == 0 ){
                      window.open('index.php?action=editaFTCART&ids='+ids, 'popup', 'width=1200,height=820'); return false;
                  }else{
                        var cotizacion = '';
                        var vendedores = '';
                        window.open('index.php?action=editaFTCART&ids='+ids, 'popup', 'width=1200,height=820'); return false;
                        data.cotizaciones.forEach(function(element) {
                            cotizacion = cotizacion + element.cotizacion + '\n';
                            vendedores = vendedores + element.vendedor + '\n';
                        });
                      if(confirm("El producto esta en la(s) cotizacion(es):\n "+ cotizacion + "de lo(s) vededor(es) \n" + vendedores+ "y no se puede cambiar, desea apartarlo para que no se pueda volver Seleccionar en ventas?)")){                      
                            $.ajax({
                                url:'index.php',
                                type:'POST',
                                dataType:'json',
                                data:{editarArticulo:ids, tipo:'a'},
                                success:function(data2){
                                    if(data2.status == 'ok'){
                                        alert('El producto se ha apartado y no se podra comprar hasta su liberacion por compras');
                                        //location.reload(true);
                                    }
                                }
                            });
                        }
                 }
            }
          });
     });
</script>



