DROP TABLE IF EXISTS `mod_syslog_opt`;
DROP TABLE IF EXISTS `mod_syslog_collector`;
DROP TABLE IF EXISTS `mod_syslog_filters_facility`;
DROP TABLE IF EXISTS `mod_syslog_filters_priority`;

DELETE FROM `topology` WHERE `topology_parent` = '204' AND `topology_name` = "Syslog";
DELETE FROM `topology` WHERE `topology_page` = '20401' AND `topology_name` = "Monitoring";
DELETE FROM `topology` WHERE `topology_page` = '20402' AND `topology_name` = "Search";
DELETE FROM `topology` WHERE `topology_parent` = '605' AND `topology_name` = "Syslog";
DELETE FROM `topology` WHERE `topology_parent` = '605' AND `topology_name` = "Collectors";
DELETE FROM `topology` WHERE `topology_page` = '60502' AND `topology_name` = "Collectors";
DELETE FROM `topology` WHERE `topology_parent` = '605' AND `topology_name` = "General";
DELETE FROM `topology` WHERE `topology_page` = '60503' AND `topology_name` = "Resfresh";

DELETE FROM `topology_JS` WHERE `id_page` = '204' LIMIT 1;
DELETE FROM `topology_JS` WHERE `id_page` = '20401' LIMIT 1;
DELETE FROM `topology_JS` WHERE `id_page` = '20402' LIMIT 1;