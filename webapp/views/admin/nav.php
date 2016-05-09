<?php 
	$CI			= &get_instance();
	$CRUD_AUTH  = $CI->session->userdata('CRUD_AUTH');
?>

<!-- Navigation -->
    <nav class="navbar navbar-default navbar-custom navbar-fixed-top">
    	<div class="container-fluid">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= base_url('asistencia') ?>">Bienvenid@ <?= isset($CRUD_AUTH['usuario']) ? $CRUD_AUTH['usuario'] : ""  ?></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <?php if($CRUD_AUTH) { ?>
                    <?php if($CRUD_AUTH['perfil'] == 'Administrador' || $CRUD_AUTH['perfil'] == 'Programador') { ?>
                    <li class="dropdown">
  						<a type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    						Usuarios <span class="caret"></span>
  						</a>
  						<ul class="dropdown-menu">
    						<li><a href="<?= base_url('admin/nuevo') ?>">Nuevo Usuario</a></li>
    						<li><a href="<?= base_url('admin/listar') ?>">Listar usuarios</a></li>
  						</ul>
					</li>
					<?php } ?>
					<li>
                        <a href="<?= base_url('admin/attendance') ?>">Lista Asistencia</a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/profile') ?>">Perfil</a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/logout') ?>">Cerrar Sesi&oacute;n</a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
          </div>
        <!-- /.container -->
    </nav><br/><br/>