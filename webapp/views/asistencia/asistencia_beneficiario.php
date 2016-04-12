
<script type="text/javascript">
        jQuery(document).ready(function(){


    		$("#guardar").click(function () {
    			if($("#matricula_asignada").val() != ""  ) {
    				$.blockUI({message: 'Procesando por favor espere...'});
    	        	jQuery.ajax({
    		            type: 'post',
    		            dataType: 'html',
    		            url: '<?= base_url('asistencia/registroAsistencia') ?>',
    		            data: {matricula: $("#matricula_asignada").val()},
    		            success: function (data) {
        		            if(data == 'registrado') {
        		            	$.unblockUI();
        		            	$('#myModalRegistrado').modal('show'); //open modal
        		            	$("#matricula_asignada").val('');
            		        }
        		            else  if(data == 'bad'){
        		            	$.unblockUI();
        		            	$('#myModalSinRegistro').modal('show'); //open modal
            		        }
        		            else if(data == 'sintaller') {
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
            window.location.href =  uri;
        }	
</script>

	<div class="modal fade" tabindex="-1" role="dialog" id="myModalSinRegistro">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" align="center">Beneficiario Sin Registro</h4>
				</div>
				<div class="modal-body">
					<form id="attributeForm" role="form">
						<div class="form-group">
							Lo sentimos no se encontr&oacute; tu registro a los talleres Prepa S&iacute;
							<br/>
							<br/>1. Verifica  que tu matr&iacute;cula est&eacute; escrita correctamente 
							<br/><center><img src="../resources/img/pink-sad-face.png" alt="codesi" width="50%" class="img-rounded"></center>
							
						</div>
					</form>
				</div>
				
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="modal fade" tabindex="-1" role="dialog" id="myModalRegistro">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" align="center">Bienvenid@</h4>
				</div>
				<div class="modal-body">
					<form id="attributeForm" role="form">
						<div class="form-group">
							 <label for="mensaje" class="control-label"><div id="mensaje"></div></label><br/><br/>
							 <br/><center><img src="../resources/img/ok.png" alt="codesi" width="50%" class="img-rounded"></center>
						</div>
					</form>
				</div>
		
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	
	
	<div class="modal fade" tabindex="-1" role="dialog" id="myModalRegistrado">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" align="center">¡El beneficiario ya se registr&oacute;!</h4>
				</div>
				<div class="modal-body">
					<form id="attributeForm" role="form">
						<div class="form-group">
							 <br/><center><img src="../resources/img/pink-happy-face.png" alt="codesi" width="50%" class="img-rounded"></center>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	
	
	<div class="modal fade" tabindex="-1" role="dialog" id="myModalSinTaller">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" align="center">Sin Talleres</h4>
				</div>
				<div class="modal-body">
					<form id="attributeForm" role="form">
						<div class="form-group">
							<div class="form-group">
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
                 	<a href="http://www.prepasi.df.gob.mx/">	<img  src="../resources/formulario/img/pleca_logos.png" style="padding-top:10px;" align="top" />&nbsp;</a>
                 </div>
                 <?php	if (isset($disponible)){ 
                 			if($disponible == 1) { ?>
				<div class="form-goup">
					<br>
					<table width="100%" border="0">		                        	
			        	<tr>
			        		<td>NO HAY TALLERES POR IMPARTIR EN ESTE MOMENTO</td>		                        		
			            </tr>
			            <tr><td>&nbsp;&nbsp;</td></tr>
			            <tr><td>&nbsp;&nbsp;</td></tr>
			            <tr>
				            <td>
					            <div style="text-align:rigth; padding-left:20px;  min-height:73px;" class="span4">
		                			<a href="http://www.prepasi.df.gob.mx/" class="btn">Terminar</a>                                         	
		                		</div>
		                	</td>
	                	</tr>
			        </table>       	
					</div>	
					<?php } ?>				
				<?php } else { ?>
                 <br>
                 	<div style="text-align:CENTER !important;"><label class="leyenda" style="color:#E6007E; padding-left:20px;"> ASISTENCIA TALLERES PREPA SÍ </label></div>
						<table width="620" border="0" align="center" cellpadding="0" cellspacing="0">
							 <tr>
							   	<td bgcolor="">
							    	<table width="95%" border="0" align="center" cellpadding="0" cellspacing="5">
							    	    <tr>
							        		<td colspan="2" align="center" class=""></td>
							          	</tr>      
							        	 <tr>
							         		<td colspan="2">&nbsp;</td>
								        </tr>
								        <tr>
								          <td colspan="2"><input type="text" id="matricula_asignada" name="matricula_asignada" value="" placeholder="                 Ingresa tu matricula PS o CURP" style="width:80%; text-transform:uppercase;" autofocus/></td>
							        	</tr>
								         <tr>
								          <td colspan="2">&nbsp;</td>
								        </tr>
								         <tr>
									      <td colspan ="2""center">
								          	  <div class="box-footer" style="text-align: center;" >
							     				<button style="width:50%;" id="guardar" name="guardar" type="button" class="btn">Consultar</button>
							     		   	  </div>
							        	  </td>
								        </tr>
								        <tr>
								          <td>&nbsp;</td>
							         	  <td>&nbsp;</td>
							        	</tr>
							        	<tr>
							        	<td>&nbsp;</td>
									  	<td><div style="width:; display:inline-block;" id="letrero"> </div></td>
									    <td>&nbsp;</td>
							        	</tr>
							      	</table>
							 </tr>
						</table>
				<?php } ?>
						
				</form>
			</div>
		</div>
	</div>
		 							
