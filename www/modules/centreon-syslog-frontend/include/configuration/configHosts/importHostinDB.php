<?php
/*
 * Copyright 2005-2010 MERETHIS
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
 * Module name: Syslog
 * 
 * First developpement by : Jean Marc Grisard - Christophe Coraboeuf
 * 
 * Adaptation for Centreon 2.0 by : Merethis team 
 * 
 * SVN : $URL:$
 * SVN : $Id:$
 * 
 */
	if (!isset($_GET["host"])) {
		exit();
	}
	
	include ("@CENTREON_ETC@centreon.conf.php");

	require ($centreon_path . "www/class/Session.class.php");
	require ($centreon_path . "www/class/Oreon.class.php");
	Session::start();
	
	/*
	 * Defined path
	 */
	$syslog_mod_path = $centreon_path . "www/modules/centreon-syslog/";
	
	/*
	 * PHP functions
	 */
	require $syslog_mod_path ."/include/common/common-Func.php";
	require $centreon_path . "www/include/common/common-Func.php";
	
	/*
	 * Get host name and IP address
	 */
	$hostAndIP = split("::", $_GET["host"]);

	$hostname = $hostAndIP[0];
	$hostIP = $hostAndIP[1];
	
	$value = _("Unable to get IP address");
	
	if (strcmp($hostIP, $value) == 0) {
		$hostIP = "0.0.0.0";
	}
	
	/*
	 * Insert into Database informations
	 */
	$pearCentreonDB = new SyslogDB("centreon");
	
	$DBRESULT =& $pearCentreonDB->query("SELECT `host_id` FROM `host` WHERE `host_address` = '".$hostIP."' OR `host_name` LIKE '%".$hostname."%' OR `host_alias` LIKE '%".$hostname."%'");
	$result = $DBRESULT->fetchRow();
	
	if ($DBRESULT->numRows() == 1) {
		$query = "INSERT INTO mod_syslog_hosts(host_id, host_centreon_id, host_name, host_ipv4) VALUES ('', '".$result["host_id"]."', '".$hostname."', '".$hostIP."');";
	} else {
		$query = "INSERT INTO mod_syslog_hosts(host_id, host_centreon_id, host_name, host_ipv4) VALUES ('', NULL, '".$hostname."', '".$hostIP."');";
	}

	$pearCentreonDB->query($query);
?>