<?php $direccion = ""; ?>
<script type="text/javascript">
      function muestraAviso() {        	        			        	 
          bootbox.dialog({
              title: '<span style=" font-weight:bold; font-size:29px; ">Aviso de Privacidad</span>',
    		  message: $("#aviso").html(),
    		  buttons: {
        		  success: {
    			  	label: "Cerrar",
    			 	className: "btn-info",
    			 	callback: function () {
        			 	}
			 		}
			 	}
    	     });    			                     
        }

      $(document).ready(function() {
      	var rules_form = {
		        rules: {
		        	sede: {required : true, selectNone: true}
		        },
		        messages: {
		        	sede: {required: "Campo obligatorio"}
		        },
		        ignore: ":not(:visible)",
		        showErrors: function (map, list) {
		            // there's probably a way to simplify this
		            var focussed = document.activeElement;
		            
		            if (focussed && $(focussed).is("input, textarea")) {
		                $(this.currentForm).tooltip("close", {
		                    currentTarget: focussed
		                }, true);
		            }
		            //this.currentElements.removeAttr("title").removeClass("ui-state-error1");
		            this.currentElements.css(  {"border-style":"solid","border-color":"#A4A4A4","border-width":"1px"});
		            
		            $.each(list, function (index, error) {
		            	 //$(error.element).css( "border-color", "red","border-style:dashed" );
		            	 //$(error.element).attr("title", error.message).addClass("ui-state-error1");
		                $(error.element).attr("title", error.message).css( {"border-style":"dashed","border-color":"red", "border-width":"2px"} );
		            });
		            
		            if (focussed && $(focussed).is("input, textarea")) {
		                $(this.currentForm).tooltip("open", {
		                    target: focussed
		                });
		            }
		        }
		    };

      	jQuery.validator.addMethod("selectNone", function (value, element) {
          	if (element.value == "-1") {
          		return false;
          	} else {
              	return true;
          	}
      	},"Debe seleccionar una opci\xf3n"
      	);

      	$('#sede').change(function () {
          	if ($('#sede').val() != '-1') {
      			$('#talleres').show(); //muestro mediante id
      			$('#datos').html('<label class="control-label" style="text-align: left; color:#e6007e;" for="direccion"><?= $direccion ?></label>');
      			$('#direccion').show(); //muestro mediante id
          	} else {
          		$('#talleres').hide(); //oculto mediante id
          		$('#direccion').hide(); //muestro mediante id
            }
		});

      $("#registro").validate(rules_form);

      $("#guardar").click(function (){ 
  				if($('#registro').valid()) {
  					$.blockUI({message: 'Procesando por favor espere...'});
  				    $.ajax({
  				    	type: 'POST',
  				        url: $('#registro').attr("action"),
  				        data: $('#registro').serialize(),
  				        success: function (data) {
  	  				    	$.unblockUI();
  				            if(data == 'ok') {
  				             	swal({
  				             		title: 'Listo',
		                          	  text: '¡Registro exitoso!',
		                          	  type: "success",
		                          	  showCancelButton: false,
		                          	  confirmButtonColor: '#34AF00',
		                          	  confirmButtonText: 'Ok',
		                          	  closeOnConfirm: true,
		                          	  closeOnCancel: true
  				                },
  				                function(isConfirm){
  				                	if (isConfirm) {
  				                		irAPdf('registro/pdf/'+ $("#matricula").val());
  		        		            	$("#matricula").val("");
  				                    } 
  				                });
  				            } else if (data == 'nodisponible') {
  				            	swal({
  					            	title: 'Error',
		                         	  text: 'La Sede seleccionada ya no se encuentra disponible',
		                         	  type: 'error',
		                         	  showCancelButton: false,
		                         	  confirmButtonColor: '#C9302C',
		                         	  confirmButtonText: 'Ok',
		                         	  closeOnConfirm: true,   
		                         	  closeOnCancel: true
  				                },
  				                function(isConfirm){
  				                	if (isConfirm) {
  				                    	irA('registro/nuevo/'+ $('#matricula').val());
  				                    } 
  				                });
  				            } else {
  					            swal({
  					            	title: 'Error',
		                         	  text: 'Ocurri\xf3 un error, int\xe9ntelo m\xe1s tarde!!!',
		                         	  type: 'error',
		                         	  showCancelButton: false,
		                         	  confirmButtonColor: '#C9302C',
		                         	  confirmButtonText: 'Ok',
		                         	  closeOnConfirm: true,   
		                         	  closeOnCancel: true
  				                },
  				                function(isConfirm){
  				                	if (isConfirm) {
  				                    	irA('registro/nuevo/'+ $('#matricula').val());
  				                    } 
  				                });
  				            }
  				        }
  				     });
  			     }
  			 });
  		 
      }); //fin ready
  		 
  		function irA(uri) {
	        window.location.href = '<?= base_url() ?>' + uri;  
	    }  

  		function irAPdf(uri) {
            window.open('<?= base_url() ?>' + uri, '_blank');
        }	
</script>

<div class="register-container container">
	<div class="row">                
    	<div class="register">
        	<form role="form" class="form-horizontal" id="registro" name="registro" action="<?= base_url('registro/guardar') ?>" method="post">
        		<div style="text-align:left; padding-left:20px; border-bottom: 2px dotted #bbb; min-height:73px;">
                	<img src="resources/formulario/img/pleca_logos.png" class="img-responsive center-block" style="padding-top:10px;" align="top" />&nbsp;                        	
                </div>
                <?php if(isset($matricula)) { ?>
                <div id="datos_beneficiario" style="text-align:center; padding-top:10px;">
                	<div style="text-align:center !important;"><label class="leyenda" style="color:#E6007E; padding-left:20px;">Bienvenid@</label></div>	
		            <div style="text-align:justify;">
				  		Para registrarte a los talleres sigue los siguientes pasos:<br/>
				  		1. Verifica que los datos mostrados sean correctos, de lo contrario comun&iacute;cate al tel&eacute;fono 1102 1750 de L a V de 9:00 a 18:00 hrs para que puedan asesorarte.<br />
				  		2. Selecciona la Sede a donde deseas recibir los talleres y da click en el bot&oacute;n "Continuar".<br/><br/>
				  	</div>
				  	<div class="form-group">
    					<label class="control-label col-sm-offset-1 col-sm-1" style="text-align: left; color:#e6007e;" for="beneficiario">Beneficiari@: </label>
      					<label class="control-label col-sm-offset-1 col-sm-9" style="text-align: left;" for="beneficiario"><?=  (isset($beneficiario['nombre']) ? $beneficiario['nombre'] : ' ') . ' ' .  (isset($beneficiario['ap']) ?  $beneficiario['ap'] : ' ') . ' ' . (isset($beneficiario['am']) ? $beneficiario['am'] : ' ') ?></label>
  					</div>
  					<div class="form-group">
    					<label class="control-label col-sm-offset-1 col-sm-1" style="text-align: left; color:#e6007e;" for="institucion">Instituci&oacute;n: </label>
      					<label class="control-label col-sm-offset-1 col-sm-9" style="text-align: left;" for="institucion"><?=  isset($beneficiario['institucion']) ? $beneficiario['institucion'] : ' ' ?></label>
  					</div>
  					<div class="form-group">
    					<label class="control-label col-sm-offset-1 col-sm-1" style="text-align: left; color:#e6007e;" for="plantel">Plantel: </label>
      					<label class="control-label col-sm-offset-1 col-sm-9" style="text-align: left;" for="plantel"><?=  isset($beneficiario['plantel']) ? $beneficiario['plantel'] : ' ' ?></label>
  					</div>
  					<div class="form-group">
    					<label class="control-label col-sm-offset-1 col-sm-1" style="text-align: left; color:#e6007e;" for="matricula">Matr&iacute;cula: </label>
      					<label class="control-label col-sm-offset-1 col-sm-9" style="text-align: left;" for="matricula"><?=  isset($beneficiario['matricula_asignada']) ? $beneficiario['matricula_asignada'] : ' ' ?></label>
      					<input type="hidden" id="matricula" name="matricula" value="<?=  isset($beneficiario['matricula_asignada']) ? $beneficiario['matricula_asignada'] : ' ' ?>">
  					</div>
  					<div class="form-group">
    					<label class="control-label col-sm-offset-1 col-sm-1" style="text-align: left; color:#e6007e;" for="curp">CURP: </label>
      					<label class="control-label col-sm-offset-1 col-sm-9" style="text-align: left;" for="curp"><?=  isset($beneficiario['curp']) ? $beneficiario['curp'] : ' ' ?></label>
  					</div>
  					<div class="form-group">
    					<label class="control-label col-sm-offset-1 col-sm-1" style="text-align: left; color:#e6007e;" for="sede">Sede: </label>
    					<div class="col-sm-offset-1 col-sm-7" style="text-align: left;">
      						<select class="form-control" style="text-align: left;" id="sede" name="sede">
  								<option value="-1" style="text-align: center;">Selecciona una sede...</option>
  								<?php foreach ($sedes as $value){ ?>
			                        <option value="<?= $value['id_plantel'] ?>"><?= $value['plantel'] ?></option>
			                    <?php }?>
							</select>
      					</div>
  					</div>
  					<div class="form-group" style="; display: none;" id="direccion">
  						<label class="control-label col-sm-offset-1 col-sm-1" style="text-align: left; color:#e6007e;" for="direccion">Direcci&oacute;n: </label>
  						<div class="col-sm-offset-1 col-sm-9" style="text-align: left;" id="datos">
  						</div>
  					</div>
  					<div class="form-group" style="display:none;" id="talleres">
    					<label class="control-label col-sm-offset-1 col-sm-1" style="text-align: left; color:#e6007e;" for="taller">Talleres: </label><br/><br/>
      					<ul class="list-group">
      						<?php foreach ($talleres as $value){ ?>
			                        	<li class="list-group-item col-sm-6" style="text-align: left;"><img src="" class="img-thumbnail pull-xs-left" alt="<?= $value['archivo'] ?>"></img>&nbsp;<?= $value['taller'] ?></li>
	                        	<?php } ?>
      						
      					</ul>
  					</div>
  					<div style="text-align:right; color:#E60380 !important; cursor:pointer; width:96%;"> 
			        	<i><a href="javascript:muestraAviso();">Consultar nuestro aviso de privacidad</a></i>
		            </div>
		            <div class="form-group"> 
    					<div class="col-sm-offset-5 col-sm-2">
							<button id="guardar" type="button" value="Continuar" class="btn btn-primary">Continuar</button>
    					</div>
  					</div>
                </div>
                 <?php } else if(isset($disponible)) { 
		            				if($disponible == 1) { ?>
		            		<div class="form-group">
								<br>
								<table width="100%" border="0">		                        	
			        			<tr>
			        				<td>NO HAY SEDES DISPONIBLES EN ESTE MOMENTO</td>		                        		
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
		            				<?php } else if($disponible == 2) { ?>
		            				<div class="form-group">
										<br>
										<table width="100%" border="0">		                        	
					        			<tr>
					        				<td>NO HAY TALLERES DISPONIBLES EN ESTE MOMENTO</td>		                        		
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
		            						<?php } else { ?>
		            								<div class="form-group">
														<br>
														<table width="100%" border="0">		                        	
									        			<tr>
									        				<td>
									        					Te informamos que <strong>no podr&aacute;s realizar el registro a alg&uacute;n taller</strong> en tanto no <strong>regularices</strong> la 
																situaci&oacute;n que presenta el expediente que entregaste.<br /><br/>
																
																Para dar seguimiento a tu tr&aacute;mite, es indispensable que <strong>te presentes en las oficinas del 
																Programa</strong>, ubicadas en Lucas Alam&aacute;n #45 en la colonia Obrera, Delegaci&oacute;n Cuauht&eacute;moc 
																(Metro Doctores), de lunes a viernes de 9:00 a 17:00 hrs.<br/><br/>
																
																Te recomendamos que <strong>revises cu&aacute;les son los documentos aceptados y sus 
																caracter&iacute;sticas</strong> en el apartado V. Requisitos y Procedimientos de Acceso a las Reglas de 
																Operaci&oacute;n del Programa o en la Convocatoria vigente, ambas publicadas en 
																www.prepasi.df.gob.mx y que <strong>acudas con toda la documentaci&oacute;n que entregaste</strong>, ya que tu
																expediente puede presentar varias inconsistencias adem&aacute;s de la se&ntilde;alada, esto con la finalidad
																de evitar que acudas en varias ocasiones.<br /><br />
									        				</td>		                        		
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
					<?php } ?>
            </form>
        </div>
    </div>
</div>