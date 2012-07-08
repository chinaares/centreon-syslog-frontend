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
	include ("/etc/centreon/centreon.conf.php");
	
	/*
	 * Make PEAR DB object to connect to MySQL DB
	 */
	require_once $centreon_path . "www/modules/centreon-syslog-frontend/class/syslogDB.class.php";
	$pearDB = new SyslogDB("centreon");

	/**
	 * 
	 * Get syslog collectors configuration
	 * @param int $collector_id
	 * @return array
	 */
	function getSyslogOption($collector_id) {
		global $pearDB;
		
		$cfg_syslog = array();

		$DBRESULT =& $pearDB->query("SELECT * FROM `mod_syslog_collector` WHERE `collector_id` = '".$collector_id."'");
		/*
		 * Set base value
		 */
		$cfg_syslog = array_map("myDecode", $DBRESULT->fetchRow());
		$DBRESULT->free();
		return $cfg_syslog;
	}

	/**
	 * 
	 * Get list of Hosts from merge table
	 * @param PEAR::DB $pearSyslogDB
	 * @param array $cfg_syslog
	 * @return array
	 */
	function getFilterHostsMerge($pearSyslogDB, $cfg_syslog) {
		$query = "SELECT distinct(value) as host FROM " . $cfg_syslog["db_table_cache_merge"] . " WHERE type= \"HOST\" ORDER BY host ASC";
		
		$res =& $pearSyslogDB->query($query);
		if (PEAR::isError($pearSyslogDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearSyslogDB->getMessage());
		}
		
		if ($pearSyslogDB->numberRows() == 0) {
			return NULL;
		}
		
		# Set base value
		$FilterHosts  =  array("" => "");
		
		while ($host =& $res->fetchRow())
			$FilterHosts[$host['host']] = $host['host']; 
			
		$FilterHosts = array_map("myDecode",$FilterHosts);
			
		return $FilterHosts;
	}

	/**
	 * 
	 * Get Centreon hostname available from ACL
	 * @param string $hostID
	 * @param string $collectorID
	 * @param int $isAdmin
	 * @return NULL|string
	 */
	function getFilterHostsACL($hostID, $collectorID, $isAdmin = false) {
		global $pearDB;
		
		if(!$isAdmin) {
			$query = "SELECT h.host_alias FROM host as h, mod_syslog_hosts as msh WHERE h.host_id = msh.host_centreon_id AND msh.collector_id = '".$collectorID."' AND msh.host_centreon_id IN (".$hostID.")";
		} else {
			$query = "SELECT h.host_alias FROM host as h, mod_syslog_hosts as msh WHERE h.host_id = msh.host_centreon_id AND msh.collector_id = '".$collectorID."'";
		}
		
		$res =& $pearDB->query($query);
		if (PEAR::isError($pearDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearDB->getMessage());
		}
		
		if ($pearDB->numberRows() == 0) {
			return NULL;
		}
		
		# Set base value
		$FilterHosts  =  array("" => "");
		
		while ($host =& $res->fetchRow()) {
			$FilterHosts[$host['host_alias']] = $host['host_alias'];
		}
			
		$FilterHosts = array_map("myDecode",$FilterHosts);
			
		return $FilterHosts;
	}

	/**
	 * 
	 * Get list of available hostgroups activated
	 */
	function getHostGroups() {
		global $pearDB;
		
		$query = "SELECT hg_id, hg_name FROM hostgroup WHERE hg_activate = '1'";
		$DBRESULT = $pearDB->query($query);
		$hostgroup = array("" => "");
		while ($row = $DBRESULT->fetchRow()) {
			$hostgroup[$row['hg_id']] = $row['hg_name'];
		}
		$DBRESULT->free();
		
		$hostgroup = array_map("myDecode",$hostgroup);
		
		return $hostgroup;
	}

	/**
	 * 
	 * Get Syslog hostname from Centreon hostgroup name
	 * @param string $centreonHostName
	 * @return string
	 */
	function getSyslogHostFromHostgroups($centreonHostgroupName) {
		global $pearDB;
		
		$query = "SELECT msh.host_syslog_name, msh.host_syslog_ipv4 ";
		$query .= "FROM mod_syslog_hosts AS msh, host AS h ";
		$query .= "WHERE h.host_id IN ( ";
		$query .= "     SELECT host_host_id ";
		$query .= "     FROM hostgroup_relation AS hgr, hostgroup AS hg ";
		$query .= "     WHERE hg.hg_name LIKE \"%".$centreonHostgroupName."%\" ";
		$query .= "     AND hg.hg_id = hgr.hostgroup_hg_id ";
		$query .= ") AND h.host_id = msh.host_centreon_id";

		$res =& $pearDB->query($query);
		if (PEAR::isError($pearDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearDB->getMessage());
		}
		
		if ($pearDB->numberRows() == 0) {
			return "''";
		}
		
		# Set base value
		$Hosts  =  "";
		
		while($host =& $res->fetchRow()) {
			if ($Hosts != "")
				$Hosts .= ",";
			if (strcmp($host['host_syslog_name'],$host['host_syslog_ipv4']) == 0) {
				$Hosts .= '"'.$host['host_syslog_ipv4'].'"';
			} else {
				$Hosts .= '"'.$host['host_syslog_ipv4'].'","'.$host['host_syslog_name'].'"';
			}
		}
		
		return $Hosts;
	}

	/**
	 * 
	 * Get Syslog hostname from Centreon hostname
	 * @param string $centreonHostName
	 * @return string
	 */
	function getSyslogHostFromCentreon($centreonHostName) {
		global $pearDB;
		
		$query = "SELECT host_syslog_name, host_syslog_ipv4 ";
		$query .= "FROM mod_syslog_hosts, host ";
		$query .= "WHERE host.host_alias = \"".$centreonHostName."\" ";
		$query .= "AND host.host_id = mod_syslog_hosts.host_centreon_id";
		
		$res =& $pearDB->query($query);
		if (PEAR::isError($pearDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearDB->getMessage());
		}
		
		if ($pearDB->numberRows() == 0) {
			return NULL;
		}
		
		# Set base value
		$Host  =  "";
		
		$host =& $res->fetchRow();
		
		if (strcmp($host['host_syslog_name'],$host['host_syslog_ipv4']) == 0) {
			return '"'.$host['host_syslog_ipv4'].'"';
		} else {
			return '"'.$host['host_syslog_ipv4'].'","'.$host['host_syslog_name'].'"';
		}
	}

	/**
	 * 
	 * Get all Syslog hostname available from ACL
	 * @param string $centreonHostName
	 * @return string
	 */
	function getFullSyslogHostFromCentreon($collector_id) {
		global $pearDB;
		
		$query = "SELECT host_syslog_name, host_syslog_ipv4 ";
		$query .= "FROM mod_syslog_hosts WHERE collector_id = '".$collector_id."'";
		
		$res =& $pearDB->query($query);
		if (PEAR::isError($pearDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearDB->getMessage());
		}
		
		if ($pearDB->numberRows() == 0) {
			return NULL;
		}
		
		# Set base value
		$hosts  =  "";
		
		while ($host =& $res->fetchRow()) {
			if ($hosts != "") {
				$hosts .= ",";
			}
			if (strcmp($host['host_syslog_name'],$host['host_syslog_ipv4']) == 0) {
				$hosts .= '"'.$host['host_syslog_ipv4'].'"';
			} else {
				$hosts .= '"'.$host['host_syslog_ipv4'].'","'.$host['host_syslog_name'].'"';
			}
		}

		return $hosts;
	}

	/**
	*
	* Get all Syslog hostname available from ACL
	* @param string $centreonHostName
	* @return string
	*/
	function getAllSyslogHostFromCentreon($collector_id, $aclHostString) {
		global $pearDB;
	
		$query = "SELECT host_syslog_name, host_syslog_ipv4 ";
		$query .= "FROM mod_syslog_hosts, host ";
		$query .= "WHERE host.host_id IN (".$aclHostString.") ";
		$query .= "AND mod_syslog_hosts.collector_id = '".$collector_id."' ";
		$query .= "AND mod_syslog_hosts.host_centreon_id = host.host_id";
	
		$res =& $pearDB->query($query);
		if (PEAR::isError($pearDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearDB->getMessage());
		}
	
		if ($pearDB->numberRows() == 0) {
			return NULL;
		}
	
		# Set base value
		$hosts  =  "";
			
		while ($host =& $res->fetchRow()) {
		if ($hosts != "") {
		$hosts .= ",";
		}
				if (strcmp($host['host_syslog_name'],$host['host_syslog_ipv4']) == 0) {
		$hosts .= '"'.$host['host_syslog_ipv4'].'"';
				} else {
		$hosts .= '"'.$host['host_syslog_ipv4'].'","'.$host['host_syslog_name'].'"';
				}
			}
	
			return $hosts;
	}

	/**
	*
	* Get list of Facilities from merge table
	* @return array
	*/
	function getFilterFacilitiesMerge() {
		global $pearSyslogDB, $cfg_syslog;

		$query = "SELECT distinct(value) as facility FROM " . $cfg_syslog["db_table_cache_merge"] . " WHERE type= \"FACILITY\"  ORDER BY facility ASC";

		$res =& $pearSyslogDB->query($query);
		if (PEAR::isError($pearSyslogDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearSyslogDB->getMessage());
		}
		
		if ($pearSyslogDB->numberRows() == 0) {
			displayConnectionErrorPage(_("<br\>Database empty or not correctly configured<br\><br\>Please contact your administrator."));
		}
		
		# Set base value
		$FilterFacilities  =  array("" => "");
		
		while ($facility =& $res->fetchRow())
			$FilterFacilities[$facility['facility']] = $facility['facility']; 
			
		$FilterFacilities = array_map("myDecode",$FilterFacilities );
		return $FilterFacilities;
	}

	/**
	*
	* Get list of Priotirites from merge table
	* @return array
	*/
	function getFilterPrioritiesMerge() {
		global $pearSyslogDB, $cfg_syslog;

		$query = "SELECT distinct(value) as priority FROM " . $cfg_syslog["db_table_cache_merge"] . " WHERE type= \"PRIORITY\"  ORDER BY priority ASC";

		$res =& $pearSyslogDB->query($query);
		if (PEAR::isError($pearSyslogDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearSyslogDB->getMessage());
		}
		
		if ($pearSyslogDB->numberRows() == 0) {
			displayConnectionErrorPage(_("<br\>Database empty or not correctly configured<br\><br\>Please contact your administrator."));
		}
		
		# Set base value
		$FilterPriorities  =  array("" => "");
		
		while ($priority =& $res->fetchRow())
			$FilterPriorities[$priority['priority']] = $priority['priority']; 
			
		$FilterPriorities = array_map("myDecode",$FilterPriorities );
		return $FilterPriorities;
	}

	/**
	 * 
	 * Get list of available programs from merge table
	 * @param PEAR::DB $pearSyslogDB
	 * @param array
	 * @return array
	 */
	function getFilterProgramsMerge($pearSyslogDB, $cfg_syslog) {

		$query = "SELECT distinct(value) as program FROM " . $cfg_syslog["db_table_cache_merge"] . " WHERE type= \"PROGRAM\"  ORDER BY program ASC";

		$res =& $pearSyslogDB->query($query);
		if (PEAR::isError($pearSyslogDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearSyslogDB->getMessage());
		}
		
		if ($pearSyslogDB->numberRows() == 0) {
			displayConnectionErrorPage(_("<br\>Database empty or not correctly configured<br\><br\>Please contact your administrator."));
		}
		
		# Set base value
		$FilterPrograms  = array("" => "");
			
		while ($program =&$res->fetchRow())
			$FilterPrograms[$program['program']] = $program['program'] ;
			
		$FilterPrograms  = array_map("myDecode",$FilterPrograms );
		return $FilterPrograms ;
	}

	/**
	 * 
	 * Get list of selected facilities
	 * @param string $severity
	 * @param string $Fseverity
	 * @return array
	 */
	function getListOfFacilities($facility, $Ffacility) {
		global $pearDB;
		
		if ((strcmp($facility, "") == 0) || (strcmp($facility, "undefined") == 0)) {
			return array("null" => "null");
		}
		
		$query = "SELECT value FROM `mod_syslog_filters_facility` WHERE `key` LIKE '".$facility."'";
		$res =& $pearDB->query($query);
		
		if (PEAR::isError($pearDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearDB->getMessage());
		}
		$intFacility = &$res->fetchRow();
		
		if (strcmp($Ffacility, "") == 0) {
			$math = "=";
		} else {
			$math = $Ffacility;
		}

		$query = "SELECT * FROM `mod_syslog_filters_facility` WHERE `value` ".$math." ".$intFacility["value"];
		$res =& $pearDB->query($query);

		if (PEAR::isError($pearDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearDB->getMessage());
		}

		$list_priorities = array();
		while ($tab =&$res->fetchRow()) {
            $list_priorities[$tab['key']] = $tab['value'] ;
		}
		$res->free();
		return $list_priorities;
	}

	/**
	 * 
	 * Get list of selected severities
	 * @param string $severity
	 * @param string $Fseverity
	 * @return array
	 */
	function getListOfSeverities($severity, $Fseverity) {
		global $pearDB;
		
		if ((strcmp($severity, "") == 0) || (strcmp($severity, "undefined") == 0)) {
			return array("null" => "null");
		}
		
		$query = "SELECT value FROM `mod_syslog_filters_priority` WHERE `key` LIKE '".$severity."'";
		$res =& $pearDB->query($query);
		
		if (PEAR::isError($pearDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearDB->getMessage());
		}
		$intSeverity = &$res->fetchRow();

		if (strcmp($Fseverity, "") == 0) {
			$math = "=";
		} else {
		    $math = $Fseverity;
		}

		$query = "SELECT * FROM `mod_syslog_filters_priority` WHERE `value` ".$math." ".$intSeverity["value"];
		$res =& $pearDB->query($query);

		if (PEAR::isError($pearDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearDB->getMessage());
		}
		
		$list_severities = array();
		while ($tab =&$res->fetchRow()) {
				$list_severities[$tab['key']] = $tab['value'] ;
		}
		$res->free();
		return $list_severities;
	}

	/**
	 * 
	 * Get list of Facilities from specific table
	 * @return array
	 */
	function getAllFacilities() {
		global $pearDB;

		$res =& $pearDB->query("SELECT * FROM mod_syslog_filters_facility ORDER BY CAST(value AS UNSIGNED) ASC");
		if (PEAR::isError($pearDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearDB->getMessage());
		}
		# Set base value
		$FilterFacilities  =  array("" => "");
		
		while ($facility =& $res->fetchRow())
			$FilterFacilities[$facility['key']] = $facility['key']; 
			
		return $FilterFacilities;
	}

	/**
	 * 
	 * Get list of Severities from specific table
	 * @return array
	 */
	function getAllSeverities() {
		global $pearDB;

		$res =& $pearDB->query("SELECT * FROM mod_syslog_filters_priority ORDER BY CAST(value AS UNSIGNED) ASC");
		if (PEAR::isError($pearDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearDB->getMessage());
		}
		# Set base value
		$FilterSeverities  =  array("" => "");
		
		while ($severity =& $res->fetchRow())
			$FilterSeverities[$severity['key']] = $severity['key']; 
			
		$FilterSeverities = array_map("myDecode",$FilterSeverities );
		return $FilterSeverities;
	}

	/**
	 * 
	 * Get list of collectors
	 * @return array
	 */
	function getCollectorList() {
	    global $pearDB;

		$res =& $pearDB->query("SELECT `collector_id`, `collector_name` FROM `mod_syslog_collector` ");
		if (PEAR::isError($pearDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearDB->getMessage());
		}
		# Set base value
		$collectorsList =  array("" => "");
		
		while ($element =& $res->fetchRow()) {
			$collectorsList[$element['collector_id']] = $element['collector_name']; 
		}

		return $collectorsList;
	}

	/**
	 * 
	 * Get event details
	 * @param PEAR::DB $pearSyslogDB
	 * @param array $cfg_syslog
	 * @param array $options
	 */
	function getEvent($pearSyslogDB, $cfg_syslog, $options) {
		$query = " SELECT * FROM " . $cfg_syslog["db_table_logs_merge"] ." ";
		$query .= "WHERE host = \"".$options['host']."\" ";
		$query .= "AND 	facility = \"".$options['facility']."\" ";
		$query .= "AND 	priority = \"".$options['priority']."\" ";
		$query .= "AND 	datetime = \"".$options['datetime']."\" ";
		$query .= "AND 	program = \"".$options['program']."\" ";

		$res =& $pearSyslogDB->query($query);
		if (PEAR::isError($pearSyslogDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearSyslogDB->getMessage());
		}
		# Set base value
		$eventDetails = array();
				
		$event =& $res->fetchRow();
		
		$eventDetails = array(
			'host' 		=> $event['host'],
			'facility' 	=> $event['facility'],
			'priority' 	=> $event['priority'],
			'tag' 		=> $event['tag'],
			'datetime'	=> $event['datetime'],
			'program'	=> $event['program'],
			'msg'		=> $event['msg'],
			'seq'		=> $event['seq'],
			'counter'	=> $event['counter'],
			'fo'		=> $event['fo'],
			'lo'		=> $event['lo']
		);
		
		return $eventDetails;
	}

	/**
	 * 
	 * Get refresh time
	 * @return array
	 */
	function getRefreshInfo() {
		global $pearDB;
		
		$res =& $pearDB->query("SELECT `refresh_monitoring`, `refresh_filters` FROM `mod_syslog_opt` ");
		if (PEAR::isError($pearDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearDB->getMessage());
		}
		# Set base value
		$refresh_options =  array("" => "");
				
		while ($element =& $res->fetchRow()) {
			$refresh_options['refresh_monitoring'] = $element['refresh_monitoring'];
			$refresh_options['refresh_filters'] = $element['refresh_filters'];
		}
		
		return $refresh_options;
	}

	/**
	* Display error page
	*
	* @access protected
	* @return array
	*/
	function displayConnectionErrorPage($msg = null) {
		if ($msg) {
			echo "<root><error>" . $msg . "</error></root>";
		} else {
			echo "<root><error>" . _("Connection failed, please contact your administrator") . "</error></root>";
		}
		exit;
	}

	/**
	 * Get host_id and host_name from Centreon database
	 * @return array
	 */
	function getHostNameAndIDFromCentreon() {
		global $pearDB;
		
		$res =& $pearDB->query("SELECT `host_name` FROM `host` where `host_activate` = '1' AND `host_register` = '1' ORDER BY host_name ASC ");
		if (PEAR::isError($pearDB)) {
			displayConnectionErrorPage("Mysql Error : ". $pearDB->getMessage());
		}
		# Set base value
		$list =  array("" => "");
						
		while ($element =& $res->fetchRow()) {
			$list[$element['host_name']] = $element['host_name'];		
		}
		return $list;
	}
?>
