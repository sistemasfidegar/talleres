 <?php 
       $user_agent = $_SERVER['HTTP_USER_AGENT'];
       
       function getBrowser($user_agent){
       	if(strpos($user_agent, 'MSIE') !== FALSE)
       	return 'IE';
       	elseif(strpos($user_agent, 'Trident') !== FALSE) //IE 11
       	return 'IE';
       	elseif(strpos($user_agent, 'Firefox') !== FALSE)
       	return 'Mozilla Firefox';
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
            
        	$("#reimpresión").click(function () {
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
        		            } else {
        		            	$.unblockUI();
        		            	irAPdf('registro/pdf/'+ $("#matricula_escuela").val());
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

<div class="modal fade" tabindex="-1" role="dialog" id="myModalSinRegistro">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" align="center">Datos No Registrados</h4>
				</div>
				<div class="modal-body">
					<form id="attributeForm" role="form">
						<div class="form-group">
							Los datos proporcionados (CURP, PS &oacute; No. de cuenta) no se encontraron en nuestra Base de Datos, recuerda que para poder registrarte es necesario ser un Beneficiario Activo del Programa "Prepa S&iacute;". <br /><br />
	                        
	                        Para mayor informaci&oacute;n comun&iacute;cate al tel&eacute;fono 1102 1750 (L a V de 9 a 18 hrs)<br /><br />  
						</div>
					</form>
				</div>
				<div class="modal-footer" style="text-align: center;">
						Para mayor informaci&oacute;n visita:<br/>
						<a href="http://www.prepasi.df.gob.mx" target="_blank">www.prepasi.df.gob.mx</a><br/>
						<a href="https://www.facebook.com/pprepasi" target="_blank">
							<span class="fa-stack fa-lg">
                            	<i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                        <a href="https://www.twitter.com/P_Prepa_Si" target="_blank">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                        </a>
                        <a href="https://www.instagram.com/actividadesps/" target="_blank">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-instagram fa-stack-1x fa-inverse"></i>
                                </span>
                        </a><br/>
						Atenci&oacute;n telef&oacute;nica Prepa S&iacute; 1102 1750 (L a V de 9 a 18 hrs)
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="modal fade" tabindex="-1" role="dialog" id="myModalRegistro">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" align="center">Beneficiario Previamente Registrado</h4>
				</div>
				<div class="modal-body">
					<form id="attributeForm" role="form">
						<div class="form-group">
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
					<h4 class="modal-title" align="center">Datos No Registrados</h4>
				</div>
				<div class="modal-body">
					<form id="attributeForm" role="form">
						<div class="form-group">
							No se encontraron los datos proporcionados (CURP, PS &oacute; No. de cuenta), por favor reg&iacute;strate primero. <br /><br />
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
                 	<img  src="resources/formulario/img/pleca_logos.png" class="img-responsive center-block" style="padding-top:10px;" align="top" />&nbsp;
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
                 	<div style="text-align:CENTER !important;"><label  style="color:#4C4C4C;  font-size: 180%;">REGISTRO AL CICLO DE CONFERENCIAS "PREP&Aacute;RATE"</label></div>
						<table width="620" border="0" align="center" cellpadding="0" cellspacing="0">
							 <tr>
							   	<td bgcolor="">
							    	<table style="width:95%; text-align: center; <?php if($navegador=='IE'){ echo "display:none;"; }?>" border="0" cellpadding="0" cellspacing="5">
							        	 <tr>
							         		<td colspan="3">&nbsp;</td>
								        </tr>
								        <tr>
								          <td colspan="3"><input type="text" id="matricula_asignada" name="matricula_asignada" value="" placeholder="                          Ingresa tu matr&iacute;cula PS o CURP" style="width:87%; text-transform:uppercase;"/></td>
							        	</tr>
								         <tr>
								          <td colspan="3">&nbsp;</td>
								        </tr>
								        <tr>
								          <td colspan="3"><input type="text" id="matricula_escuela" name="matricula_escuela" value="" placeholder="                          Ingresa matr&iacute;cula (unam)" style="width:87%; text-transform:uppercase;"/></td>
								        </tr>
								        <tr>
							         		<td colspan="3">&nbsp;</td>
								        </tr>
								        <tr>
									      <td width="50%">
								          	  <div class="box-footer" style="text-align: center;" >
							     				<button style="width:70%; height:40%;" id="registro" name="registro" type="button" class="btn">Iniciar Registro</button>
							     		   	  </div>
							        	  </td>
							        	  <td width="50%">
								          	  <div class="box-footer" style="text-align: center;" >
							     				<button style="width:70%; height:40%;" id="reimpresión" name="reimpresión" type="button" class="btn">Obtener Comprobante</button>
							     		   	  </div>
							        	  </td>
								        </tr>
								       
							        	<tr>
							        	<td colspan="3">&nbsp;</td>
									  	<td><div style="width:; display:inline-block;" id="letrero"> </div></td>
									    <td>&nbsp;</td>
							        	</tr>
							      	</table>
							      	<table style="width: 95%; <?php if($navegador!='IE'){ echo "display:none;";}?>" border="0" id="mensaje">
                        	 			<tr>
                                			<td align="center" colspan="2" style="font-size:19px;" >                                	
                                    		<span style="color: #4C4C4C;">
                                    			Para evitar contratiempos en el funcionamiento del sistema es necesario utilizarlo con alguno de los siguientes navegadores.<br /><br />
                                    			<a href="https://download.mozilla.org/?product=firefox-stub&os=win&lang=es-MX" style="color:#E6007E;"><img src="resources/img/firefox.png" align="middle" title="Mozilla Firefox"/></a>&nbsp;&nbsp; 
                                    			<a href="https://www.google.com.mx/chrome/browser/desktop/#" style="color:#E6007E;" target="_blank"><img src="resources/img/chrome.png" align="middle" title="Google Chrome"/></a> 
                                    		</span>
                                			</td>	
                            			</tr>      
                        			</table>
							 </tr>
						</table>
						
				<?php } ?>
						
				</form>
			</div>
		</div>
	</div>
		 							