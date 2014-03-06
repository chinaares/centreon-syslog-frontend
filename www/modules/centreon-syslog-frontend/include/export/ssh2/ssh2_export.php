<?php
/*
 * Copyright 2005-2011 MERETHIS
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 * 
 * This program is free software; you can redistribute it and/or modify it under 
 * the terms of the GNU General Public License as published by the Free Software 
 * Foundation ; either version 2 of the License.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A 
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with 
 * this program; if not, see <http://www.gnu.org/licenses>.
 * 
 * Linking this program statically or dynamically with other modules is making a 
 * combined work based on this program. Thus, the terms and conditions of the GNU 
 * General Public License cover the whole combination.
 * 
 * As a special exception, the copyright holders of this program give MERETHIS 
 * permission to link this program with independent modules to produce an executable, 
 * regardless of the license terms of these independent modules, and to copy and 
 * distribute the resulting executable under terms of MERETHIS choice, provided that 
 * MERETHIS also meet, for each linked independent module, the terms  and conditions 
 * of the license of that module. An independent module is a module which is not 
 * derived from this program. If you modify this program, you may extend this 
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 * 
 * For more information : contact@centreon.com
 * 
 * Project name : Centreon Syslog
 * Module name: Centreon-Syslog-Frontend
 * 
 * SVN : $URL$
 * SVN : $Id$
 * 
 */
	
	include ("@CENTREON_ETC@/centreon.conf.php");

	require_once $centreon_path . "www/modules/centreon-syslog-frontend/include/common/header.php";
	
	header('Content-Type: text/xml'); 
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

	/*
	 * Set language
	 */ 
	$locale = $oreon->user->get_lang();
	putenv("LANG=$locale");
	setlocale(LC_ALL, $locale);
	bindtextdomain("messages", $syslog_mod_path . "locale/");
	bind_textdomain_codeset("messages", "UTF-8"); 
	textdomain("messages");

	/*
	 * PHP functions
	 */
	require_once $syslog_mod_path . "class/syslogDB.class.php";
	require_once $syslog_mod_path . "include/common/common-Func.php";

	/*
	 * Path to the configuration dir
	 */
	global $path, $conf_file, $tmp_file;

	if (isset($_GET['id']) && $_GET['id'] != "" )
		$collector_id = $_GET['id'];
	else
		return "<root><status>"._("No collector ID defined")."</status></root>";
	
	$conf_file = "syslog.conf.php";

	/**
	 * 
	 * Generate new configuration file for syslog server
	 * @param array $Syslog_options
	 */
	function generateNewConfFile($Syslog_options) {
		global $tmp_file, $conf_file;

		$tmp_file = "/tmp/".$conf_file."_tmp";
			
		$fp = fopen($tmp_file,"w");
		fputs($fp, "<?php\n");
		fputs($fp, "\$syslogOpt[\"syslog_server_db_user\"] = \"".$Syslog_options["db_username"]."\";\n");
		fputs($fp, "\$syslogOpt[\"syslog_server_db_password\"] = \"".$Syslog_options["db_password"]."\";\n");
		fputs($fp, "\$syslogOpt[\"syslog_server\"] = \"".$Syslog_options["db_server_address"]."\";\n");
		fputs($fp, "\$syslogOpt[\"syslog_db_name\"] = \"".$Syslog_options["db_name"]."\";\n");
		fputs($fp, "\$syslogOpt[\"syslog_db_filter\"] = \"".$Syslog_options["db_table_logs"]."\";\n");
		fputs($fp, "\$syslogOpt[\"syslog_db_table\"] = \"".$Syslog_options["db_table_cache"]."\";\n");
		fputs($fp, "\$syslogOpt[\"syslog_db_rotate\"] = \"".$Syslog_options["retention_days"]."\";\n");
		fputs($fp, "?>\n");
		fclose($fp);
	}

	/**
	 * 
	 * Export new configuration file in "etc" syslog server directory"
	 * @param array $Syslog_options
	 */
	function exportConfFile($Syslog_options) {
		if (preg_match('/(localhost|127.0.0.1)/', $Syslog_options["ssh_server_address"])) {
			return localExportConfFile($Syslog_options);
		} else {
			return sshExportConfFile($Syslog_options);
		}
	}
	
	/**
	*
	* Export centreon-syslog-server configuration file locally
	* @param array $Syslog_options
	* @return string
	*/
	function localExportConfFile($Syslog_options) {
		global $tmp_file, $conf_file;
		
		$command = "cp ".$tmp_file." ".$Syslog_options["configuration_dir"]."/".$conf_file." 2>&1";
		$tab_result = array();
		$return_var = 0;

		exec ($command, $tab_result, $return_var);

		if ($return_var != 0) {
			return $tab_result[0];
		}

		return _("Configuration file copied successfully");
	}
	
	/**
	 * 
	 * Export centreon-syslog-server configuration file using SSH2
	 * @param array $Syslog_options
	 * @return string
	 */
	function sshExportConfFile($Syslog_options) {
		global $tmp_file, $conf_file;
		
		$connection = ssh2_connect($Syslog_options["ssh_server_address"], $Syslog_options["ssh_server_port"], array('hostkey'=>'ssh-rsa'));
			
		if (!$connection) {
			$output = _("Unable to connect on distant server.");
			return $output;
		}
		
		if (strlen($Syslog_options["ssh_password"]) == 0) {
			$status = ssh2_auth_none($connection, $Syslog_options["ssh_username"]);
		} else {
			$status = ssh2_auth_password($connection, $Syslog_options["ssh_username"], $Syslog_options["ssh_password"]);
		}
		
		if (!$status) {
			$output = _("Authentification failed.");
			return $output;
		}
		
		$status = ssh2_scp_send($connection, $tmp_file, $Syslog_options["configuration_dir"]."/".$conf_file, 0664);
		
		if (!$status) {
			$output = _("Unable to export configuration file. Rights may be not correct on distant directory.");
			return $output;
		}
		
		return _("Configuration file exported successfully");
	}

	$Syslog_options = getSyslogOption($collector_id);
	generateNewConfFile($Syslog_options);

	echo "<root><status>".exportConfFile($Syslog_options)."</status></root>";

 ?>
