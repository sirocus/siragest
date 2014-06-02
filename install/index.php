<?
	$ajax=true;
	include('../includes/config.php');
	$install = $SIRAgest->testInstall($_POST);
	$linkForm = URL.'install/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?=URL?>/img/favicon.ico">
    <title>SIRAgest instalacion</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="<?=URL?>css/siragest.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	<div class="container">
    <form action="<?=$linkForm?>" method="post" enctype="multipart/form-data" class="form-signin" role="form">
    <?
	switch($install['step']){
		default:
	?>
    	<h1 class="form-signin-heading">Instalación SIRAgest 1.0</h1>
    	<h2 class="form-signin-heading">Crear bbdd</h2>        
		<input id="hostname" name="hostname" type="text" class="form-control" placeholder="Host name" required autofocus>
    	<input id="username" name="username" type="text" class="form-control" placeholder="User name" required>
    	<input id="password" name="password" type="password" class="form-control" placeholder="Password" required>        
    	<input id="database" name="database" type="text" class="form-control" placeholder="Database" required>                  		
    	<button class="btn btn-lg btn-primary btn-block" type="submit">Dale ya!</button>        
    <?
			break;
		case 1:
	?>
        <h1 class="form-signin-heading">Instalación SIRAgest 1.0</h1>
        <h2 class="form-signin-heading">Crear admin</h2>     
        <input id="username" name="username" type="text" class="form-control" placeholder="User name" required>
        <input id="password" name="password" type="password" class="form-control" placeholder="Password" required>             
        <input id="email" name="email" type="password" class="form-control" placeholder="E-mail" required>                         
        <input id="step" name="step" type="hidden" value="1">
    	<button class="btn btn-lg btn-primary btn-block" type="submit">Crea Admin!</button>          
    <?
			break;
		case 2:
	?>
        <h1 class="form-signin-heading">Instalación SIRAgest 1.0</h1>
        <h2 class="form-signin-heading">Buscar actualizaciones</h2>      
	<?
			break;
	}
    ?>
    </form>
    </div> 
   	<!-- /container -->
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
</body>
</html>