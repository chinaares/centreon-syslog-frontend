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

	/*
	 * Get list of Hosts
	 */
	function getFilterHosts() {
		global $pearSyslogDB, $cfg_syslog;

		$query = "SELECT distinct(value) as host FROM " . $cfg_syslog["db_table_cache"] . " WHERE type= \"HOST\" ORDER BY host ASC";
			
		$res =& $pearSyslogDB->query($query);
		if (PEAR::isError($pearSyslogDB)) {
			print "Mysql Error : ". $pearSyslogDB->getMessage()."\n";
		}
		# Set base value
		$FilterHosts  =  array("" => "");
		
		while ($host =& $res->fetchRow())
			$FilterHosts[$host['host']] = $host['host']; 
			
		$FilterHosts = array_map("myDecode",$FilterHosts);
			
		return $FilterHosts;
	}
	
	/*
	 * Get list of Hosts from merge table
	 */
	function getFilterHostsMerge($pearSyslogDB, $cfg_syslog) {
		$query = "SELECT distinct(value) as host FROM " . $cfg_syslog["db_table_cache_merge"] . " WHERE type= \"HOST\" ORDER BY host ASC";
		
		$res =& $pearSyslogDB->query($query);
		if (PEAR::isError($pearSyslogDB)) {
			print "Mysql Error : ". $pearSyslogDB->getMessage()."\n";
		}
		# Set base value
		$FilterHosts  =  array("" => "");
		
		while ($host =& $res->fetchRow())
			$FilterHosts[$host['host']] = $host['host']; 
			
		$FilterHosts = array_map("myDecode",$FilterHosts);
			
		return $FilterHosts;
	}
	
	/*
	 * Get list of Facilities
	 */
	function getFilterFacilities() {
		global $pearSyslogDB, $cfg_syslog;

		$query = "SELECT distinct(value) as facility FROM " . $cfg_syslog["db_table_cache"] . " WHERE type= \"FACILITY\"  ORDER BY facility ASC";

		$res =& $pearSyslogDB->query($query);
		if (PEAR::isError($pearSyslogDB)) {
			print "Mysql Error : ". $pearSyslogDB->getMessage()."\n";
		}
		# Set base value
		$FilterFacilities  =  array("" => "");
		
		while ($facility =& $res->fetchRow())
			$FilterFacilities[$facility['facility']] = $facility['facility']; 
			
		$FilterFacilities = array_map("myDecode",$FilterFacilities );
		return $FilterFacilities;
	}
	
	/*
	 * Get list of Facilities from merge table
	 */
	function getFilterFacilitiesMerge() {
		global $pearSyslogDB, $cfg_syslog;

		$query = "SELECT distinct(value) as facility FROM " . $cfg_syslog["db_table_cache_merge"] . " WHERE type= \"FACILITY\"  ORDER BY facility ASC";

		$res =& $pearSyslogDB->query($query);
		if (PEAR::isError($pearSyslogDB)) {
			print "Mysql Error : ". $pearSyslogDB->getMessage()."\n";
		}
		# Set base value
		$FilterFacilities  =  array("" => "");
		
		while ($facility =& $res->fetchRow())
			$FilterFacilities[$facility['facility']] = $facility['facility']; 
			
		$FilterFacilities = array_map("myDecode",$FilterFacilities );
		return $FilterFacilities;
	}
	
	/*
	 * Get list of Priotirites
	 */
	function getFilterPriorities() {
		global $pearSyslogDB, $cfg_syslog;

		$query = "SELECT distinct(value) as priority FROM " . $cfg_syslog["db_table_cache"] . " WHERE type= \"PRIORITY\"  ORDER BY priority ASC";

		$res =& $pearSyslogDB->query($query);
		if (PEAR::isError($pearSyslogDB)) {
			print "Mysql Error : ". $pearSyslogDB->getMessage()."\n";
		}
		# Set base value
		$FilterPriorities  =  array("" => "");
		
		while ($priority =& $res->fetchRow())
			$FilterPriorities[$priority['priority']] = $priority['priority']; 
			
		$FilterPriorities = array_map("myDecode",$FilterPriorities );
		return $FilterPriorities;
	}
	
	/*
	 * Get list of Priotirites from merge table
	 */
	function getFilterPrioritiesMerge() {
		global $pearSyslogDB, $cfg_syslog;

		$query = "SELECT distinct(value) as priority FROM " . $cfg_syslog["db_table_cache_merge"] . " WHERE type= \"PRIORITY\"  ORDER BY priority ASC";

		$res =& $pearSyslogDB->query($query);
		if (PEAR::isError($pearSyslogDB)) {
			print "Mysql Error : ". $pearSyslogDB->getMessage()."\n";
		}
		# Set base value
		$FilterPriorities  =  array("" => "");
		
		while ($priority =& $res->fetchRow())
			$FilterPriorities[$priority['priority']] = $priority['priority']; 
			
		$FilterPriorities = array_map("myDecode",$FilterPriorities );
		return $FilterPriorities;
	}
 
 	/*
	 * Get list of Levels
	 */
	function getFilterLevels() {
		global $pearSyslogDB, $cfg_syslog;

		$query = "SELECT distinct(value) as level FROM " . $cfg_syslog["db_table_cache"] . " WHERE type= \"LEVEL\"  ORDER BY level ASC";

		$res =& $pearSyslogDB->query($query);
		if (PEAR::isError($pearSyslogDB)) {
			print "Mysql Error : ". $pearSyslogDB->getMessage()."\n";
		}
		# Set base value
		$FilterLevels  = array("" => "");
		
		while ($level =& $res->fetchRow())
			$FilterLevels[$level['level']] = $level['level']; 
			
		$FilterLevels = array_map("myDecode",$FilterLevels );
		return $FilterLevels;
	}
	
	/*
	 * Get list of Levels from merge table
	 */
	function getFilterLevelsMerge() {
		global $pearSyslogDB, $cfg_syslog;

		$query = "SELECT distinct(value) as level FROM " . $cfg_syslog["db_table_cache_merge"] . " WHERE type= \"LEVEL\"  ORDER BY level ASC";

		$res =& $pearSyslogDB->query($query);
		if (PEAR::isError($pearSyslogDB)) {
			print "Mysql Error : ". $pearSyslogDB->getMessage()."\n";
		}
		# Set base value
		$FilterLevels  = array("" => "");
		
		while ($level =& $res->fetchRow())
			$FilterLevels[$level['level']] = $level['level']; 
			
		$FilterLevels = array_map("myDecode",$FilterLevels );
		return $FilterLevels;
	}

	/*
	 * Get list of Programs
	 */
	function getFilterPrograms() {
		global $pearSyslogDB, $cfg_syslog;

		$query = "SELECT distinct(value) as program FROM " . $cfg_syslog["db_table_cache"] . " WHERE type= \"PROGRAM\"  ORDER BY program ASC";

		$res =& $pearSyslogDB->query($query);
		
		if (PEAR::isError($pearSyslogDB)) {
			print "Mysql Error : ". $pearSyslogDB->getMessage()."\n";
		}
		# Set base value
		$FilterPrograms  = array("" => "");
		
		while ($program =&$res->fetchRow())
				$FilterPrograms[$program['program']] = $program['program'] ; 
		
		//	array_push($FilterPrograms, $program['program']); 
			
		$FilterPrograms  = array_map("myDecode",$FilterPrograms );
		return $FilterPrograms ;
	}
	
	/*
	 * Get list of Programs from merge table
	 */
	function getFilterProgramsMerge($pearSyslogDB, $cfg_syslog) {

		$query = "SELECT distinct(value) as program FROM " . $cfg_syslog["db_table_cache_merge"] . " WHERE type= \"PROGRAM\"  ORDER BY program ASC";

		$res =& $pearSyslogDB->query($query);
		
		if (PEAR::isError($pearSyslogDB)) {
			print "Mysql Error : ". $pearSyslogDB->getMessage()."\n";
		}
		# Set base value
		$FilterPrograms  = array("" => "");
		
		while ($program =&$res->fetchRow())
				$FilterPrograms[$program['program']] = $program['program'] ; 
		
		//	array_push($FilterPrograms, $program['program']); 
			
		$FilterPrograms  = array_map("myDecode",$FilterPrograms );
		return $FilterPrograms ;
	}
	
	/*
	 * Get list of facility
	 */
	function getSyslogFacility() {
		global $pearDB;
		
		$cfg_syslog_facility = array();

		$DBRESULT =& $pearDB->query("SELECT * FROM `mod_syslog_filters_facility`");
		/*
		 * Set base value
		 */
		$cfg_syslog_facility = array_map("myDecode", $DBRESULT->fetchRow());
		$DBRESULT->free();
		return $cfg_syslog_facility;
	}
	
	/*
	 * Get list of facility
	 */
	function getSyslogPriority() {
		global $pearDB;
		
		$cfg_syslog_priority = array();

		$DBRESULT =& $pearDB->query("SELECT * FROM `mod_syslog_filters_priority`");
		/*
		 * Set base value
		 */
		$cfg_syslog_priority = array_map("myDecode", $DBRESULT->fetchRow());
		$DBRESULT->free();
		return $cfg_syslog_priority;
	}
	
	/*
	 * Get list of facility
	 */
	function getListOfFacilities($facility, $Ffacility) {
		global $pearDB;
		
		if ((strcmp($facility, "") == 0) || (strcmp($facility, "undefined") == 0)) {
			return array("null" => "null");
		}
		
		$query = "SELECT value FROM `mod_syslog_filters_facility` WHERE `key` LIKE '".$facility."'";
		$res =& $pearDB->query($query);
		
		if (PEAR::isError($pearDB)) {
			print "Mysql Error : ". $pearDB->getMessage()."\n";
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
			print "Mysql Error : ". $pearDB->getMessage()."\n";
		}

		$list_priorities = array();
		while ($tab =&$res->fetchRow()) {
            $list_priorities[$tab['key']] = $tab['value'] ;
		}
		$res->free();
		return $list_priorities;
	}

	/*
	 * Get list of facility
	 */
	function getListOfSeverities($severity, $Fseverity) {
		global $pearDB;
		
		if ((strcmp($severity, "") == 0) || (strcmp($severity, "undefined") == 0)) {
			return array("null" => "null");
		}
		
		$query = "SELECT value FROM `mod_syslog_filters_priority` WHERE `key` LIKE '".$severity."'";
		$res =& $pearDB->query($query);
		
		if (PEAR::isError($pearDB)) {
			print "Mysql Error : ". $pearDB->getMessage()."\n";
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
			print "Mysql Error : ". $pearDB->getMessage()."\n";
		}
		
		$list_severities = array();
		while ($tab =&$res->fetchRow()) {
				$list_severities[$tab['key']] = $tab['value'] ;
		}
		$res->free();
		return $list_severities;
	}
	
	/*
	 * Get list of Facilities from specific table
	 */
	function getAllFacilities() {
		global $pearDB;

		$res =& $pearDB->query("SELECT * FROM mod_syslog_filters_facility ORDER BY CAST(value AS UNSIGNED) ASC");
		if (PEAR::isError($pearDB)) {
			print "Mysql Error : ". $pearDB->getMessage()."\n";
		}
		# Set base value
		$FilterFacilities  =  array("" => "");
		
		while ($facility =& $res->fetchRow())
			$FilterFacilities[$facility['key']] = $facility['key']; 
			
		//$FilterFacilities = array_map("myDecode",$FilterFacilities );
		return $FilterFacilities;
	}
	
	/*
	 * Get list of Facilities from specific table
	 */
	function getAllSeverities() {
		global $pearDB;

		$res =& $pearDB->query("SELECT * FROM mod_syslog_filters_priority ORDER BY CAST(value AS UNSIGNED) ASC");
		if (PEAR::isError($pearDB)) {
			print "Mysql Error : ". $pearDB->getMessage()."\n";
		}
		# Set base value
		$FilterSeverities  =  array("" => "");
		
		while ($severity =& $res->fetchRow())
			$FilterSeverities[$severity['key']] = $severity['key']; 
			
		$FilterSeverities = array_map("myDecode",$FilterSeverities );
		return $FilterSeverities;
	}

	/*
	 * Get list of syslog collector define into database
	 */
	function getCollectorList() {
	    global $pearDB;

		$res =& $pearDB->query("SELECT `collector_id`, `collector_name` FROM `mod_syslog_collector` ");
		if (PEAR::isError($pearDB)) {
			print "Mysql Error : ". $pearDB->getMessage()."\n";
		}
		# Set base value
		$collectorsList =  array("" => "");
		
		while ($element =& $res->fetchRow()) {
			$collectorsList[$element['collector_id']] = $element['collector_name']; 
		}

		return $collectorsList;
	}
?>
