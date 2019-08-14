<br/>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
        	<div class="panel-heading">
        	</div>
        	<div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" >
                    <thead>
                    	<font color="blue" size='5pxs'> Archvivo del Documento <?php echo $doc?> </font>
                    	<br/>
                        <tr>
                            <th>ID </th>
                            <th>Archivo</th>
                            <th>Tipo</th>
                            <th>Documento </th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                            <th>Descargar</th>
                            <th>Forma de Pago?</th>      
                       </tr>
                    </thead>                                   
                  <tbody>
                        <?php foreach ($comprobantes as $key):
                        ?>
                        <tr class="odd gradeX">
                            <td> <?php echo $key->ID?> </td>
                            <td><?php echo $key->NOMBRE?></td>
                            <td><?php echo $key->TIPO;?> </td>
                            <td><?php echo $key->DOCUMENTO;?></td>
                            <td><?php echo $key->USUARIO;?> </td>
                            <td><?php echo $key->FECHA;?> </td>
                            <td align="center"><a href="/DocRemision/<?php echo $key->NOMBRE?>" download="/DocRemision/<?php echo ($key->NOMBRE)?>"><i class="fa fa-circle"></i></a></td>
                            <td align="center"><input  <?php echo ($key->STATUS == 9)? 'checked disabled':'' ?> type="checkbox" name="fm" class="fm" id="fm_<?php echo $key->ID?>" ln="<?php echo $key->ID?>" file="<?php echo $key->NOMBRE?>"></td>
                        </tr>
                        </form>
                        <?php endforeach; ?>
                 </tbody>
                </table>
            </div>
		</div>
	</div>
</div>

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>
    $("input:checkbox").change(function(){
        var ln = $(this).attr('ln');
        var file = $(this).attr('file');
        if(confirm('Se Marcara el archivo ' + file +', como una forma de pago y no podra ser modificado, desea continuar?')){
            $.ajax({
                url:'index.php',
                type:'post',
                dataType:'json',
                data:{setFP:ln},
                success:function(data){
                    if(data.status == 'ok'){
                        location.reload(true);
                    }else{
                        alert('No se pudo colocar como forma de pago');
                        location.reload(true);
                    }
                },
                error:function(data){
                    alert('Surgio un error favor de informar a sistemas');
                    location.reload(true);
                }
            });
        }else{
            document.getElementById('fm_' + ln).checked=false;
        }

    })

</script>
