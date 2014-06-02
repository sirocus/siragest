<?
if(!defined('BASE_DIR')) die("No se puede aceder esta página directamente");
else{
	$link = URL.'index.php?seccion=gestion&apartado=usuarios';
	$linkForm = $link.'&opcion=nuevo';
?>
    <div class="row">
    	<div class="col-lg-11">
    		<ol class="breadcrumb">
    			<li><i class="fa fa-wrench"></i> Gestión</li>
    			<li><i class="fa fa-user"></i> Usuarios</li>
    			<li class="active"><i class="fa fa-plus-square"></i> Nuevo</li>                
    		</ol>
	    </div>
    	<div class="col-lg-1"><a href="<?=$SIRAgest->urlAmigas($link)?>"><button type="button" class="btn btn-success">Volver</button></a></div>        
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Nuevo usuario</div>
                <div class="panel-body">
                    <div class="row">
                        <form action="<?=$SIRAgest->urlAmigas($linkForm)?>" method="post" enctype="multipart/form-data" role="form">                    
                    	<div class="col-lg-6">
							<input name="debugar" id="debugar" type="hidden" value="<?=$debugar?>" />
							<input name="idReg" id="idReg" type="hidden" value="<?=$idReg?>" />                            
                    		<div class="form-group"><label>Usuario</label><input id="usuario" name="usuario" type="text" maxlength="100" class="form-control" value="<?=$usuario?>" placeholder="Email" <?=$inputUsuario?>></div>
                    		<div class="form-group"><label>Password</label><input id="password" name="password" type="password" maxlength="32" class="form-control" value="<?=$password?>" placeholder="Password" <?=$inputPassword?>></div>                            
                    		<div class="form-group"><label>Rol</label>
                            <select id="rol" name="rol" class="form-control">
                            <? 
								if(is_array($users['roles']) && count($users['roles'])>0){ 
									foreach($users['roles'] as $role){
										$selected = $role['id']==$rol ? ' selected' : '';
							?>
                                <option value="<?=$role['id']?>"<?=$selected?>><?=$role['rol']?></option>                            
                            <?
									}
								}
							?>
                            </select>                            
                            </div>                                                        
                        </div> 
                    	<div class="col-lg-6">
                    		<div class="form-group"><label>Nombre</label><input id="nombre" name="nombre" type="text" maxlength="100" class="form-control" value="<?=$nombre?>" placeholder="Nombre usuario"></div>                                                    
                    		<div class="form-group">
                            	<label>Apps</label>
                                <select multiple id="apps[]" name="apps[]" class="form-control">
                            <? 
								if(is_array($users['apps']) && count($users['apps'])>0){ 
									foreach($users['apps'] as $app){
										$selected = in_array($app['id'],$usrapps) ? ' selected' : '';
							?>
                                <option value="<?=$app['id']?>"<?=$selected?>><?=$app['titulo']?></option>                            
                            <?
									}
								}
							?>
                                </select>                                
                            </div>                        
							<?
								if(is_array($users['roles']) && count($users['roles'])>0){ 
							?>                        
							<div class="form-group text-right"><button type="submit" class="btn btn-default">Guardar</button></div>                            
							<?
								}else{
							?>
                            <div class="alert alert-danger">No existen roles, para poder crear un usuario debes crear antes los <a href="<?=$SIRAgest->urlAmigas(URL.'index.php?seccion=gestion&apartado=roles')?>" class="alert-link"><strong>Roles</strong></a>.</div>                                                        
                            <?
								}
                            ?>
                        </div>                                                 
						</form>                        
					</div>
                </div>
            </div>            
        </div>        
    </div>    
<?
}
?>