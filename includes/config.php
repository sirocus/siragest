<?
	$nombreServer = str_replace(array('http://','https://','www.'),'',$_SERVER['HTTP_HOST']);
	//--
	//echo $nombreServer;
	switch($nombreServer){
		default:
		case 'localhost':		
			$bbdd=array('HOSTNAME'=>'localhost','USERNAME'=>'root','PASSWORD'=>'#','DATABASE'=>'siragest');
			define("URL","http://localhost/webs/siragest/");				
			break;
		case '192.168.1.231':			
			$bbdd=array('HOSTNAME'=>'localhost','USERNAME'=>'root','PASSWORD'=>'#','DATABASE'=>'siragest');
			define("URL","http://192.168.1.231/siragest/");						
			break;
		case 'apps.gesgrup.es':
			$bbdd=array('HOSTNAME'=>'localhost','USERNAME'=>'root','PASSWORD'=>'#','DATABASE'=>'siragest');
			define("URL","http://apps.gesgrup.es/");						
			break;		
	}						
	//--
	date_default_timezone_set('Europe/Madrid');		
	define("DEBUG",false);	
	define("HOMEDIR",dirname(dirname(__FILE__)));
	define("BASE_DIR",dirname(__FILE__)."/");
	//--	
	require_once HOMEDIR."/includes/funciones.php";		
	require_once HOMEDIR.'/includes/class.siragest.php';	
	require_once HOMEDIR."/includes/class.usuarios.php";	
	//--	
	if($_GET['web'] == "logout"){ $logout=true; $loginError=true; }
	session_start();
	if(!isset($_SESSION["usuarioActivo"])) $_SESSION["usuarioActivo"] = new usuarios();	
	if(empty($ajax)) include("includes/checkLogin.php");	
	//--
	if(empty($ajax)) include('directorios.php');
?>
