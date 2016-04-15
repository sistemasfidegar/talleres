<script type="text/javascript">
	function cifrar(){
		var input_pass = document.getElementById("actual");
		input_pass.value = sha1(input_pass.value);
		var input_pass2 = document.getElementById("password");
		input_pass2.value = sha1(input_pass2.value);
		var input_pass3 = document.getElementById("password2");
		input_pass3.value = sha1(input_pass3.value);
	}
	
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
		        	nombre: {required : true, maxlength: 50},
		        	paterno: {required : true, maxlength: 50},
		        	materno: {required : true, maxlength: 50},
		        	email: {required : true, estructuraemail: true, maxlength: 80},
		        	usuario: {required : true, maxlength: 20},
		        	password: {required : true, minlength: 8, maxlength: 255},
		        	password2: {required : true, passwordigual: true},
		        	sede: {required : true, selectNone: true}
		        },
		        messages: {
		        	nombre: {required: "Campo obligatorio", maxlength: "Introduce m\xc1ximo 50 caract\xe9res"},
		        	paterno: {required: "Campo obligatorio", maxlength: "Introduce m\xc1ximo 50 caract\xe9res"},
		        	materno: {required: "Campo obligatorio", maxlength: "Introduce m\xc1ximo 50 caract\xe9res"},
		        	email: {required: "Campo obligatorio", estructuraemail: "Introduce un email v\xc1lido", maxlength: "Introduce m\xc1ximo 80 caract\xe9res"},
		        	usuario: {required: "Campo obligatorio", maxlength: "Introduce m\xc1ximo 20 caract\xe9res"},
		        	password: {required: "Campo obligatorio", minlength: "Introduce al menos 8 caract\xe9res", maxlength: "Introduce m\xc1ximo 255 caract\xe9res"},
		        	password2: {required : "Campo obligatorio", passwordigual: "La confirmaci\xf3n de contrase\xf1a no coincide"},
		        	sede: {required: "Campo obligatorio", selectNone: "Debe seleccionar una opci\xf3n"}
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
      	}, "Debe seleccionar una opci\xf3n");

      	 jQuery.validator.addMethod("estructuraemail", function (value, element) {
     			 var patron=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
     				
     				if (patron.test(element.value)){
     						return true;
     				} else {
     						return false;
     				}
     			}, "Introduce un email v\xc1lido");

      	jQuery.validator.addMethod("passwordigual",function (value, element) {
    				var password = $('#password').val().toString();
    				
    				if (element.value != password)
    					return false;
    				 else 
    				    return true;
    				}, "La confirmaci\xf3n de contrase\xf1a no coincide");

      $("#editModal #datos").validate(rules_form);
      $("#editPassModal #datos").validate(rules_form);

    	//Modal Editar
		$('#editModal').on('show.bs.modal', function (event) {
			var nombre = $('#attributeForm').find('#nombre').val();
			var paterno = $('#attributeForm').find('#paterno').val();
			var materno = $('#attributeForm').find('#materno').val();
			var email = $('#attributeForm').find('#email').val();
			var plantel = $('#attributeForm').find('#plantel').val();
			var idplantel = $('#attributeForm').find('#plantel').attr('data-idplantel');
			var idUser = $('#attributeForm').find('#usuario').attr('data-iduser');
			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			var modal = $(this);
			modal.find('#nombre').val(nombre);
			modal.find('#paterno').val(paterno);
			modal.find('#materno').val(materno);
			modal.find('#email').val(email);
			modal.find('#sede :selected').val(idplantel);
			modal.find('#sede :selected').text(plantel);
			modal.find('.modal-footer button').attr('data-user', idUser);
		});

		//Modal Editar
		$('#editPassModal').on('show.bs.modal', function (event) {
			var idUser = $('#attributeForm').find('#usuario').attr('data-iduser');
			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			var modal = $(this);
			modal.find('.modal-footer button').attr('data-user', idUser);
		});

		//Resetear los valores y estilo del form del Modal Edit Pass
		$("#editPassModal").on("hidden.bs.modal", function(){
			$(this).find('.modal-body input').val("");
			$(this).find('.modal-body').closest('.row').css({"border-style":"solid","border-color":"#A4A4A4","border-width":"1px"});
		});

		//Actualizamos el Perfil
		$("#edit").click(function(){
			if($('#editPassModal #datos').valid()) {
				$.blockUI({message: 'Procesando por favor espere...'});
				$.ajax({
					type: "POST",
					url: "<?= base_url('/admin/edit') ?>",
					data: {usuarioId: $(this).attr('data-user'), nombre: $("#nombre").val(), paterno: $("#paterno").val(), materno: $("#materno").val(),
						email: $("#email").val(), sede: $("#sede").val()},
					success: function(msg){
						$('#editModal').modal('hide'); //hide popup
						$.unblockUI();
						if (msg == 'ok') {
                            swal({
				             	title: 'Listo',
	                          	  text: '¡Editado exitoso!',
	                          	  type: "success",
	                          	  showCancelButton: false,
	                          	  confirmButtonColor: '#34AF00',
	                          	  confirmButtonText: 'Ok',
	                          	  closeOnConfirm: true,
	                          	  closeOnCancel: true
				                },
				                function(isConfirm){
				                	if (isConfirm) {
				                    	irA('admin/profile');
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
				                });
						}
					}
				});
			}
		});

		//Actualizamos la Contrase&ntilde;a
		$("#editPass").click(function(){
			if($('#editPassModal #datos').valid()) {
				$.blockUI({message: 'Procesando por favor espere...'});
				$.ajax({
					type: "POST",
					url: "<?= base_url('/admin/editPass') ?>",
					data: {usuarioId: $(this).attr('data-user'), actual: $("#actual").val(), password: $("#password").val()},
					success: function(msg){
						$('#editPassModal').modal('hide'); //hide popup
						$.unblockUI();
						if (msg == 'ok') {
                            swal({
				             	title: 'Listo',
	                          	  text: '¡Editado exitoso!',
	                          	  type: "success",
	                          	  showCancelButton: false,
	                          	  confirmButtonColor: '#34AF00',
	                          	  confirmButtonText: 'Ok',
	                          	  closeOnConfirm: true,
	                          	  closeOnCancel: true
                            });
                        } else if (msg == 'nocoincide') {
                        	swal({
				            	title: 'Error',
	                         	  text: 'La contrase\xf1a actual ingresada no es correcta!!!',
	                         	  type: 'error',
	                         	  showCancelButton: false,
	                         	  confirmButtonColor: '#C9302C',
	                         	  confirmButtonText: 'Ok',
	                         	  closeOnConfirm: true,   
	                         	  closeOnCancel: true
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
				                });
						}
					}
				});
			}
		});
      }); //fin ready
  		 
  		function irA(uri) {
	        window.location.href = '<?= base_url(); ?>' + uri;  
	    }  
</script>

	<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Editar Perfil</h4>
				</div>
				<div class="modal-body">
					<form id="datos" autocomplete="off">
						<div class="row">
							<div class="col-sm-3">
								<label class="control-label" style="text-align: left;" for="nombre">Nombre:</label>
							</div>
				    		<div class="col-sm-9">
				      			<input class="form-control" id="nombre" name="nombre" type="text" placeholder="Introduzca su nombre" value="" autofocus/>
				    		</div>
				  		</div>
					  	<div class="row">
					  		<div class="col-sm-3">
								<label class="control-label" style="text-align: left;" for="nombre">Apellido Paterno:</label>
							</div>
						    <div class="col-sm-9">
						      <input class="form-control" id="paterno" name="paterno" type="text" placeholder="Introduzca su apellido paterno" value=""/>
						    </div>
					  	</div>
						<div class="row">
							<div class="col-sm-3">
								<label class="control-label" style="text-align: left;" for="nombre">Apellido Materno:</label>
							</div>
						    <div class="col-sm-9">
						      <input class="form-control" id="materno" name="materno" type="text" placeholder="Introduzca su apellido materno" value=""/>
						    </div>
						 </div>
						 <div class="row">
						  	<div class="col-sm-3">
								<label class="control-label" style="text-align: left;" for="email">Correo electr&oacute;nico:</label>
							</div>
						    <div class="col-sm-9">
						      <input class="form-control" id="email" name="email" type="email" placeholder="Introduzca su correo electr&oacute;nico" value=""/>
						    </div>
					  	</div>
					  	<div class="row">
							<div class="col-sm-3">
								<label class="control-label" style="text-align: left;" for="email">Sede:</label>
							</div>
						    <div class="col-sm-9">
						    	<select class="form-control" style="text-align: left;" id="sede" name="sede">
  									<option value="-1">Selecciona una sede...</option>
  									<option value="" selected="selected"></option>
  									<?php foreach ($sedes as $value){ ?>
			                			<option value="<?= $value['id_plantel'] ?>"><?= $value['plantel'] ?></option>
			                		<?php }?>
					  			</select>
						    </div>
					  	</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" id="edit" class="btn btn-primary" data-user="">Actualizar</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="modal fade" tabindex="-1" role="dialog" id="editPassModal">
    	<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Cambiar Contrase&ntilde;a</h4>
				</div>
				<div class="modal-body">
					<form id="datos" autocomplete="off">
						<div class="row">
							<div class="col-sm-3">
								<label class="control-label" style="text-align: left;" for="actual">Contrase&ntilde;a actual:</label>
							</div>
				    		<div class="col-sm-9">
				      			<input class="form-control" id="actual" name="actual" type="password" placeholder="Introduzca su contrase&ntilde;a actual" value="" autofocus/>
				    		</div>
				  		</div>
					  	<div class="row">
					  		<div class="col-sm-3">
								<label class="control-label" style="text-align: left;" for="nombre">Nueva contrase&ntilde;a:</label>
							</div>
						    <div class="col-sm-9">
						      <input class="form-control" id="password" name="password" type="password" placeholder="Introduzca su nueva contrase&ntilde;a" value=""/>
						    </div>
					  	</div>
						<div class="row">
							<div class="col-sm-3">
								<label class="control-label" style="text-align: left;" for="nombre">Confirme su contrase&ntilde;a:</label>
							</div>
						    <div class="col-sm-9">
						      <input class="form-control" id="password2" name="password2" type="password" placeholder="Confirme su nueva contrase&ntilde;a" value=""/>
						    </div>
						 </div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" id="editPass" class="btn btn-primary" data-user="" onclick="cifrar()">Actualizar</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

<div class="register-container container">
	<div class="row">                
		<div class="register">
			<form id="attributeForm" class="form-horizontal" role="form" autocomplete="off">
				 <div style="text-align:left; padding-left:20px; border-bottom: 2px dotted #bbb; min-height:73px;">
                 	<a href="<?= base_url('asistencia') ?>">	<img  src="resources/formulario/img/pleca_logos.png" class="img-responsive center-block" style="padding-top:10px;" align="top" />&nbsp;</a>
                 </div>
                 <div style="text-align: center;">
				  		<strong>Perfil</strong>:<br/><br/>
				  </div>
                 <div class="form-group">
				    <label class="control-label col-sm-offset-1 col-sm-2" style="text-align: left;" for="nombre">Nombre:</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="nombre" name="nombre" type="text" readonly value="<?= isset($usuario['nombre']) ? $usuario['nombre'] : "" ?>"/>
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="control-label col-sm-offset-1 col-sm-2" style="text-align: left;" for="paterno">Apellido Paterno:</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="paterno" name="paterno" type="text" readonly value="<?= isset($usuario['apellido_paterno']) ? $usuario['apellido_paterno'] : "" ?>"/>
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="control-label col-sm-offset-1 col-sm-2" style="text-align: left;" for="materno">Apellido Materno:</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="materno" name="materno" type="text" readonly value="<?= isset($usuario['apellido_materno']) ? $usuario['apellido_materno'] : "" ?>"/>
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="control-label col-sm-offset-1 col-sm-2" style="text-align: left;" for="email">Correo electr&oacute;nico:</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="email" name="email" type="email" readonly value="<?= isset($usuario['email']) ? $usuario['email'] : "" ?>"/>
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="control-label col-sm-offset-1 col-sm-2" style="text-align: left;" for="usuario">Usuario:</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="usuario" name="usuario" type="text" data-iduser="<?= isset($usuario['id_usuario']) ? $usuario['id_usuario'] : "" ?>" readonly value="<?= isset($usuario['usuario']) ? $usuario['usuario'] : "" ?>"/>
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="control-label col-sm-offset-1 col-sm-2" style="text-align: left;" for="sede">Sede:</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="plantel" data-idplantel="<?= isset($usuario['id_plantel']) ? $usuario['id_plantel'] : "" ?>" name="plantel" type="text" readonly value="<?= isset($plantel['plantel']) ? $plantel['plantel'] : "" ?>"/>
				    </div>
				  </div>
				  <div class="form-group"> 
    				<div class="col-sm-offset-3 col-sm-3">
						<button id="modificar" type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal">Modificar Perfil</button>
    				</div>
    				<div class="col-sm-3">
						<button id="modificarpass" type="button" class="btn btn-primary" data-toggle="modal" data-target="#editPassModal">Modificar Contrase&ntilde;a</button>
    				</div>
  				</div>
			</form>
		</div>
	</div>
</div>  