CREATE TABLE `gestion__apps` (
  `ga_id` int(10) unsigned NOT NULL auto_increment,
  `ga__fechaRegistro` timestamp NOT NULL default '0000-00-00 00:00:00',
  `ga__usuarioRegistro` int(10) NOT NULL,
  `ga__fechaModificacion` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `ga__usuarioModificacion` int(10) NOT NULL,
  `ga_app` text NOT NULL,
  `ga_titulo` text NOT NULL,
  `ga_orden` smallint(3) NOT NULL,
  `ga_estado` smallint(1) NOT NULL default '1',
  PRIMARY KEY  (`ga_id`)
);

INSERT INTO `gestion__apps` (`ga_id`,`ga__fechaRegistro`,`ga__usuarioRegistro`,`ga__fechaModificacion`,`ga__usuarioModificacion`,`ga_app`,`ga_titulo`,`ga_orden`,`ga_estado`)
VALUES (NULL,NOW(),'','0000-00-00 00:00:00','','gestion','Gestión','1', '1');

