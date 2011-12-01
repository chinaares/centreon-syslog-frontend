-- Create new table for collectors
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

ALTER TABLE `mod_syslog_opt`
DROP `syslog_db_server`,
DROP `syslog_db_name`,
DROP `syslog_db_logs`,
DROP `syslog_db_logs_merge`,
DROP `syslog_db_cache`,
DROP `syslog_db_cache_merge`,
DROP `syslog_ssh_server`,
DROP `syslog_db_user`,
DROP `syslog_db_password`,
DROP `syslog_db_rotate`,
DROP `syslog_conf_dir`,
DROP `syslog_ssh_port`,
DROP `syslog_ssh_user`,
DROP `syslog_ssh_pass`;
  
ALTER TABLE `mod_syslog_opt` CHANGE `sopt_id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ;
ALTER TABLE `mod_syslog_opt` CHANGE `syslog_refresh_monitoring` `refresh_monitoring` INT( 15 ) NOT NULL DEFAULT '10' ;
ALTER TABLE `mod_syslog_opt` CHANGE `syslog_refresh_filters` `refresh_filters` INT( 15 ) NOT NULL DEFAULT '240' ;

-- Delete old topologies
DELETE FROM `topology` WHERE `topology_page` = '204' AND `topology_name` = "Syslog";
DELETE FROM `topology` WHERE `topology_page` = '20401' AND `topology_name` = "Monitoring";
DELETE FROM `topology` WHERE `topology_page` = '20402' AND `topology_name` = "Search";
DELETE FROM `topology` WHERE `topology_parent` = '507' AND `topology_name` = "Syslog";
DELETE FROM `topology` WHERE `topology_parent` = '507' AND `topology_name` = "Modules";
DELETE FROM `topology` WHERE `topology_page` = '50710' AND `topology_name` = "Configuration";

-- Insert old topologies
INSERT INTO `topology` (`topology_id`, `topology_name`, `topology_icone`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `topology_popup`, `topology_modules`, `topology_show`, `topology_style_class`, `topology_style_id`, `topology_OnClick`) 
VALUES ('', 'Syslog', NULL, 2, 204, 40, 1, './modules/centreon-syslog-frontend/include/monitoring/syslog.php', NULL, '0', '1', '1', NULL, NULL, NULL) ,
('', 'Monitoring', './img/icones/16x16/text_view.gif', 204, 20401, 40, 1, './modules/centreon-syslog-frontend/include/monitoring/syslog.php', NULL, '0', '1', '1', NULL, NULL, NULL) ,
('', 'Search', './img/icones/16x16/text_view.gif', 204, 20402, 40, 1, './modules/centreon-syslog-frontend/include/search/search.php', NULL, '0', '1', '1', NULL, NULL, NULL) ,
('', 'Details', NULL, 204, 20403, 40, 1, './modules/centreon-syslog-frontend/include/details/eventDetails.php', NULL, '0', '1', '0', NULL, NULL, NULL) ,
('', 'Syslog', './img/icones/16x16/text_view.gif', 6, 605, 91, 1, NULL, NULL, '0', '0', '1', NULL, NULL, NULL) ,
('', 'Collectors', NULL, 605, NULL, NULL, 3, NULL, NULL, '0', '0', '1', NULL, NULL, NULL) ,
('', 'Collectors', './img/icones/16x16/server_network.gif', 605, 60502, 10, 3, './modules/centreon-syslog-frontend/include/configuration/configCollectors/collectors.php', NULL, '0', '1', '1', NULL, NULL, NULL) ,
('', 'General', NULL, 605, NULL, NULL, 4, NULL, NULL, '0', '0', '1', NULL, NULL, NULL) ,
('', 'Resfresh', './img/icones/16x16/server_network.gif', 605, 60503, 10, 4, './modules/centreon-syslog-frontend/include/configuration/configOpt/refresh.php', '&o=f', '0', '1', '1', NULL, NULL, NULL) ;

-- Javascript topology
INSERT INTO `topology_JS` (`id_t_js`, `id_page`, `o`, `PathName_js`, `Init`) 
VALUES ('', '20402', NULL, './modules/centreon-syslog-frontend/include/search/javascript/exportCSV.js', NULL ) ,
('', '20402', NULL, './include/common/javascript/datePicker.js', NULL ) ,
('', '20402', NULL, './include/common/javascript/tool.js', NULL ) ;
