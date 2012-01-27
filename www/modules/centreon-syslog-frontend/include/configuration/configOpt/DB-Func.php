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

	include ("@CENTREON_ETC@centreon.conf.php");
	
	/*
	 * Make PEAR DB object to connect to MySQL DB
	*/
	require_once $centreon_path . "www/modules/centreon-syslog-frontend/class/syslogDB.class.php";
	$pearDB = new SyslogDB("centreon");
	
	/*
	 * Get Syslog module options
	*/
	function getRefreshOption() {
		global $pearDB;
	
		$cfg_syslog = array();
	
		$DBRESULT =& $pearDB->query("SELECT * FROM `mod_syslog_opt` LIMIT 1");
		/*
		 * Set base value
		*/
		$cfg_syslog = array_map("myDecode", $DBRESULT->fetchRow());
		$DBRESULT->free();
		return $cfg_syslog;
	}
	
	function updateRefreshOption() {
	global $form, $pearDB;
		
		$ret = array();
		$ret = $form->getSubmitValues();
		$rq = "UPDATE `mod_syslog_opt` SET ";
		
		# Update Configuration of syslog collector
		$rq .= "refresh_monitoring = ";
		isset($ret["refresh_monitoring"]) && $ret["refresh_monitoring"] != NULL ? $rq .= "'".htmlentities($ret["refresh_monitoring"], ENT_QUOTES)."', ": $rq .= "NULL;";
		
		$rq .= "refresh_filters = ";
		isset($ret["refresh_filters"]) && $ret["refresh_filters"] != NULL ? $rq .= "'".htmlentities($ret["refresh_filters"], ENT_QUOTES)."';": $rq .= "NULL;";

		$DBRESULT =& $pearDB->query($rq);
		if (PEAR::isError($DBRESULT)) {
			print $DBRESULT->getDebugInfo()."<br>";
		}
		
	}

?>