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
 * Project name : Centreon Syslog
 * Module name: Centreon-Syslog-Frontend
 * 
 * SVN : $URL:$
 * SVN : $Id:$
 * 
 */
 
	if (!isset($oreon))
		exit();
	
	function testExistence ($name = NULL)	{
		global $pearDB, $form;
		$id = NULL;
		if (isset($form))
			$id = $form->getSubmitValue('id');
		$DBRESULT = $pearDB->query("SELECT `collector_name`, `collector_id` FROM `mod_syslog_collector` WHERE `collector_name` = '".htmlentities($name, ENT_QUOTES, "UTF-8")."'");
		$poller = $DBRESULT->fetchRow();
		if ($DBRESULT->numRows() >= 1 && $poller["collector_id"] == $id) #Modif case	
			return true;
		else if ($DBRESULT->numRows() >= 1 && $poller["collector_id"] != $id) #Duplicate entry
			return false;
		else
			return true;
	}	
	
	function enablePollerInDB ($id = null)	{
		if (!$id) return;
		global $pearDB, $oreon;
		$DBRESULT = $pearDB->query("UPDATE `mod_syslog_collector` SET `enable` = '1' WHERE `collector_id` = '".$id."'");
	}
	
	function disablePollerInDB ($id = null)	{
		if (!$id) return;
		global $pearDB,$oreon;
		$DBRESULT = $pearDB->query("UPDATE `mod_syslog_collector` SET `enable` = '0' WHERE `collector_id` = '".$id."'");
	}
	
	function deletePollerInDB ($ndomod = array())	{
		global $pearDB;
		foreach($ndomod as $key => $value)	{
			$DBRESULT = $pearDB->query("DELETE FROM `mod_syslog_collector` WHERE `collector_id` = '".$key."'");
		}
	}
	
	function multiplePollerInDB ($ndomod = array(), $nbrDup = array())	{
		foreach($ndomod as $key => $value)	{
			global $pearDB;
			$DBRESULT = $pearDB->query("SELECT * FROM `mod_syslog_collector` WHERE `collector_id` = '".$key."' LIMIT 1");
			$row = $DBRESULT->fetchRow();
			$row["collector_id"] = '';
			$row["enable"] = '0';
			$DBRESULT->free();
			for ($i = 1; $i <= $nbrDup[$key]; $i++)	{
				$val = null;
				foreach ($row as $key2=>$value2)	{
					$key2 == "collector_name" ? ($poller_name = $value2 = $value2."_".$i) : null;
					$val ? $val .= ($value2!=NULL?(", '".$value2."'"):", NULL") : $val .= ($value2!=NULL?("'".$value2."'"):"NULL");
				}
				if (testExistence($poller_name))	{
					$val ? $rq = "INSERT INTO `mod_syslog_collector` VALUES (".$val.")" : $rq = null;
					$DBRESULT = $pearDB->query($rq);
				}
			}
		}
	}
	
	function updatePollerInDB ($id = NULL)	{
		if (!$id) return;
		updatePoller($id);
	}	
	
	function insertPollerInDB ()	{
		$id = insertPoller();
		return ($id);
	}
	
	function insertPoller($ret = array())	{
		global $form, $pearDB, $oreon;
		if (!count($ret))
			$ret = $form->getSubmitValues();
		
		$rq = "INSERT INTO `mod_syslog_collector` (" .
				"`collector_name`, `db_server_address`, `db_server_port`, `db_type`, `db_name`," .
				" `db_username`, `db_password`, `db_table_logs`, `db_table_logs_merge`, `db_table_cache`, " .
				"`db_table_cache_merge`, `ssh_server_address`, `ssh_server_port`,  `ssh_username`, " .
				"`ssh_password`, `configuration_dir`, `retention_days`, `enable`, `comment`) ";
		$rq .= "VALUES (";
		isset($ret["collector_name"]) && $ret["collector_name"] != NULL ? $rq .= "'".htmlentities($ret["collector_name"], ENT_QUOTES, "UTF-8")."', " : $rq .= "NULL, ";
		isset($ret["db_server_address"]) && $ret["db_server_address"] != NULL ? $rq .= "'".htmlentities($ret["db_server_address"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "NULL, ";
        isset($ret["db_server_port"]) && $ret["db_server_port"] != NULL ? $rq .= "'".htmlentities($ret["db_server_port"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "NULL, ";
        isset($ret["db_type"]) && $ret["db_type"] != NULL ? $rq .= "'".htmlentities($ret["db_type"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "NULL, ";
        isset($ret["db_name"]) && $ret["db_name"] != NULL ? $rq .= "'".htmlentities($ret["db_name"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "NULL, ";
        isset($ret["db_username"]) && $ret["db_username"] != NULL ? $rq .= "'".$ret["db_username"]."',  "  : $rq .= "NULL, ";
        isset($ret["db_password"]) && $ret["db_password"] != NULL ? $rq .= "'".htmlentities($ret["db_password"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "NULL, ";
 	    isset($ret["db_table_logs"]) && $ret["db_table_logs"] != NULL ? $rq .= "'".htmlentities($ret["db_table_logs"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "NULL, ";
        isset($ret["db_table_logs_merge"]) && $ret["db_table_logs_merge"] != NULL ? $rq .= "'".htmlentities($ret["db_table_logs_merge"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "NULL, ";
        isset($ret["db_table_cache"]) && $ret["db_table_cache"] != NULL ? $rq .= "'".$ret["db_table_cache"]."',  " : $rq .= "NULL, ";
        isset($ret["db_table_cache_merge"]) && $ret["db_table_cache_merge"] != NULL ? $rq .= "'".$ret["db_table_cache_merge"]."',  " : $rq .= "NULL, ";
        isset($ret["ssh_server_address"]) && $ret["ssh_server_address"] != NULL ? $rq .= "'".$ret["ssh_server_address"]."',  " : $rq .= "NULL, ";
        isset($ret["ssh_server_port"]) && $ret["ssh_server_port"] != NULL ? $rq .= "'".$ret["ssh_server_port"]."',  " : $rq .= "NULL, ";
        isset($ret["ssh_username"]) && $ret["ssh_username"] != NULL ? $rq .= "'".$ret["ssh_username"]."',  " : $rq .= "NULL, ";
        isset($ret["ssh_password"]) && $ret["ssh_password"] != NULL ? $rq .= "'".$ret["ssh_password"]."',  " : $rq .= "NULL, ";
        isset($ret["configuration_dir"]) && $ret["configuration_dir"] != NULL ? $rq .= "'".$ret["configuration_dir"]."',  " : $rq .= "NULL, ";
        isset($ret["retention_days"]) && $ret["retention_days"] != NULL ? $rq .= "'".$ret["retention_days"]."',  " : $rq .= "NULL, ";
        isset($ret["comment"]) && $ret["comment"] != NULL ? $rq .= "'".$ret["comment"]."',  " : $rq .= "NULL, ";
        isset($ret["enable"]) && $ret["enable"]["enable"] != NULL ? $rq .= "'".$ret["enable"]["enable"]."')" : $rq .= "NULL )";
       	$DBRESULT = $pearDB->query($rq);
		$DBRESULT = $pearDB->query("SELECT MAX(id) FROM `cfg_ndomod`");
		$ndomod_id = $DBRESULT->fetchRow();
		$DBRESULT->free();
		return ($ndomod_id["MAX(id)"]);
	}
	
	function updatePoller($id = null)	{
		global $form, $pearDB;
		print "toto<BR\>";
		if (!$id) 
			return;
		
		$ret = array();
		$ret = $form->getSubmitValues();
		$rq = "UPDATE `mod_syslog_collector` SET ";
        isset($ret["collector_name"]) && $ret["collector_name"] != NULL ? $rq .= "collector_name = '".htmlentities($ret["collector_name"], ENT_QUOTES, "UTF-8")."', " : $rq .= "collector_name = NULL, ";
       	isset($ret["db_server_address"]) && $ret["db_server_address"] != NULL ? $rq .= "db_server_address = '".htmlentities($ret["db_server_address"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "db_server_address = NULL, ";
       	isset($ret["db_server_port"]) && $ret["db_server_port"] != NULL ? $rq .= "db_server_port = '".htmlentities($ret["db_server_port"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "db_server_port = NULL, ";
       	isset($ret["db_type"]) && $ret["db_type"] != NULL ? $rq .= "db_type = '".htmlentities($ret["db_type"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "db_type = NULL, ";
		isset($ret["db_name"]) && $ret["db_name"] != NULL ? $rq .= "db_name = '".htmlentities($ret["db_name"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "db_name = NULL, ";
		isset($ret["db_username"]) && $ret["db_username"] != NULL ? $rq .= "db_username = '".htmlentities($ret["db_username"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "db_username = NULL, ";
		isset($ret["db_password"]) && $ret["db_password"] != NULL ? $rq .= "db_password = '".htmlentities($ret["db_password"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "db_password = NULL, ";
		isset($ret["db_table_logs"]) && $ret["db_table_logs"] != NULL ? $rq .= "db_table_logs = '".htmlentities($ret["db_table_logs"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "db_table_logs = NULL, ";
		isset($ret["db_table_logs_merge"]) && $ret["db_table_logs_merge"] != NULL ? $rq .= "db_table_logs_merge = '".htmlentities($ret["db_table_logs_merge"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "db_table_logs_merge = NULL, ";
		isset($ret["db_table_cache"]) && $ret["db_table_cache"] != NULL ? $rq .= "db_table_cache = '".htmlentities($ret["db_table_cache"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "db_table_cache = NULL, ";
		isset($ret["db_table_cache_merge"]) && $ret["db_table_cache_merge"] != NULL ? $rq .= "db_table_cache_merge = '".htmlentities($ret["db_table_cache_merge"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "db_table_cache_merge = NULL, ";
		isset($ret["ssh_server_address"]) && $ret["ssh_server_address"] != NULL ? $rq .= "ssh_server_address = '".htmlentities($ret["ssh_server_address"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "ssh_server_address = NULL, ";
		isset($ret["ssh_server_port"]) && $ret["ssh_server_port"] != NULL ? $rq .= "ssh_server_port = '".htmlentities($ret["ssh_server_port"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "ssh_server_port = NULL, ";
		isset($ret["ssh_username"]) && $ret["ssh_username"] != NULL ? $rq .= "ssh_username = '".htmlentities($ret["ssh_username"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "ssh_username = NULL, ";
		isset($ret["ssh_password"]) && $ret["ssh_password"] != NULL ? $rq .= "ssh_password = '".htmlentities($ret["ssh_password"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "ssh_password = NULL, ";
		isset($ret["configuration_dir"]) && $ret["configuration_dir"] != NULL ? $rq .= "configuration_dir = '".htmlentities($ret["configuration_dir"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "configuration_dir = NULL, ";
		isset($ret["retention_days"]) && $ret["retention_days"] != NULL ? $rq .= "retention_days = '".htmlentities($ret["retention_days"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "retention_days = NULL, ";
		isset($ret["comment"]) && $ret["comment"] != NULL ? $rq .= "comment = '".htmlentities($ret["comment"], ENT_QUOTES, "UTF-8")."',  " : $rq .= "comment = NULL, ";
		$rq .= "enable = '".$ret["enable"]["enable"]."' ";
		$rq .= "WHERE collector_id = '".$id."'";
		print $rq;
		$DBRESULT = $pearDB->query($rq);
	}
?>