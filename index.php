<? include('includes/config.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Marc Torrente Cesteros @ SIRAstudio.net">
    <link rel="shortcut icon" href="<?=URL?>/img/favicon.ico">
    <title>SIRAgest</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=URL?>css/bootstrap.css">
    <link rel="stylesheet" href="<?=URL?>css/sb-admin.css">    
    <? /* ?><link rel="stylesheet" href="<?=URL?>font-awesome/css/font-awesome.min.css"><? */ ?>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">    
    <? /* ?><link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.3.min.css"><? */ ?>
    <?=$css?>    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<? if(empty($_SESSION["usuarioActivo"]->usuarioLogin)){ ?>
    <div class="container">
    	<div class="row">
    		<div class="col-md-4 col-md-offset-4">
    			<div class="login-panel panel panel-default">
    				<div class="panel-heading"><h3 class="panel-title">Acceso usuarios</h3></div>
					<div class="panel-body">
					<form action="<?=URL?>" method="post" enctype="multipart/form-data" class="form-signin" role="form">
						<fieldset>
							<div class="form-group"><input id="log_usuario" name="log_usuario" type="text" maxlength="100" class="form-control" placeholder="Usuario" required autofocus></div>
							<div class="form-group"><input id="log_passwd" name="log_passwd" type="password" maxlength="15" class="form-control" placeholder="Password" required></div>
                            <button class="btn btn-lg btn-success btn-block" type="submit">Acceder</button>
    					</fieldset>
    				</form>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
<? }else{ ?>    
	<div id="wrapper">
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Movil -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=URL?>"><img src="<?=URL?>img/logo.png" width="150"></a>
            </div><!-- /Movil -->
            <!-- Menu lateral -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
	                <? $active = empty($_GET['seccion']) ? ' class="active"' : ''; ?>
                    <li<?=$active?>><a href="<?=URL?>"><i class="fa fa-dashboard"></i> Portada</a></li>
					<?
                    if(is_array($menu) && count($menu)>0){
						foreach($menu as $seccion=>$seccionDatos){					
							$active = !empty($seccionDatos['selected']) ? ' active open' : '';						
					?>
                    <li class="dropdown<?=$active?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="<?=$seccionDatos['icono']?>"></i> <?=ucfirst($seccion)?> <b class="caret"></b></a>
                    <?
							if(is_array($seccionDatos['apartados']) && count($seccionDatos['apartados'])>0){
					?>
						<ul class="dropdown-menu">
                    <?
								foreach($seccionDatos['apartados'] as $apartado=>$apartadoDatos){
									if(!empty($apartadoDatos['activo'])){
										$active = !empty($apartadoDatos['selected']) ? ' class="active"' : '';																			
					?>
							<li<?=$active?>><a href="<?=$apartadoDatos['url']?>"><i class="<?=$apartadoDatos['icono']?>"></i> <?=$apartado?></a></li>                    
					<?
									}
								}
					?>
	                    </ul>                    
					<?
							}
						}
					?>
					</li>
                    <?
					}
                    ?>                                                   
                </ul>
                <ul class="nav navbar-nav navbar-right navbar-user">
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Bienvenido <?=$_SESSION["usuarioActivo"]->usuario_Nombre?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#"><i class="fa fa-user"></i> Perfil</a></li>
                            <li class="divider"></li>
                            <li><a href="<?=$SIRAgest->urlAmigas(URL.'index.php?logout=true')?>"><i class="fa fa-power-off"></i> Salir</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /Menu lateral -->
        </nav>
		<!-- Includes -->
		<div id="page-wrapper"><? include($pagInclude); ?></div><!-- /Includes -->
	</div>
<? } ?>   
    <!-- JavaScript -->
    <script src="<?=URL?>js/jquery-1.10.2.js"></script>
    <script src="<?=URL?>js/bootstrap.js"></script>
    <script src="<?=URL?>js/general.js"></script>        
    <? /* ?><script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="http://cdn.oesmith.co.uk/morris-0.4.3.min.js"></script>
    <script src="<?=URL?>js/morris/chart-data-morris.js"></script><? */ ?>
    <script src="<?=URL?>js/tablesorter/jquery.tablesorter.js"></script>
    <script src="<?=URL?>js/tablesorter/tables.js"></script>
    <?=$javascripts?>
</body>
</html>