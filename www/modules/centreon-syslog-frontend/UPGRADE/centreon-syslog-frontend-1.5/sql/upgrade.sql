UPDATE `topology` SET `topology_url` = './modules/centreon-syslog-frontend/include/monitoring/syslog.php' WHERE `topology_url` = './modules/Syslog/include/monitoring/syslog.php' AND `topology_name` = 'Syslog';
UPDATE `topology` SET `topology_url` = './modules/centreon-syslog-frontend/include/administration/formSyslogAdmin.php' WHERE `topology_url` = './modules/Syslog/include/administration/formSyslogAdmin.php' AND `topology_name` = 'Configuration';
UPDATE `topology` SET `topology_url` = './modules/centreon-syslog-frontend/include/monitoring/syslog.php' WHERE `topology_url` = './modules/Syslog/include/monitoring/syslog.php' AND `topology_name` = 'Monitoring';
UPDATE `topology` SET `topology_url` = './modules/centreon-syslog-frontend/include/search/syslog_search.php' WHERE `topology_url` = './modules/Syslog/include/search/syslog_search.php' AND `topology_name` = 'Search';

UPDATE `topology_JS` SET `PathName_js` = './modules/centreon-syslog-frontend/include/administration/javascript/changetab.js' WHERE `PathName_js` = './modules/Syslog/include/administration/javascript/changetab.js' AND `id_page` = '50710';
UPDATE `topology_JS` SET `PathName_js` = './modules/centreon-syslog-frontend/include/administration/javascript/exportConf.js' WHERE `PathName_js` = './modules/Syslog/include/administration/javascript/exportConf.js' AND `id_page` = '50710';
UPDATE `topology_JS` SET `PathName_js` = './modules/centreon-syslog-frontend/include/search/javascript/exportCSV.js' WHERE `PathName_js` = './modules/Syslog/include/search/javascript/exportCSV.js' AND `id_page` = '20402';

DELETE FROM `topology` WHERE `topology`.`topology_page` = '50710';
DELETE FROM `topology` WHERE `topology`.`topology_name` = 'Syslog' AND `topology`.`topology_parent` = '507';

--CREATE TABLE IF NOT EXISTS `mod_syslog_hosts` (
--  `host_id` int(11) NOT NULL auto_increment,
--  `host_centreon_id` int(11) default NULL,
--  `host_name` varchar(255) collate utf8_unicode_ci NOT NULL,
--  `host_ipv4` varchar(15) collate utf8_unicode_ci NOT NULL,
--  `collector_id` int(11) NOT NULL,
--  PRIMARY KEY  (`host_id`),
--  KEY `host_centreon_id` (`host_centreon_id`),
--  KEY `host_name` (`host_name`),
-- KEY `host_ipv4` (`host_ipv4`)
--) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `topology` (`topology_id`, `topology_name`, `topology_icone`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `topology_popup`, `topology_modules`, `topology_show`, `topology_style_class`, `topology_style_id`, `topology_OnClick`) 
VALUES ('', 'Syslog', './img/icones/16x16/text_view.gif', 6, 605, 91, 1, NULL, NULL, '0', '0', '1', NULL, NULL, NULL) ,
--('', 'Hosts', NULL, 605, NULL, NULL, 1, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL) ,
--('', 'Hosts', './img/icones/16x16/server_network.gif', 605, 60501, 10, 1, './modules/centreon-syslog-frontend/include/configuration/configHosts/hosts.php', '&o=l', '0', '1', '1', NULL, NULL, NULL) ,
('', 'Collectors', NULL, 605, NULL, NULL, 3, NULL, NULL, '0', '0', '1', NULL, NULL, NULL) ,
('', 'Collectors', './img/icones/16x16/server_network.gif', 605, 60502, 10, 3, './modules/centreon-syslog-frontend/include/configuration/configCollectors/collectors.php', '&o=l', '0', '1', '1', NULL, NULL, NULL) ,
('', 'General', NULL, 605, NULL, NULL, 4, NULL, NULL, '0', '0', '1', NULL, NULL, NULL) ,
('', 'Resfresh', './img/icones/16x16/server_network.gif', 605, 60503, 10, 4, './modules/centreon-syslog-frontend/include/configuration/configOpt/refresh.php', '&o=w', '0', '1', '1', NULL, NULL, NULL) ;


--INSERT INTO `topology_JS` (`id_t_js`, `id_page`, `o`, `PathName_js`, `Init`) 
--VALUES (NULL, '60501', NULL, './modules/centreon-syslog-frontend/include/configuration/configHosts/javascript/syslogImport.js', NULL ) ;

CREATE TABLE IF NOT EXISTS `mod_syslog_collector` (
  `collector_id` int(11) NOT NULL auto_increment,
  `collector_name` varchar(255) default NULL,
  `db_server_address` varchar(45) default NULL,
  `db_server_port` int(11) default '3306',
  `db_type` varchar(45) default 'mysql',
  `db_name` varchar(45) default 'centreon_syslog',
  `db_username` varchar(45) default 'centreon_syslog',
  `db_password` varchar(45) default 'syslogapass',
  `db_table_logs` varchar(45) default 'logs',
  `db_table_logs_merge` varchar(45) default 'all_logs',
  `db_table_cache` varchar(45) default 'cache',
  `db_table_cache_merge` varchar(45) default 'all_cache',
  `ssh_server_address` varchar(45) default NULL,
  `ssh_server_port` int(11) default '22',
  `ssh_username` varchar(45) default 'syslog',
  `ssh_password` varchar(45) default NULL,
  `configuration_dir` varchar(255) default '/etc/centreon-syslog',
  `retention_days` int(11) default '31',
  `enable` enum('0','1') default '1',
  `comment` varchar(255) default NULL,
  PRIMARY KEY  (`collector_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

INSERT INTO `mod_syslog_collector` VALUES ('', 'collector-1', '0.0.0.0', '3306', 'mysql', 'centreon_syslog', 'centreon_syslog', 'syslogapass', 'logs', 'all_logs', 'cache', 'all_cache', '0.0.0.0', '22', 'syslog', 'syslog', '/etc/centreon-syslog', '31', '1', 'Default configuration') ;
UPDATE `mod_syslog_collector` SET `db_server_address` = (SELECT `syslog_db_server` FROM `mod_syslog_opt` WHERE 1) WHERE `db_server_address` = '0.0.0.0' ;
UPDATE `mod_syslog_collector` SET `db_name` = (SELECT `syslog_db_name` FROM `mod_syslog_opt` WHERE 1) WHERE `db_name` = 'centreon_syslog' ;
UPDATE `mod_syslog_collector` SET `db_table_logs` = (SELECT `syslog_db_logs` FROM `mod_syslog_opt` WHERE 1) WHERE `db_table_logs` = 'logs' ;
UPDATE `mod_syslog_collector` SET `db_table_logs_merge` = (SELECT `syslog_db_logs_merge` FROM `mod_syslog_opt` WHERE 1) WHERE `db_table_logs_merge` = 'all_logs' ;
UPDATE `mod_syslog_collector` SET `db_table_cache` = (SELECT `syslog_db_cache` FROM `mod_syslog_opt` WHERE 1) WHERE `db_table_cache` = 'cache' ;
UPDATE `mod_syslog_collector` SET `db_table_cache_merge` = (SELECT `syslog_db_cache_merge` FROM `mod_syslog_opt` WHERE 1) WHERE `db_table_cache_merge` = 'all_cache' ;
UPDATE `mod_syslog_collector` SET `db_username` = (SELECT `syslog_db_user` FROM `mod_syslog_opt` WHERE 1) WHERE `db_username` = 'centreon_syslog' ;
UPDATE `mod_syslog_collector` SET `db_password` = (SELECT `syslog_db_password` FROM `mod_syslog_opt` WHERE 1) WHERE `db_password` = 'syslogapass' ;

UPDATE `mod_syslog_collector` SET `ssh_server_address` = (SELECT `syslog_db_server` FROM `mod_syslog_opt` WHERE 1) WHERE `ssh_server_address` = '0.0.0.0' ;
UPDATE `mod_syslog_collector` SET `ssh_server_port` = (SELECT `syslog_ssh_port` FROM `mod_syslog_opt` WHERE 1) WHERE `ssh_server_port` = '22' ;
UPDATE `mod_syslog_collector` SET `ssh_username` = (SELECT `syslog_ssh_user` FROM `mod_syslog_opt` WHERE 1) WHERE `ssh_username` = 'syslog' ;
UPDATE `mod_syslog_collector` SET `ssh_password` = (SELECT `syslog_ssh_pass` FROM `mod_syslog_opt` WHERE 1) WHERE `ssh_password` = 'syslog' ;

UPDATE `mod_syslog_collector` SET `retention_days` = (SELECT `syslog_db_rotate` FROM `mod_syslog_opt` WHERE 1) WHERE `retention_days` = '31' ;
UPDATE `mod_syslog_collector` SET `configuration_dir` = (SELECT `syslog_conf_dir` FROM `mod_syslog_opt` WHERE 1) WHERE `configuration_dir` = '/etc/centreon-syslog' ;

-- ALTER TABLE `mod_syslog_opt`
-- DROP `syslog_db_server`,
-- DROP `syslog_db_name`,
-- DROP `syslog_db_logs`,
-- DROP `syslog_db_logs_merge`,
-- DROP `syslog_db_cache`,
-- DROP `syslog_db_cache_merge`,
-- DROP `syslog_ssh_server`,
-- DROP `syslog_db_user`,
-- DROP `syslog_db_password`,
-- DROP `syslog_db_rotate`,
-- DROP `syslog_conf_dir`,
-- DROP `syslog_ssh_port`,
-- DROP `syslog_ssh_user`,
-- DROP `syslog_ssh_pass`;
  
ALTER TABLE `mod_syslog_opt` CHANGE `sopt_id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ;
ALTER TABLE `mod_syslog_opt` CHANGE `syslog_refresh_monitoring` `refresh_monitoring` INT( 15 ) NOT NULL DEFAULT '10' ;
ALTER TABLE `mod_syslog_opt` CHANGE `syslog_refresh_filters` `refresh_filters` INT( 15 ) NOT NULL DEFAULT '240' ;