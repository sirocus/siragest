<?
	// login o logout?
	if (isset($logout) || isset($_GET["logout"]) || isset($_POST["logout"])) {
		// logout
		session_start();
		unset($_SESSION['usuario']);
		unset($_SESSION['clave']);
		$_SESSION = array();
		session_destroy();
		$sessionPath = session_get_cookie_params(); 
		setcookie(session_name(), "", 0, $sessionPath["path"], $sessionPath["domain"]); 
	}else{
		/*
		// login
		if($_POST['recordar']){			
			$_SESSION["usuarioActivo"][0]->recordarPass(addslashes(strtolower($_POST['log_usuario'])));
		}
		if($_POST['guardar']){
			$_SESSION["usuarioActivo"][0]->guardarClave(addslashes($_POST['token']),addslashes($_POST['log_passwd']));
		}
		*/
		$loginError = $_SESSION["usuarioActivo"]->comprobarUsuario(addslashes($_POST['log_usuario']),addslashes($_POST['log_passwd']));		
	}
?>
