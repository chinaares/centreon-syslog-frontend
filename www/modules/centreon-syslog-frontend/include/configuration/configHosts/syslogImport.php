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
	if (!isset($oreon))
		exit();

	/*
	 * Path to the configuration dir
	 */
	require_once $centreon_path . "www/modules/centreon-syslog-frontend/include/common/header.php";

	/*
	 * Pear library
	 */
	require_once "HTML/QuickForm.php";
	require_once 'HTML/QuickForm/advmultiselect.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

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
	 * PHP functions
	 */
	require_once $syslog_mod_path ."include/common/common-Func.php";
	require_once $syslog_mod_path ."include/common/common-IP-Func.php";
	require_once $syslog_mod_path . "class/syslogDB.class.php";
	require_once $centreon_path . "www/include/common/common-Func.php";

	/*
	 * Database retrieve information for Centreon-Syslog
	 */
	if (isset($_GET['collector_id']) && $_GET['collector_id'] != "" )
		$collector_id = $_GET['collector_id'];

	$cfg_syslog = getSyslogOption($collector_id);

	# QuickSearch form
	include_once $centreon_path . "www/include/common/quickSearch.php";

	/*
	 * Get list of hostname already imported
	 */
	$req = "SELECT DISTINCT host_syslog_name FROM mod_syslog_hosts";
	$DBRESULT =& $pearDB->query($req);
	$hostsList = "";
	while ($host =& $DBRESULT->fetchRow()) {
		if (strcmp($hostsList, "") != 0) {
			$hostsList .= ", ";
		}
		$hostsList .= "'".$host["host_syslog_name"]."'";
	}

	$req = "SELECT DISTINCT host_syslog_ipv4 FROM mod_syslog_hosts";
	$DBRESULT =& $pearDB->query($req);
	while ($host =& $DBRESULT->fetchRow()) {
		if (strcmp($hostsList, "") != 0) {
			$hostsList .= ", ";
		}
		$hostsList .= "'".$host["host_syslog_ipv4"]."'";
	}

	/*
	 * Get list of hostname not imported
	 */
	if($search) {
		$req = "SELECT DISTINCT value FROM ".$cfg_syslog["db_table_cache_merge"] . " WHERE type = 'HOST' AND value LIKE '%".htmlentities($search, ENT_QUOTES)."%' ORDER BY value ";
	} else {
		if (strcmp($hostsList, "") != 0) {
			$req = "SELECT DISTINCT value FROM ".$cfg_syslog["db_table_cache_merge"] . " WHERE type = 'HOST' AND value NOT IN (".$hostsList.") ORDER BY value ";
		} else {
			$req = "SELECT DISTINCT value FROM ".$cfg_syslog["db_table_cache_merge"] . " WHERE type = 'HOST' ORDER BY value ";
		}
	}
	$pearSyslogDB = new SyslogDB("syslog", $collector_id);
	$DBRESULT =& $pearSyslogDB->query($req);

	# Smarty template Init
	$tpl = new Smarty();
	$tpl = initSmartyTpl($path, $tpl);

	# start header menu
	$tpl->assign("headerMenu_syslog_dns", _("Syslog Name"));
	$tpl->assign("headerMenu_syslog_ipv4", _("Syslog IP v4"));
	# end header menu

	$form = new HTML_QuickForm('select_form', 'POST', "?p=".$p);
	#Different style between each lines
	$style = "one";
	#Fill a tab with a mutlidimensionnal Array we put in $tpl

	$elemArr = array();
	for ($i = 0; $host =& $DBRESULT->fetchRow(); $i++) {
		if (is_ip_address($host["value"])) {
			$syslog_name = getDNSFromIP($host["value"]);			
			$syslog_ip = $host["value"];
		} else {
			$syslog_name = $host["value"];
			$syslog_ip = getIPFromDNS($host["value"]);
			
			if (strcmp($syslog_ip, $syslog_name) == 0) {
				$syslog_ip = _("no IP");
			}
		}

		$elemArr[$i] = array("MenuClass"=>"list_".$style,
						"hostName"=>$syslog_name,
						"hostIPV4"=>$syslog_ip);
		$style != "two" ? $style = "two" : $style = "one";

		$syslog_name = "";
		$syslog_ip = "";
	}

	$tpl->assign("elemArr", $elemArr);
	$tpl->assign('limit', $limit);

	#
	##Apply a template definition
	#
	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl);
	$form->addElement('button', 'import', _("Import"), array("onClick"=>"javascript:importHost('".$collector_id."');"));
	$form->accept($renderer);
	$tpl->assign('form', $renderer->toArray());
	$tpl->display($syslog_mod_path . "include/configuration/configHosts/template/syslogImport.ihtml");
?>
