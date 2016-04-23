<?php 
	$CI = & get_instance(); 
	$crudAuth = $CI->input->post('crudAuth');
	
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
	function cifrar(){
		var input_pass = document.getElementById("crudAuth[password]");
		input_pass.value = sha1(input_pass.value);
	}
</script>
	
<div class="register-container container">
	<div class="row">                
			<div style="text-align:left; padding-left:20px; border-bottom: 2px dotted #bbb; min-height:73px;">
            	<img  src="resources/formulario/img/pleca_logos.png" class="img-responsive center-block" style="padding-top:10px;" align="top" />&nbsp;
            </div>
			<div class="col-sm-6 col-md-4 col-md-offset-4">
				<div class="panel panel-default" style="<?php if($navegador=='IE'){ echo "display:none;"; }?>">
					<div class="panel-heading">
						<strong>Iniciar sesi&oacute;n para continuar</strong>
					</div>
					<div class="panel-body">
                        <?php if (!empty($crudAuth)) { ?>
                            <div class="alert alert-danger">
                            Nombre de usuario y/o contrase&ntilde;a incorrectos
                            </div>   
                        <?php } ?>
						<form role="form" method="POST" autocomplete="off">
							<fieldset>
								<div class="row">
									<div class="col-sm-12 col-md-10  col-md-offset-1 ">
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-user"></i>
												</span> 
												<input class="form-control" placeholder="Usuario" id="crudAuth[name]" name="crudAuth[name]" type="text" autofocus value="<?php if (isset($crudAuth['name'])) { echo htmlspecialchars($crudAuth['name']); } ?>"  required>
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-lock"></i>
												</span>
												<input class="form-control" placeholder="Contrase&ntilde;a" id="crudAuth[password]" name="crudAuth[password]" type="password" value="<?php if (isset($crudAuth['password'])) { echo htmlspecialchars($crudAuth['password']); } ?>"  required>
											</div>
										</div>
										<div class="form-group">
											<input type="submit" class="btn btn-primary btn-block" value="Iniciar Sesi&oacute;n" onclick="cifrar()">
										</div>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
                </div>
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
			</div>
		</div>
	</div>