
<script type="text/javascript">
        jQuery(document).ready(function(){


    		$("#guardar").click(function () {
    			if($("#matricula_asignada").val() != ""  ) {
    				$.blockUI({message: 'Procesando por favor espere...'});
    	        	jQuery.ajax({
    		            type: 'post',
    		            dataType: 'html',
    		            url: '<?= base_url('asistencia/registroAsistencia/') ?>',
    		            data: {matricula: $("#matricula_asignada").val()},
    		            success: function (data) {
        		            if(data == 'error') {
        		            	$.unblockUI();
        		            	$('#myModalError').modal('show'); //open modal
        		            	$("#matricula_asignada").val('');
            		        } else  if(data == 'bad'){
        		            	$.unblockUI();
        		            	$('#myModalSinRegistro').modal('show'); //open modal
            		        } else if(data == 'sintaller') {
        		            	$.unblockUI();
        		            	$('#myModalSinTaller').modal('show'); //open modal
        		            	$("#matricula_asignada").val('');
        		            } else {
        		            	$.unblockUI();
        		            	$('#myModalRegistro').modal('show'); //open modal
        		            	$('#mensaje').html('Asistencia '+data+' Completo');
        		            	$("#matricula_asignada").val('');
        		            }
    		            }
    		        });
    	        } 
    		});
        });//ready
        
        function irA(uri) {
            window.location.href =  '<?= base_url() ?>' + uri;
        }	
</script>

	<div class="modal fade" tabindex="-1" role="dialog" id="myModalSinRegistro">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="text-align: center">Beneficiari@ Sin Registro</h4>
				</div>
				<div class="modal-body">
					<form id="attributeFormModalSinRegistro">
						<div class="form-group" style="text-align: justify;">
							Lo sentimos no se encontr&oacute; tu registro al Ciclo de Conferencias <strong>"PREP&Aacute;rate"</strong>
							<br/>
							<br/>1. Verifica  que tu matr&iacute;cula est&eacute; escrita correctamente. 
						</div>
					</form>
				</div>
				
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="modal fade" tabindex="-1" role="dialog" id="myModalRegistro">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="text-align: center;">Bienvenid@</h4>
				</div>
				<div class="modal-body">
					<form id="attributeFormModalRegistro">
						<div class="form-group" style="text-align: justify;">
							 <div id="mensaje"></div>
						</div>
					</form>
				</div>
		
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	
	
	<div class="modal fade" tabindex="-1" role="dialog" id="myModalError">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="text-align: center;">Error</h4>
				</div>
				<div class="modal-body">
					<form id="attributeFormModalError">
						<div class="form-group" style="text-align: justify;">
							 ¡Ocurri&oacute; un error! Favor de intentarlo de nuevo
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	
	
	<div class="modal fade" tabindex="-1" role="dialog" id="myModalSinTaller">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="text-align: center;">Sin Talleres</h4>
				</div>
				<div class="modal-body">
					<form id="attributeFormModalSinTaller">
						<div class="form-group">
							<div class="form-group" style="text-align: justify;">
							Lo sentimos, no se encontraron talleres disponibles para el d&iacute;a de hoy
							<br/>
						</div>
						</div>
					</form>
				</div>
		
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	
	
<div class="register-container container">
	<div class="row">                
		<div class="register">
			<form role="form" id="buscar_beneficiario" name="buscar_beneficiario" action="" method="post" autocomplete="off">
				 <div style="text-align:left; padding-left:20px; border-bottom: 2px dotted #bbb; min-height:73px;">
                 	<img  src="resources/formulario/img/pleca_logos.png" alt="Logo" class="img-responsive center-block" style="padding-top:10px; vertical-align:top;" />&nbsp;
                 </div>
                 <?php	if (isset($disponible)){ 
                 			if($disponible == 1) { ?>
				<div class="form-goup">
					<br>
					<table style="width: 100%;">		                        	
			        	<tr>
			        		<td>A&Uacute;N NO SE ENCUENTRA ACTIVO LA ASISTENCIA PARA EL CICLO DE CONFERENCIAS <strong>"PREP&Aacute;rate"</td>		                        		
			            </tr>
			            <tr><td>&nbsp;&nbsp;</td></tr>
			            <tr><td>&nbsp;&nbsp;</td></tr>
			            <tr>
				            <td>
					            <div style="text-align:rigth; padding-left:20px;  min-height:73px;" class="span4">
		                			<a href="<?= base_url() ?>" class="btn">Terminar</a>                                         	
		                		</div>
		                	</td>
	                	</tr>
			        </table>       	
					</div>	
					<?php } ?>				
				<?php } else { ?>
                 <br>
                 	<div style="text-align:CENTER !important;"><label  style="color:#4C4C4C;  font-size: 180%;">ASISTENCIA AL CICLO DE CONFERENCIAS "PREP&Aacute;rate"</label></div>
						<table style="width:100%; text-align: center; float: center; border-spacing: 5;">
							<tbody>
				    	    	<tr>
							    	<td colspan="2" style="text-align: center;" class=""></td>
							  	</tr>      
							    <tr>
							    	<td colspan="2">&nbsp;</td>
						        </tr>
						        <tr>
						          	<td colspan="2"><input type="text" id="matricula_asignada" name="matricula_asignada" value="" placeholder="Ingresa tu matricula PS o CURP" style="width:50%; text-transform:uppercase;" autofocus/></td>
					        	</tr>
					         	<tr>
						          	<td colspan="2">&nbsp;</td>
						        </tr>
							</tbody>
							
							<tfoot>
				         		<tr>
						      		<td colspan="2" style="width: 25%; text-align: center;">
					          	  		<div class="box-footer" style="text-align: center;" >
					     					<button style="width:25%;" id="guardar" name="guardar" type="button" class="btn">Consultar</button>
					     		   	  	</div>
					        	  	</td>
						        </tr>
						        <tr>
					          		<td>&nbsp;</td>
					         	  	<td>&nbsp;</td>
					        	</tr>
							</tfoot>
						</table>
				<?php } ?>
				</form>
			</div>
		</div>
	</div>