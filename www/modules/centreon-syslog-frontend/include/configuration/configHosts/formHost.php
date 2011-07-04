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

	include $syslog_mod_path. 'include/common/common-Func.php';
	/*
	 * Resource Hosts
	 */
	$hostListInCentreon = getHostNameAndID();
	
	$attrsText = array("size"=>"35");

	/*
	 * a => Add
	 * w => Watch
	 * m => Modify
	 */
	
	/*
	 * Form begin
	 */
	$form = new HTML_QuickForm('Form', 'post', "?p=".$p);
	$form->addElement('header', 'title', _("Link a Centreon host to a Syslog host"));
	$form->addElement('select', 'centreon_hosts', _("Centreon host name"), $hostListInCentreon);
	$form->addElement('text', 'syslog_name', _("Syslog host name"), $attrsText);
	$form->addElement('text', 'syslog_ip', _("Syslog host IP address"), $attrsText);
	
	$form->applyFilter('_ALL_', 'trim');
	$form->addRule('centreon_hosts', _("Required Field"), 'required');
	$form->addRule('syslog_name', _("Required Field"), 'required');
	$form->addRule('syslog_ip', _("Required Field"), 'required');
	$form->setRequiredNote("<font style='color: red;'>*</font>&nbsp;". _("Required fields"));

	/*
	 * Smarty template Init
	 */
	$tpl = new Smarty();
	$tpl = initSmartyTpl($path, $tpl);
	
	/*
	 * a => Add
	 * w => Watch
	 * m => Modify
	 */
	if ($o == "a") {
		$subC =& $form->addElement('submit', 'submitC', _("Create"));
		$res =& $form->addElement('reset', 'reset', _("Reset"));
	} else if ($o == "w") {
		$form->freeze();
		$subM =& $form->addElement('submit', 'submitM', _("Modify"));
	} else if ($o == "m") {
		$subS =& $form->addElement('submit', 'submitS', _("Save"));
		$res =& $form->addElement('reset', 'reset', _("Reset"));
	}
	
	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl);
	$renderer->setRequiredTemplate('{$label}&nbsp;<font color="red" size="1">*</font>');
	$renderer->setErrorTemplate('<font color="red">{$error}</font><br />{$html}');
	$form->accept($renderer);
	$tpl->assign('form', $renderer->toArray());
	$tpl->assign('o', $o);
	$tpl->display($syslog_mod_path. "include/configuration/configHosts/template/formHost.ihtml");
?>