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
 * SVN : $URL$
 * SVN : $Id$
 * 
 */

	require_once $centreon_path . "www/modules/centreon-syslog-frontend/include/common/header.php";

	/*
	 * Set language
	*/
	$locale = $oreon->user->get_lang();
	putenv("LANG=$locale");
	setlocale(LC_ALL, $locale);
	bindtextdomain("messages", $syslog_mod_path . "locale/");
	bind_textdomain_codeset("messages", "UTF-8");
	textdomain("messages");

	# Pagination
	include("./include/common/autoNumLimit.php");

	/*
	 * Pear library
	*/
	require_once "HTML/QuickForm.php";
	require_once 'HTML/QuickForm/advmultiselect.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	/*
	 * PHP functions
	*/
	require_once $syslog_mod_path. "include/common/common-Func.php";
	require_once "./include/common/common-Func.php";

	/*
	 * Database retrieve information for Centreon-Syslog
	*/
	$pearCentreonDB = new SyslogDB("centreon");

	$collectorList = getCollectorList();

	# QuickSearch form
	include_once("./include/common/quickSearch.php");

	# Set limit & num
	$DBRESULT =& $pearCentreonDB->query("SELECT maxViewMonitoring FROM general_opt LIMIT 1");
	if (PEAR::isError($DBRESULT)) {
		# For Centreon 2.1 compatibility
		$DBRESULT =& $pearCentreonDB->query("SELECT `value` FROM options WHERE `key`=\"maxViewMonitoring\" LIMIT 1");
		$row = $DBRESULT->fetchRow();
		$gopt = $row["value"];
		if (PEAR::isError($DBRESULT)) {
			print "Mysql Error : ".$DBRESULT->getMessage()."\n";
		}
	} else {
		$gopt = array_map("myDecode", $DBRESULT->fetchRow());
	}

	# Get end Post values
	isset ($_GET["num"]) ? $num = $_GET["num"] : $num = 0;
	isset ($_GET["search"]) ? $search = $_GET["search"] : $search = NULL;

	# Get collectors
	( isset($_POST["collector_id"]) && ($_POST["collector_id"] != ""  )) ? $collectorP = $_POST["collector_id"] : $collectorP = NULL;
	( isset($_GET["collector_id"]) && ($_GET["collector_id"] != ""  )) ? $collectorG = $_GET["collector_id"] : $collectorG = NULL;
	( isset($collectorP) ) ? $collector = $collectorP : $collector = $collectorG ;
	
	$error = 0;
	
	if (isset($collector)) {
		if (isset($search)) {
			$DBRESULT = & $pearDB->query("SELECT COUNT(*) FROM mod_syslog_hosts WHERE (mod_syslog_hosts.host_syslog_name LIKE '%".htmlentities($search, ENT_QUOTES)."%' OR mod_syslog_hosts.host_syslog_ipv4 LIKE '%".htmlentities($search, ENT_QUOTES)."%') AND collector_id = '".$collector."' ");
		} else {
			$DBRESULT = & $pearDB->query("SELECT COUNT(*) FROM mod_syslog_hosts WHERE collector_id = '".$collector."' ");
		}
		
		$tmp = & $DBRESULT->fetchRow();
		$rows = $tmp["COUNT(*)"];
		unset($DBRESULT);
		
		include("./include/common/checkPagination.php");
		
		#Host list
		if ($search) {
			$rq = "SELECT * FROM mod_syslog_hosts WHERE (mod_syslog_hosts.host_syslog_name LIKE '%".htmlentities($search, ENT_QUOTES)."%' OR mod_syslog_hosts.host_syslog_ipv4 LIKE '%".htmlentities($search, ENT_QUOTES)."%') AND collector_id = '".$collector."' LIMIT ".$num * $limit.", ".$limit;
		} else {
			$rq = "SELECT * FROM mod_syslog_hosts WHERE collector_id = '".$collector."' LIMIT ".$num * $limit.", ".$limit;
		}
		$DBRESULT =& $pearDB->query($rq);
		
		$rq = "SELECT host_id, host_name FROM host WHERE host_register = '1'";
		$HOSTRESULT =& $pearDB->query($rq);
		
		$CentreonHostID = array();
		while ($host =& $HOSTRESULT->fetchRow()) {
			$CentreonHostID[$host["host_id"]] = $host["host_name"];
		}
		unset($HOSTRESULT);
		
		$form = new HTML_QuickForm('select_form', 'POST', "?p=".$p."&o=l");
		#Different style between each lines
		$style = "one";
		#Fill a tab with a mutlidimensionnal Array we put in $tpl
		$elemArr = array();
		for ($i = 0; $host =& $DBRESULT->fetchRow(); $i++) {
			if (isset($CentreonHostID[$host['host_centreon_id']])) {
				$name = $CentreonHostID[$host['host_centreon_id']];
			} else {
				$name = _("any correspondence with a Centreon host");
			}
			
			$host['state'] != 1 ? $state = "not linked" : $state = "linked to Centreon host";
		
			$elemArr[$i] = array("MenuClass"=>"list_".$style,
								"RowMenu_link"=>"main.php?p=60501&o=w&host_id=".$host['id'],
								"RowMenu_centreonHostName"=>$name,
								"RowMenu_hostName"=>$host['host_syslog_name'],
								"RowMenu_hostIPV4"=>$host['host_syslog_ipv4'],
								"RowMenu_state"=>$state);
			$style != "two" ? $style = "two" : $style = "one";
		}
		unset($DBRESULT);
	}
	
	$tpl = new Smarty();
	$path = $syslog_mod_path . "include/configuration/configHosts";
	$tpl = initSmartyTpl($path, $tpl);
	$tpl->assign("headerMenu_collectors", _("Collectors:"));
	$tpl->assign("headerMenu_centreon_name", _("Centreon Host Name"));
	$tpl->assign("headerMenu_syslog_dns", _("Syslog Name"));
	$tpl->assign("headerMenu_syslog_ipv4", _("Syslog IP v4"));
	$tpl->assign("headerMenu_state", _("State"));
	$tpl->assign("elemArr", $elemArr);
	
	$form_host = new HTML_QuickForm('Formhost', 'post');
	$form_host->addElement('select', 'collectors', _("Collectors:"), $collectorList, array("onChange"=>"javascript:window.location.href='?o=l&p=".$p."&collector_id='+this.value"));
	$form_host->setDefaults(array('collectors' => $collector));
	
	if (isset($collector)) {
		$form_host->addElement('button', 'import',  _("Syslog Import"), array("onClick"=>"javascript:window.location.href='?p=".$p."&o=si&collector_id=".$collector."'"));
	}
		$form_host->addElement('button', 'add',  _("Add"), array("onClick"=>"javascript:window.location.href='?p=".$p."&o=a'"));
   	
	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl);
	$form_host->accept($renderer);
	$tpl->assign('Formhost', $renderer->toArray());
	$tpl->display($syslog_configuration_path . "template/listHosts.ihtml");
?>
