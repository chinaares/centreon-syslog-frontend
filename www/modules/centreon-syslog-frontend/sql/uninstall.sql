DROP TABLE `mod_syslog_opt`;
DROP TABLE `mod_syslog_filters_facility`;
DROP TABLE `mod_syslog_filters_priority`;

DELETE FROM `modules_informations` WHERE `name` = "Syslog";

DELETE FROM `topology_JS` WHERE `id_page` = '204';
DELETE FROM `topology_JS` WHERE `id_page` = '20401';
DELETE FROM `topology_JS` WHERE `id_page` = '20402';

DELETE FROM `topology_JS` WHERE `id_page` = '50710';

DELETE FROM `topology` WHERE `topology_page` = '204' AND `topology_name` = "Syslog";
DELETE FROM `topology` WHERE `topology_page` = '20401' AND `topology_name` = "Monitoring";
DELETE FROM `topology` WHERE `topology_page` = '20402' AND `topology_name` = "Search";

DELETE FROM `topology` WHERE `topology_parent` = '507' AND `topology_name` = "Syslog";
DELETE FROM `topology` WHERE `topology_parent` = '507' AND `topology_name` = "Modules";
DELETE FROM `topology` WHERE `topology_page` = '50710' AND `topology_name` = "Configuration";