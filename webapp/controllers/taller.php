
<script type="text/javascript">
        jQuery(document).ready(function(){


    		$("#guardar").click(function () {
    			if($("#matricula_asignada").val() != ""  ) //&& $("#matricula_asignada").val()
    	        {
    				$.blockUI({message: 'Procesando por favor espere...'});
    	        	jQuery.ajax({
    		            type: 'post',
    		            dataType: 'html',
    		            url: '../..',
    		            data: {matricula: $("#matricula_asignada").val()},
    		            success: function (data) {
        		            if(data!="bad")
        		            {
            		            if(data!="aceptado"){
	    		            		
            		            	
	    		            		irA('../../'+data);
            		            }
            		            else
            		            {
            		            	$.unblockUI();
            		            	$('#letrero').html('<span style="color:#E6007E; font-size:12px;">El expediente del Beneficiario ya fue aprobado en el periodo de Inscripción-Reinscripción (2015-2016).<br>No es posible hacer modificaciones a la información</span>');
            		            }   	               
        		            }
        		            else
        		            {
            		            alert('No se encontró al beneficiario');
            		            irA('..');

            		        }
    		            }
    		            
    		        });
             
    	        }
    			else if($("#matricula_escuela").val()!= "" ){

    				$.blockUI({message: 'Procesando por favor espere...'});
    	        	jQuery.ajax({
    		            type: 'post',
    		            dataType: 'html',
    		            url: '../../',
    		            data: {matricula_escuela: $("#matricula_escuela").val()},
    		            success: function (data) {

        		            if(data!="bad")
        		            {
	    		            	matricula = data;
	    		            	irA('../../'+matricula);	               
        		            }
        		            else
        		            {
            		            alert('No se encontró al beneficiario');
            		            irA('..');
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
<div class="register-container container">
	<div class="row">                
		<div class="register">
			<form role="form" id="buscar_beneficiario" name="buscar_beneficiario" action="../.." method="post">
				 <div style="text-align:left; padding-left:20px; border-bottom: 2px dotted #bbb; min-height:73px;">
                 	<a href="http://www.prepasi.df.gob.mx/">	<img  src="resources/formulario/img/logo_gdf_fidegar.png" style="padding-top:10px;" align="top" />&nbsp;</a>
                       	<!-- <img src="../resources/formulario/img/tit_sistema.png" style="padding-top:10px;" align="top" />  -->                        	
                 </div>
                 <br>
                 	<div style="text-align:CENTER !important;"><label class="leyenda" style="color:#E6007E; padding-left:20px;"> CORRECCI&Oacute;N DE DATOS </label></div>
						<table width="620" border="0" align="center" cellpadding="0" cellspacing="0">
							 <tr>
							   	<td bgcolor="">
							    	<table width="95%" border="0" align="center" cellpadding="0" cellspacing="5">
							    	    <tr>
							        		<td colspan="2" align="center" class="">Elige un método de búsqueda:</td>
							          	</tr>      
							        	 <tr>
							         		<td colspan="2">&nbsp;</td>
								        </tr>
								        <tr>
								          <td colspan="2"><input type="text" id="matricula_asignada" name="matricula_asignada" value="" placeholder="                 Ingresa tu matrícula PS o CURP" style="width:80%; text-transform:uppercase;"/></td>
							        	</tr>
								         <tr>
								          <td colspan="2">&nbsp;</td>
								        </tr>
								        <tr>
								          <td colspan="2"><input type="text" id="matricula_escuela" name="matricula_escuela" value="" placeholder="                    Ingresa matrícula (unam)" style="width:80%; text-transform:uppercase;"/></td>
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
						
				</form>
			</div>
		</div>
	</div>
		 							