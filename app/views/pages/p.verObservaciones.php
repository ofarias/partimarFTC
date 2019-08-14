<meta http-equiv="Refresh" content="240">
<br /><br />
		<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            OBSERVACIONES DEL DOCUMENTO.
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Documento</th>
                                            <th>Observacion</th>
                                       </tr>
                                    </thead>                                   
                                  <tbody>
                                    	<?php
                                    	foreach ($obs as $data): 
                                        ?>
                                        <tr class="odd gradeX";?>                                            
                                            <td><?php echo $doc;?></td>
                                            <td><?php echo $data->STR_OBS;?></td>
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