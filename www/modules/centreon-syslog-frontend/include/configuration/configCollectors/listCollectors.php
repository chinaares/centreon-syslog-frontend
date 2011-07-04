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

	if (!isset($oreon))
		exit();

	# Pagination
	include("./include/common/autoNumLimit.php");

	include_once("./include/common/quickSearch.php");

	if (isset($search))
		$DBRESULT = & $pearDB->query("SELECT COUNT(*) FROM mod_syslog_collector WHERE (mod_syslog_collector.collector_name LIKE '%".htmlentities($search, ENT_QUOTES)."%')");
	else
		$DBRESULT = & $pearDB->query("SELECT COUNT(*) FROM mod_syslog_collector");

	$tmp = & $DBRESULT->fetchRow();
	$rows = $tmp["COUNT(*)"];

	include("./include/common/checkPagination.php");

	# Smarty template Init
	$tpl = new Smarty();
	$tpl = initSmartyTpl($path, $tpl);

	# start header menu
	$tpl->assign("headerMenu_collector_name", _("Collector Name"));
	$tpl->assign("headerMenu_colletor_db_address", _("Collector Database Address"));
	$tpl->assign("headerMenu_db_name", _("Database Name"));
	$tpl->assign("headerMenu_colletor_ssh_address", _("Collector SSH Address"));
	# end header menu

	#Host list
	if ($search)
		$rq = "SELECT * FROM mod_syslog_collector WHERE (mod_syslog_collector.collector_name LIKE '%".htmlentities($search, ENT_QUOTES)."%') LIMIT ".$num * $limit.", ".$limit;
	else
		$rq = "SELECT * FROM mod_syslog_collector LIMIT ".$num * $limit.", ".$limit;
	$DBRESULT =& $pearDB->query($rq);

	$form = new HTML_QuickForm('select_form', 'POST', "?p=".$p);
	#Different style between each lines
	$style = "one";
	#Fill a tab with a mutlidimensionnal Array we put in $tpl
	$elemArr = array();
	for ($i = 0; $host =& $DBRESULT->fetchRow(); $i++) {
		$elemArr[$i] = array("MenuClass"=>"list_".$style,
						"collector_name"=>$host['collector_name'],
						"colletor_db_address"=>$host['db_server_address'],
						"colletor_db_name"=>$host['db_name'],
						"colletor_ssh_address"=>$host['ssh_server_address']);
		$style != "two" ? $style = "two" : $style = "one";
	}
	#Different messages we put in the template
	$tpl->assign('msg', array ("addL"=>"?p=".$p."&o=a", "addT"=>_("Add")));

	$tpl->assign("elemArr", $elemArr);
	$tpl->assign('limit', $limit);

	#
	##Apply a template definition
	#
	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl);
	$form->accept($renderer);
	$tpl->assign('form', $renderer->toArray());
	$tpl->display($syslog_configuration_path . "template/listCollectors.php.ihtml");
?>
