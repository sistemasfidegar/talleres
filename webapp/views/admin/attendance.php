<script type="text/javascript">
		jQuery(document).ready(function(){
			var rules_form = {
			        rules: {
			        	taller: {required : true, selectNone: true}
			        },
			        messages: {
			        	taller: {required: "Campo obligatorio", selectNone: "Debe seleccionar una opci\xf3n"}
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

			 // Bot&oacute;n de Exportar a Excel
			$("#btn-excel").click(function(e) {
				$("#datos_a_enviar").val($("<div>").append( $("#tbl-export").eq(0).clone()).html());
				$("#FormularioExportacion").submit();
			});

			$("#taller").change(function () {
				if ($('#taller').val() != '-1') {
					$.blockUI({message: 'Procesando por favor espere...'});
					var taller = $("#taller option:selected").val();
	
			        jQuery.ajax({
			            type: 'post',
			            dataType: 'html',
			            url: 'admin/ajaxGetBeneficiarios/' + taller,
			            data: {operacion: 'ajax'},
			            success: function (data) {
			            	$.unblockUI();
		                	$('#datos').html(data);
		                	$('#btn-excel').show();	               
		            	}
			        });
		        } else {
	          		$('#datos').hide(); //oculto mediante id
	          		$('#btn-excel').hide(); //oculto mediante id
	            }
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
        });

		function irA(uri) {
	        window.location.href = '<?= base_url(); ?>' + uri;  
	    } 
</script>

<div class="panel-body">
	<div class="text-right">
		<form action="<?= base_url('admin/excelPdf') ?>" method="post" target="_blank" id="FormularioExportacion">
			<div style="text-align:left; padding-left:20px; border-bottom: 2px dotted #bbb; min-height:73px;">
                 	<a href="<?= base_url('asistencia') ?>"><img  src="resources/formulario/img/pleca_logos.png" alt="Logo" class="img-responsive center-block" style="padding-top:10px; vertical-align:top;" />&nbsp;</a>
            </div>
            <div class="input-group input-group-md">
			<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
			<input id="filtrar" type="text" class="form-control" placeholder="Buscar....">
			</div><br>
			<div style="text-align: center;">
		  		<strong style="font-size: 150%;"><?= isset($plantel['plantel']) ? $plantel['plantel'] : "" ?></strong><br/><br/>
			</div>
			<div class="form-group">
    			<label class="control-label col-sm-offset-1 col-sm-2" style="text-align: right; color:#4C4C4C;" for="taller">Taller: </label>
    			<div class="col-sm-7" style="text-align: right;">
      				<select class="form-control" style="text-align: left;" id="taller" name="taller">
  						<option value="-1" style="text-align: center;">Selecciona un taller...</option>
  						<?php foreach ($talleres as $value){ ?>
			               	<option value="<?= $value['id_taller'] ?>"><?= $value['taller'] ?></option>
			            <?php }?>
					</select>
      			</div>
  			</div>
			<button type="button" id="btn-excel" style="display: none;" class="btn btn-default btn-xs" data-datos="">
				<span class="glyphicon glyphicon-export"></span> Exportar a Excel
			</button>
			<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
		</form>
		</div><br>
        <div id="datos" style="overflow: auto;">
        </div>
</div>