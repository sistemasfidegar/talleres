 <?php 
       $user_agent = $_SERVER['HTTP_USER_AGENT'];
       
       function getBrowser($user_agent){
       	if(strpos($user_agent, 'MSIE') !== FALSE)
       	return 'IE';
       	elseif(strpos($user_agent, 'Trident') !== FALSE) //IE 11
       	return 'IE';
       	elseif(strpos($user_agent, 'Firefox') !== FALSE)
       	return 'Mozilla Firefox';
       	elseif(strpos($user_agent, 'Edge') !== FALSE)
       	return 'IE';
       	elseif(strpos($user_agent, 'Chrome') !== FALSE)
       	return 'Google Chrome';
       	elseif(strpos($user_agent, 'Opera Mini') !== FALSE)
       	return "Opera Mini";
       	elseif(strpos($user_agent, 'Opera') !== FALSE)
       	return "Opera";
       	elseif(strpos($user_agent, 'Safari') !== FALSE)
       	return "Safari";
       	else
       		return 'OTROr';
              
       }
       
       $navegador =  getBrowser($user_agent);
       ?>
       
<script type="text/javascript">
        jQuery(document).ready(function(){
        	$("#asistencia").click(function () {
        		if($("#matricula_asignada").val() != ""  ) {
        			$.blockUI({message: 'Procesando por favor espere...'});
        			jQuery.ajax({
    		            type: 'post',
    		            dataType: 'html',
    		            url: 'registro/getBeneficiario/',
    		            data: {matricula: $("#matricula_asignada").val()},
    		            success: function (data) {
        		            if(data == 'bad') {
        		            	$.unblockUI();
        		            	$('#myModalSinRegistroReimpresion').modal('show'); //open modal
        		            }
        		            else
            		        {
        		            	$.unblockUI();
        		            	irAPdf('asistencia/listaAsistencia/'+$("#matricula_asignada").val());
            		        }
    		            }
        			});		
        			
        		}else if($("#matricula_escuela").val() != "" ){
        			$.blockUI({message: 'Procesando por favor espere...'});
    	        	jQuery.ajax({
    		            type: 'post',
    		            dataType: 'html',
    		            url: 'registro/getBeneficiarioUnamReimpresion/',
    		            data: {matricula_escuela: $("#matricula_escuela").val()},
    		            success: function (data) {
    		            	if(data == 'bad') {
    		            		$.unblockUI();
        		            	$('#myModalSinRegistroReimpresion').modal('show'); //open modal
        		            } else {
        		            	$.unblockUI();
        		            	irAPdf('asistencia/listaAsistencia/'+data);
        		            	$("#matricula_escuela").val("");
            		        }
    		            }
    		        });		
            	}
				

             });
            
        	$("#reimpresion").click(function () {
        		if($("#matricula_asignada").val() != ""  ) {
    				$.blockUI({message: 'Procesando por favor espere...'});
    	        	jQuery.ajax({
    		            type: 'post',
    		            dataType: 'html',
    		            url: 'registro/getBeneficiarioReimpresion/',
    		            data: {matricula: $("#matricula_asignada").val()},
    		            success: function (data) {
        		            if(data == 'bad') {
        		            	$.unblockUI();
        		            	$('#myModalSinRegistroReimpresion').modal('show'); //open modal
        		            } else if(data == 'espera') {
        		            	$.unblockUI();
        		            	$('#myModalEsperaReimpresion').modal('show'); //open modal
        		            } else {
        		            	$.unblockUI();
         		            	irAPdf('registro/pdf/'+ $("#matricula_asignada").val());
         		            	$("#matricula_asignada").val("");
            		        }
    		            }
    		            
    		        });
    	        } else if($("#matricula_escuela").val()!= "" ){
    				$.blockUI({message: 'Procesando por favor espere...'});
    	        	jQuery.ajax({
    		            type: 'post',
    		            dataType: 'html',
    		            url: 'registro/getBeneficiarioUnamReimpresion/',
    		            data: {matricula_escuela: $("#matricula_escuela").val()},
    		            success: function (data) {
    		            	if(data == 'bad') {
    		            		$.unblockUI();
        		            	$('#myModalSinRegistroReimpresion').modal('show'); //open modal
        		            } else if(data == 'espera') {
        		            	$.unblockUI();
        		            	$('#myModalEsperaReimpresion').modal('show'); //open modal
        		            } else {
        		            	$.unblockUI();
        		            	irAPdf('registro/pdf/'+ data);
        		            	$("#matricula_escuela").val("");
            		        }
    		            }
    		        });
    			}
        	});

            
    		$("#registro").click(function () {
        		if($("#matricula_asignada").val() != ""  ) {
	    				$.blockUI({message: 'Procesando por favor espere...'});
	    	        	jQuery.ajax({
	    		            type: 'post',
	    		            dataType: 'html',
	    		            url: 'registro/getBeneficiario/',
	    		            data: {matricula: $("#matricula_asignada").val()},
	    		            success: function (data) {
	        		            if(data == 'bad') {
	        		            	$.unblockUI();
	        		            	$('#myModalSinRegistro').modal('show'); //open modal
	        		            } else if(data == 'registro') {
	        		            	$.unblockUI();
	         		            	$('#myModalRegistro').modal('show'); //open modal
	         		            } else if(data == 'pagoMax') {
	         		            	$.unblockUI();
	         		            	$('#myModalPagoMax').modal('show'); //open modal
	         		            } else if(data == 'espera') {
	         		            	$.unblockUI();
	         		            	$('#myModalEspera').modal('show'); //open modal
	         		            } else if(data == 'sinedad') {
	         		            	$.unblockUI();
	         		            	$('#myModalSinEdad').modal('show'); //open modal
	         		            } else {
	         		            	irA('registro/nuevo/'+ data);
	            		        }
	    		            }
	    		            
	    		        });
	    	        } else if($("#matricula_escuela").val()!= "" ){
	    				$.blockUI({message: 'Procesando por favor espere...'});
	    	        	jQuery.ajax({
	    		            type: 'post',
	    		            dataType: 'html',
	    		            url: 'registro/getBeneficiarioUnam/',
	    		            data: {matricula_escuela: $("#matricula_escuela").val()},
	    		            success: function (data) {
	    		            	 if(data == 'bad') {
	    		            		 $.unblockUI();
	          		            	$('#myModalSinRegistro').modal('show'); //open modal
	         		            } else if(data == 'registro') {
	         		            	$.unblockUI();
	         		            	$('#myModalRegistro').modal('show'); //open modal
	         		            }else if(data == 'pagoMax'){
	         		            	$.unblockUI();
	         		            	$('#myModalPagoMax').modal('show'); //open modal
	         		            } else if(data == 'espera') {
	         		            	$.unblockUI();
	         		            	$('#myModalEspera').modal('show'); //open modal
	         		            } else if(data == 'sinedad') {
	         		            	$.unblockUI();
	         		            	$('#myModalSinEdad').modal('show'); //open modal
	         		            } else {
	         		            	irA('registro/nuevo/'+ data);
	             		        }
	    		            }
	    		        });
	    			}
    		});
        });//ready
        
        function irA(uri) {
            window.location.href =  '<?= base_url() ?>' + uri;
            
        }	
        
        function irAPdf(uri) {
            window.open('<?= base_url() ?>' + uri, '_blank');
        }	
</script>

	<div class="modal fade" tabindex="-1" role="dialog" id="myModalEsperaReimpresion">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="text-align: center;">Lista de espera</h4>
				</div>
				<div class="modal-body">
					<form id="attributeFormModalEsperaReimpresion">
						<div class="form-group" style="text-align: justify;">
							Te has registrado en lista de espera y no es posible imprimir tu comprobante.<br />
							Te sugerimos estar al pendiente, ya que puedes ser seleccionad@ posteriormente.<br /><br />
							Para mayor informaci&oacute;n comun&iacute;cate al tel&eacute;fono 1102 1750 (L a V de 9 a 18 hrs).<br />
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="modal fade" tabindex="-1" role="dialog" id="myModalEspera">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="text-align: center;">Lista de espera</h4>
				</div>
				<div class="modal-body">
					<form id="attributeFormModalEspera">
						<div class="form-group" style="text-align: justify;">
							Los datos proporcionados corresponden a un Beneficiario Previamente Registrado en lista de espera.<br /><br />
							Para mayor informaci&oacute;n comun&iacute;cate al tel&eacute;fono 1102 1750 (L a V de 9 a 18 hrs)<br />
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" tabindex="-1" role="dialog" id="myModalSinRegistro">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="text-align: center;">Datos No Registrados</h4>
				</div>
				<div class="modal-body">
					<form id="attributeFormModalSinRegistro">
						<div class="form-group" style="text-align: justify;">
							Los datos proporcionados (CURP, PS &oacute; No. de cuenta) no se encontraron en nuestra Base de Datos, recuerda que para poder registrarte es necesario ser un Beneficiario Activo del Programa "Prepa S&iacute;". <br /><br />
	                        
	                        Para mayor informaci&oacute;n comun&iacute;cate al tel&eacute;fono 1102 1750 (L a V de 9 a 18 hrs)<br /><br />  
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
					<h4 class="modal-title" style="text-align: center;">Beneficiario Previamente Registrado</h4>
				</div>
				<div class="modal-body">
					<form id="attributeFormModalRegistro">
						<div class="form-group" style="text-align: justify;">
							Los datos proporcionados corresponden a un Beneficiario Previamente Registrado.<br /><br />
							Para mayor informaci&oacute;n comun&iacute;cate al tel&eacute;fono 1102 1750 (L a V de 9 a 18 hrs)<br /><br />
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="modal fade" tabindex="-1" role="dialog" id="myModalSinRegistroReimpresion">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="text-align: center;">Datos No Registrados</h4>
				</div>
				<div class="modal-body">
					<form id="attributeFormModalSinRegistroReimpresion" role="form">
						<div class="form-group" style="text-align: justify;">
							No se encontraron los datos proporcionados (CURP, PS &oacute; No. de cuenta) <br /><br />
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="modal fade" tabindex="-1" role="dialog" id="myModalPagoMax">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="text-align: center;">IMPORTANTE</h4>
				</div>
				<div class="modal-body">
					<form id="attributeFormModalPagoMax">
						<div class="form-group" style="text-align: justify;">
							Lo sentimos no cumples con los requisitos. <br /><br />
	                        
	                        Para mayor informaci&oacute;n comun&iacute;cate al tel&eacute;fono 1102 1750 (L a V de 9 a 18 hrs)<br />
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="modal fade" tabindex="-1" role="dialog" id="myModalSinEdad">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="text-align: center;">IMPORTANTE</h4>
				</div>
				<div class="modal-body">
					<form id="attributeFormModalSinEdad">
						<div class="form-group" style="text-align: justify;">
							Lo sentimos no cumples con el requisito m&iacute;nimo de edad (m&aacute;ximo 20 a&ntilde;os).<br /><br />
	                        
	                        Para mayor informaci&oacute;n comun&iacute;cate al tel&eacute;fono 1102 1750 (L a V de 9 a 18 hrs)<br />
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="register-container container">
	<div class="row">                
		<div class="register">
			<form id="buscar_beneficiario" name="buscar_beneficiario" method="post" autocomplete="off">
				 <div style="text-align:left; padding-left:20px; border-bottom: 2px dotted #bbb; min-height:73px;">
                 	<img  src="resources/formulario/img/pleca_logos.png" alt="Logo" class="img-responsive center-block" style="padding-top:10px; vertical-align:top;" />&nbsp;
                 </div>
                 <?php	if (isset($disponible)){ 
                 			if($disponible == 1) { ?>
				<div class="form-goup">
					<br>
					<table style="width: 100%; border-style: 0;">		                        	
			        	<tr>
			        		<td>A&Uacute;N NO SE ENCUENTRA ACTIVO EL REGISTRO PARA EL CICLO DE CONFERENCIAS <strong>"PREP&Aacute;rate"</strong></td>		                        		
			            </tr>
			            <tr><td>&nbsp;&nbsp;</td></tr>
			            <tr><td>&nbsp;&nbsp;</td></tr>
			            <tr>
				            <td>
					            <div style="text-align:rigth; padding-left:20px;  min-height:73px;" class="span4">
		                			<a href="http://www.prepasi.df.gob.mx/" class="btn">Salir</a>                                         	
		                		</div>
		                	</td>
	                	</tr>
			        </table>       	
					</div>	
					<?php } ?>				
				<?php } else { ?>
                 <br>
                 	<div style="text-align:CENTER !important;"><label  style="color:#4C4C4C;  font-size: 180%;">REGISTRO AL CICLO DE CONFERENCIAS "PREP&Aacute;rate"</label></div>
				    	<table  style="width:100%; text-align: center; float: center; border-spacing: 5; <?php if($navegador=='IE'){ echo 'display:none;'; }?>">
			    	 		<tbody>
					        	<tr>
					         		<td colspan="3" style="padding: 0;">&nbsp;</td>
						        </tr>
						        <tr style="text-align: center;">
						          	<td colspan="3" style="padding: 0;"><input type="text" id="matricula_asignada" name="matricula_asignada" value="" placeholder="Ingresa tu matr&iacute;cula PS o CURP" style="width:50%; text-transform:uppercase;"/></td>
					        	</tr>
						        <tr>
					            	<td colspan="3" style="padding: 0;">&nbsp;</td>
						        </tr>
						        <tr style="text-align: center;">
						          	<td colspan="3" style="padding: 0;"><input type="text" id="matricula_escuela" name="matricula_escuela" value="" placeholder="Ingresa matr&iacute;cula (unam)" style="width:50%; text-transform:uppercase;"/></td>
						        </tr>
				         	</tbody>
					         
					        <tfoot>
						        <tr>
							      <td style="width: 50%;">
					     				<button style="width: 40%; height:40%; float: right;" id="registro" name="registro" type="button" class="btn">Iniciar Registro</button>
					        	  	</td>
					        	  	<td >&nbsp;</td>
					        	 
					        	  	<td style="width: 50%;">
					     				<button style="width: 40%; height:40%; float: left;" id="reimpresion" name="reimpresion" type="button" class="btn">Obtener Comprobante</button>
					        	  	</td>
					        	  	
					        	 <!-- 	
					        	  	<td style="width: 30%">
					     				<button style="width: 30%; height:40%; float: center;" id="asistencia" name="asistencia" type="button" class="btn">Obtener Asistencia</button>
					        	  	</td>
					        	 -->
						        </tr>
					        </tfoot>
				      	</table>
				      	
				      	<table style="width: 100%; float: center; <?php if($navegador!='IE'){ echo 'display:none;'; } ?>" id="mensaje">
				      		<tbody>
                        		<tr>
                                	<td style="width: 50%; float: center; text-align: center; font-size: 19px;">  
                                		<div style="color: #4C4C4C;">                              	
	                                   		<span>
	                                    		Para evitar contratiempos en el funcionamiento del sistema es necesario utilizarlo con alguno de los siguientes navegadores.<br /><br />
	                                    		<a href="https://download.mozilla.org/?product=firefox-stub&os=win&lang=es-MX" style="color:#E6007E;"><img src="resources/img/firefox.png" style="vertical-align: middle;" alt="Mozilla Firefox" title="Mozilla Firefox"/></a>&nbsp;&nbsp; 
	                                    		<a href="https://www.google.com.mx/chrome/browser/desktop/#" style="color:#E6007E;" target="_blank"><img src="resources/img/chrome.png" style="vertical-align: middle;" alt="Google Chrome" title="Google Chrome"/></a> 
	                                    	</span>
                                    	</div>
                                	</td>	
                            	</tr>
                            </tbody>      
                        </table>
				<?php } ?>
				</form>
			</div>
		</div>
	</div> 							