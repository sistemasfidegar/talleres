<?php 
	$CI			= &get_instance();
	$CRUD_AUTH  = $CI->session->userdata('CRUD_AUTH');
?>

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
		        	nombre: {required : true},
		        	paterno: {required : true},
		        	materno: {required : true},
		        	email: {required : true, estructuraemail: true},
		        	usuario: {required : true},
		        	password: {required : true, minlength: 8},
		        	password2: {required : true, passwordigual: true},
		        	sede: {required : true, selectNone: true}
		        },
		        messages: {
		        	nombre: {required: "Campo obligatorio"},
		        	paterno: {required: "Campo obligatorio"},
		        	materno: {required: "Campo obligatorio"},
		        	email: {required: "Campo obligatorio", estructuraemail: "Introduce un email v\xc1lido"},
		        	usuario: {required: "Campo obligatorio"},
		        	password: {required: "Campo obligatorio", minlength: "Introduce al menos 8 caract\xe9res"},
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

      $("#attributeForm").validate(rules_form);

      $("#guardar").click(function (){ 
  				if($('#attributeForm').valid()) {
  					$.blockUI({message: 'Procesando por favor espere...'});
  				    $.ajax({
  				    	type: 'POST',
  				        url: $('#attributeForm').attr("action"),
  				        data: $('#attributeForm').serialize(),
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
  				                    	irA('admin/nuevo');
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
  		 
      }); //fin ready
  		 
  		function irA(uri) {
	        window.location.href = '<?= base_url(); ?>' + uri;  
	    }  
</script>

<div class="register-container container">
	<div class="row">                
		<div class="register">
			<form id="attributeForm" class="form-horizontal" role="form" autocomplete="off">
				 <div style="text-align:left; padding-left:20px; border-bottom: 2px dotted #bbb; min-height:73px;">
                 	<a href="http://www.prepasi.df.gob.mx/">	<img  src="resources/formulario/img/logo_gdf_fidegar.png" style="padding-top:10px;" align="top" />&nbsp;</a>
                 </div>
                 <div style="text-align: center;">
				  		<strong>Perfil</strong>:<br/><br/>
				  </div>
                 <div class="form-group">
				    <label class="control-label col-sm-offset-1 col-sm-2" style="text-align: left;" for="nombre">Nombre:</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="paterno" name="paterno" type="text" readonly value="<?= isset($CRUD_AUTH['nombre']) ? $CRUD_AUTH['nombre'] : "" ?>"/>
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="control-label col-sm-offset-1 col-sm-2" style="text-align: left;" for="paterno">Apellido Paterno:</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="paterno" name="paterno" type="text" readonly value="<?= isset($CRUD_AUTH['apellido_paterno']) ? $CRUD_AUTH['apellido_paterno'] : "" ?>"/>
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="control-label col-sm-offset-1 col-sm-2" style="text-align: left;" for="materno">Apellido Materno:</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="materno" name="materno" type="text" readonly value="<?= isset($CRUD_AUTH['apellido_materno']) ? $CRUD_AUTH['apellido_materno'] : "" ?>"/>
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="control-label col-sm-offset-1 col-sm-2" style="text-align: left;" for="email">Correo electr&oacute;nico:</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="email" name="email" type="email" readonly value="<?= isset($CRUD_AUTH['email']) ? $CRUD_AUTH['email'] : "" ?>"/>
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="control-label col-sm-offset-1 col-sm-2" style="text-align: left;" for="usuario">Usuario:</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="usuario" name="usuario" type="text" readonly value="<?= isset($CRUD_AUTH['usuario']) ? $CRUD_AUTH['usuario'] : "" ?>"/>
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="control-label col-sm-offset-1 col-sm-2" style="text-align: left;" for="sede">Sede:</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="plantel[<?= isset($CRUD_AUTH['id_plantel']) ? $CRUD_AUTH['id_plantel'] : "" ?>]" name="plantel" type="text" readonly value="<?= isset($plantel['plantel']) ? $plantel['plantel'] : "" ?>"/>
				    </div>
				  </div>
				  <div style="text-align:right; color:#E60380 !important; cursor:pointer; width:96%;"> 
			        	<i><a href="javascript:muestraAviso();">Consultar nuestro aviso de privacidad</a></i>
		          </div>
				  <div class="form-group"> 
    				<div class="col-sm-offset-3 col-sm-3">
						<button id="modificar" type="button" class="btn btn-primary">Modificar</button>
    				</div>
    				<div class="col-sm-3">
						<button id="modificarpass" type="button" class="btn btn-primary">Modificar Contrase&ntilde;a</button>
    				</div>
  				</div>
			</form>
		</div>
	</div>
</div>  