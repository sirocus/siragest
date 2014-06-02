CREATE TABLE `gestion__usuarios` (
  `gu_id` int(10) unsigned NOT NULL auto_increment,
  `gu__fechaRegistro` timestamp NOT NULL default '0000-00-00 00:00:00',
  `gu__usuarioRegistro` int(10) NOT NULL,
  `gu__fechaModificacion` timestamp NOT NULL default '0000-00-00 00:00:00',
  `gu__usuarioModificacion` int(10) NOT NULL,
  `gu__ultimaConexion` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `gu__ultimaIp` varchar(15) NOT NULL default '000.000.000.000',
  `gu_ip` varchar(15) NOT NULL default '000.000.000.000',
  `gu_usuario` text NOT NULL,
  `gu_password` text NOT NULL,
  `gu_email` text NOT NULL,
  `gu_tipo` smallint(2) NOT NULL default '2',
  `gu_estado` smallint(1) NOT NULL default '0',
  PRIMARY KEY  (`gu_id`)
);