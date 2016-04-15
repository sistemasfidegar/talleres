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

		jQuery(document).ready(function(){
			var rules_form = {
			        rules: {
			        	nombre: {required : true, maxlength: 50},
			        	paterno: {required : true, maxlength: 50},
			        	materno: {required : true, maxlength: 50},
			        	email: {required : true, estructuraemail: true, maxlength: 80},
			        	sede: {required : true, selectNone: true}
			        },
			        messages: {
			        	nombre: {required: "Campo obligatorio", maxlength: "Introduce m\xc1ximo 50 caract\xe9res"},
			        	paterno: {required: "Campo obligatorio", maxlength: "Introduce m\xc1ximo 50 caract\xe9res"},
			        	materno: {required: "Campo obligatorio", maxlength: "Introduce m\xc1ximo 50 caract\xe9res"},
			        	email: {required: "Campo obligatorio", estructuraemail: "Introduce un email v\xc1lido", maxlength: "Introduce m\xc1ximo 80 caract\xe9res"},
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

	      $("#editModal #datos").validate(rules_form);
	      
			 // Bot&oacute;n de Exportar a Excel
			$("#btn-excel").click(function(e) {
				$("#datos_a_enviar").val( $("<div>").append( $("#tbl-export").eq(0).clone()).html());
				$("#FormularioExportacion").submit();
			});
			
			//Bot&oacute;n Buscar
			(function ($) {
                $('#filtrar').keyup(function () {
                    var rex = new RegExp($(this).val(), 'i');
                    $('.buscar tr').hide();
                    $('.buscar tr').filter(function () {
                        return rex.test($(this).text());
                    }).show();
                })
            }(jQuery));

			//Modal Eliminar
			$('#deleteModal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget); // Button that triggered the modal
				var recipient = button.data('whatever'); // Extract info from data-* attributes
				var idUser = button.data('user'); // Extract info from data-* attributes
				// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
				// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
				var modal = $(this);
				modal.find('.modal-body input').val(recipient);
				modal.find('.modal-footer button').attr('data-user', idUser);
			});

			//Modal Editar
			$('#editModal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget); // Button that triggered the modal
				var nombre = button.data('nombre'); // Extract info from data-* attributes
				var paterno = button.data('paterno'); // Extract info from data-* attributes
				var materno = button.data('materno'); // Extract info from data-* attributes
				var email = button.data('email'); // Extract info from data-* attributes
				var plantel = button.data('plantel'); // Extract info from data-* attributes
				var idplantel = button.data('idplantel'); // Extract info from data-* attributes
				var idUser = button.data('user'); // Extract info from data-* attributes
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

			//Resetear el valor y estilo del form del Modal Edit
			$("#editModal").on("hidden.bs.modal", function(){
				$(this).find('.modal-body').closest('.row').css({"border-style":"solid","border-color":"#A4A4A4","border-width":"1px"});
			});

			//Submit Modal Eliminar
			$("#delete").click(function(){
				$.blockUI({message: 'Procesando por favor espere...'});
				$.ajax({
					type: "POST",
					url: "<?= base_url('/admin/delete') ?>",
					data: {usuarioId: $(this).attr('data-user')},
					success: function(msg){
						$('#deleteModal').modal('hide'); //hide popup
						$.unblockUI();
						if (msg == 'ok') {
                            swal({
				             	title: 'Listo',
	                          	  text: '¡Eliminado exitoso!',
	                          	  type: "success",
	                          	  showCancelButton: false,
	                          	  confirmButtonColor: '#34AF00',
	                          	  confirmButtonText: 'Ok',
	                          	  closeOnConfirm: true,
	                          	  closeOnCancel: true
				                },
				                function(isConfirm){
				                	if (isConfirm) {
				                    	irA('admin/listar');
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
			});

			//Submit Modal Editar
			$("#edit").click(function(){
				if($('#editModal #datos').valid()) {
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
					                    	irA('admin/listar');
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
        });

		function irA(uri) {
	        window.location.href = '<?= base_url(); ?>' + uri;  
	    } 
</script>

	<div class="modal fade" tabindex="-1" role="dialog" id="deleteModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Eliminar Usuario</h4>
				</div>
				<div class="modal-body">
					<form id="datos">
						<div class="form-group">
							<label for="name" class="control-label">¿Est&aacute; seguro que desea eliminar el registro de:?</label>
							<input type="text" class="form-control" id="name" name="name">
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="submit" id="delete" class="btn btn-primary" data-user="">S&iacute;</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Editar Usuario</h4>
				</div>
				<div class="modal-body">
					<form id="datos" autocomplete="off">
						<div class="row">
							<div class="col-sm-3">
								<label class="control-label" style="text-align: left;" for="nombre">Nombre:</label>
							</div>
				    		<div class="col-sm-9">
				      			<input class="form-control" id="nombre" name="nombre" type="text" placeholder="Introduzca su nombre" autofocus value=""/>
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
	
<div class="panel-body">
	<div class="text-right">
		<form action="<?= base_url('admin/excel') ?>" method="post" target="_blank" id="FormularioExportacion">
			<div style="text-align:left; padding-left:20px; border-bottom: 2px dotted #bbb; min-height:73px;">
                 	<a href="<?= base_url('asistencia') ?>">	<img  src="resources/formulario/img/pleca_logos.png" class="img-responsive center-block" style="padding-top:10px;" align="top" />&nbsp;</a>
            </div>
            <div style="text-align: center;">
				  		<strong>Listado de Usuarios:</strong><br/><br/>
			</div>
            <div class="input-group input-group-md">
			<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
			<input id="filtrar" type="text" class="form-control" placeholder="Buscar....">
			</div><br>
			<button type="button" id="btn-excel" class="btn btn-default btn-xs" data-datos="">
				<span class="glyphicon glyphicon-export"></span> Exportar a Excel
			</button>
			<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
		</form>
		</div><br>
        <div style="overflow: auto;">
        	<?php if(isset($usuarios)) {
        			echo $usuarios;
        	}?>
        </div>
</div>