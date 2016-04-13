<?php 
	$CI = & get_instance(); 
	$crudAuth = $CI->input->post('crudAuth');
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
            	<img  src="resources/formulario/img/pleca_logos.png" style="padding-left:180px; padding-top:10px;" align="top" />&nbsp;
            </div>
			<div class="col-sm-6 col-md-4 col-md-offset-4">
				<div class="panel panel-default">
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
			</div>
		</div>
	</div>