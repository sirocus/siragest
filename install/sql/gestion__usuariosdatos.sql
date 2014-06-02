CREATE TABLE `gestion__usuariosdatos` (
  `gud_id` int(10) unsigned NOT NULL auto_increment,
  `gud_idReg` varchar(32) NOT NULL,
  `gud_usuario` int(10) unsigned NOT NULL,
  `gud_apps` text NOT NULL,
  `gud_nombre` varchar(45) NOT NULL,
  PRIMARY KEY  (`gud_id`)
);