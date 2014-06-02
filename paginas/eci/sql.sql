CREATE TABLE IF NOT EXISTS `eci__servicios` (
  `sv_id` int(10) unsigned NOT NULL auto_increment,
  `sv__fechaRegistro` timestamp NOT NULL default '0000-00-00 00:00:00',
  `sv__usuarioRegistro` int(10) NOT NULL,
  `sv__fechaModificacion` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `sv__usuarioModificacion` int(10) NOT NULL,
  `sv_servicio` text NOT NULL,
  `sv_estado` smallint(1) NOT NULL default '1',
  PRIMARY KEY  (`sv_id`)
);

CREATE TABLE IF NOT EXISTS `eci__subservicios` (
  `ss_id` int(10) unsigned NOT NULL auto_increment,
  `ss__fechaRegistro` timestamp NOT NULL default '0000-00-00 00:00:00',
  `ss__usuarioRegistro` int(10) NOT NULL,
  `ss__fechaModificacion` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `ss__usuarioModificacion` int(10) NOT NULL,
  `ss_servicio` int(10) unsigned NOT NULL,
  `ss_subservicio` text NOT NULL,  
  `ss_estado` smallint(1) NOT NULL default '1',
  PRIMARY KEY  (`ss_id`)
);

CREATE TABLE IF NOT EXISTS `eci__asignaciones` (
  `ea_id` int(10) unsigned NOT NULL auto_increment,
  `ea__fechaRegistro` timestamp NOT NULL default '0000-00-00 00:00:00',
  `ea__usuarioRegistro` int(10) NOT NULL,
  `ea__fechaModificacion` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `ea__usuarioModificacion` int(10) NOT NULL,
  `ea_servicio` int(10) unsigned NOT NULL,
  `ea_subservicio` int(10) unsigned NOT NULL,
  `ea_trabajador` int(10) NOT NULL,
  PRIMARY KEY  (`ea_id`)
);

CREATE TABLE IF NOT EXISTS `eci__fichar` (
  `fi_id` int(10) unsigned NOT NULL auto_increment,
  `fi__fechaRegistro` timestamp NOT NULL default '0000-00-00 00:00:00',
  `fi__usuarioRegistro` int(10) NOT NULL,
  `fi__fechaModificacion` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `fi__usuarioModificacion` int(10) NOT NULL,
  `fi_trabajador` int(10) NOT NULL,
  PRIMARY KEY  (`fi_id`)
);