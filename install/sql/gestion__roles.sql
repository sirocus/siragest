CREATE TABLE IF NOT EXISTS `gestion__roles` (
  `gr_id` int(10) unsigned NOT NULL auto_increment,
  `gr__fechaRegistro` timestamp NOT NULL default '0000-00-00 00:00:00',
  `gr__usuarioRegistro` int(10) NOT NULL,
  `gr__fechaModificacion` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `gr__usuarioModificacion` int(10) NOT NULL,
  `gr_tipo` text NOT NULL,
  `gr_estado` smallint(1) NOT NULL default '1',
  PRIMARY KEY  (`gr_id`)
);

CREATE TABLE IF NOT EXISTS `gestion__roles_permisos` (
  `gp_id` int(10) unsigned NOT NULL auto_increment,
  `gp_idReg` varchar(32) NOT NULL,  
  `gp_seccion` text NOT NULL,
  `gp_apartado` text NOT NULL,  
  `gp_opcion` text NOT NULL,
  `gp_estado` smallint(1) NOT NULL default '0',   
  PRIMARY KEY  (`gp_id`)
);