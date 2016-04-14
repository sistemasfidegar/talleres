
<script type="text/javascript">
        jQuery(document).ready(function(){
            
        	$("#reimpresión").click(function () {
        		if($("#matricula_asignada").val() != ""  ) {
    				$.blockUI({message: 'Procesando por favor espere...'});
    	        	jQuery.ajax({
    		            type: 'post',
    		            dataType: 'html',
    		            url: 'registro/getBeneficiario',
    		            data: {matricula: $("#matricula_asignada").val()},
    		            success: function (data) {
    		            	$.unblockUI();
        		            if(data == 'bad') {
        		            	$('#myModalSinRegistro').modal('show'); //open modal
        		            } else {
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
    		            url: 'registro/getBeneficiarioUnamReimpreion/',
    		            data: {matricula_escuela: $("#matricula_escuela").val()},
    		            success: function (data) {
    		            	$.unblockUI();
    		            	if(data == 'bad') {
        		            	$('#myModalSinRegistro').modal('show'); //open modal
        		            } else {
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
	    		            url: 'registro/getBeneficiario',
	    		            data: {matricula: $("#matricula_asignada").val()},
	    		            success: function (data) {
	    		            	$.unblockUI();
	        		            if(data == 'bad') {
	        		            	$('#myModalSinRegistro').modal('show'); //open modal
	        		            } else if(data == 'registro') {
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
	    		            url: 'registro/getBeneficiarioUnam',
	    		            data: {matricula_escuela: $("#matricula_escuela").val()},
	    		            success: function (data) {
	    		            	$.unblockUI();
	    		            	 if(data == 'bad') {
	          		            	$('#myModalSinRegistro').modal('show'); //open modal
	         		            } else if(data == 'registro') {
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
					<h4 class="modal-title" align="center">Beneficiario Inexistente</h4>
				</div>
				<div class="modal-body">
					<form id="attributeForm" role="form">
						<div class="form-group">
							Recuerda que para registarte a los talleres &uacute;nicamente lo puedes llevar a cabo si realizaste con anterioridad tu registro electr&oacute;nico, si entregaste tus documentos y
	                        si estos fueron aceptados. <br /><br />
	                        
	                        1. Verifica que el dato que proporcionaste para ingresar al sistema es correcto (CURP, PS o No. de cuenta), ya que puede ser un error al teclear.<br /><br />
	                        
	                        Si los datos proporcionados son correctos, comun&iacute;cate al tel&eacute;fono 1102 1750 (L a V de 9 a 18 hrs) para que puedan brindarte mayor informaci&oacute;n.<br /><br />  
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
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" align="center">Beneficiario Registrado</h4>
				</div>
				<div class="modal-body">
					<form id="attributeForm" role="form">
						<div class="form-group">
							¡El beneficiario ya se registr&oacute;!  
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
                 	<div style="text-align:CENTER !important;"><label  style="color:#E6007E;  font-size: 180%;"> REGISTRO TALLER </label></div>
						<table width="620" border="0" align="center" cellpadding="0" cellspacing="0">
							 <tr>
							   	<td bgcolor="">
							    	<table width="95%" border="0" align="center" cellpadding="0" cellspacing="5">
							    	    <tr>
							        		<td colspan="3" align="center" class="">Elige un método de búsqueda:</td>
							          	</tr>      
							        	 <tr>
							         		<td colspan="3">&nbsp;</td>
								        </tr>
								        <tr>
								          <td colspan="3"><input type="text" id="matricula_asignada" name="matricula_asignada" value="" placeholder="                 Ingresa tu matricula PS o CURP" style="width:87%; text-transform:uppercase;"/></td>
							        	</tr>
								         <tr>
								          <td colspan="3">&nbsp;</td>
								        </tr>
								        <tr>
								          <td colspan="3"><input type="text" id="matricula_escuela" name="matricula_escuela" value="" placeholder="                    Ingresa matricula (unam)" style="width:87%; text-transform:uppercase;"/></td>
								        </tr>
								        <tr>
							         		<td colspan="3">&nbsp;</td>
								        </tr>
								        <tr>
									      <td width="50%">
								          	  <div class="box-footer" style="text-align: center;" >
							     				<button style="width:70%; height:40%;" id="registro" name="registro" type="button" class="btn">Registro</button>
							     		   	  </div>
							        	  </td>
							        	  <td width="50%">
								          	  <div class="box-footer" style="text-align: center;" >
							     				<button style="width:70%; height:40%;" id="reimpresión" name="reimpresión" type="button" class="btn">Reimpresión</button>
							     		   	  </div>
							        	  </td>
								        </tr>
								       
							        	<tr>
							        	<td colspan="3">&nbsp;</td>
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
		 							