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

	if (!isset($oreon)) {
		exit();
	}

	/*
	 * Init Language 
	 */
	$locale = $oreon->user->get_lang();
	putenv("LANG=$locale");
	setlocale(LC_ALL, $locale);
	bindtextdomain("messages", "./modules/Syslog/locale/");
	bind_textdomain_codeset("messages", "UTF-8"); 
	textdomain("messages");

	/*
	 * Defined path
	 */
	$syslog_mod_path = $centreon_path . "www/modules/centreon-syslog-frontend/";

	/*
	 * Pear library
	 */
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/advmultiselect.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	/*
	 * Common functions
	 */
	require_once $syslog_mod_path . 'include/common/common-Func.php';

	/*
	 * Database retrieve information for Centreon-Syslog
	 */
	$cfg_syslog = getSyslogOption();

	/*
	 * Var information to format the element
	 */
	$attrsText 		= array("size"=>"50");
	$attrsText2		= array("size"=>"20");
	$attrsText3		= array("size"=>"5");

	/*
	 * Form begin
	 */
	$form = new HTML_QuickForm('Form', 'post', "?p=".$p);
	$form->addElement('header', 'title', _("Syslog Options"));

	# Database information
	$form->addElement('text', 'syslog_db_server', _("IP or DNS name"), $attrsText );
	$form->addElement('text', 'syslog_db_name', _("Database name"), $attrsText );
	$form->addElement('text', 'syslog_db_user', _("Database user"), $attrsText2 );
	$form->addElement('password', 'syslog_db_password', _("Password of Database user"), $attrsText2);
	$form->addElement('text', 'syslog_db_logs', _("Logs table name"), $attrsText );
	$form->addElement('text', 'syslog_db_logs_merge', _("Logs Merge table name"), $attrsText );
	$form->addElement('text', 'syslog_db_cache', _("Cache table name"), $attrsText );
	$form->addElement('text', 'syslog_db_cache_merge', _("Cache Merge table name"), $attrsText );

	# SSH information
	$form->addElement('text', 'syslog_ssh_server', _("IP or DNS name"), $attrsText );
	$form->addElement('text', 'syslog_ssh_user', _("Username for SSH connection"), $attrsText2);
	$form->addElement('password', 'syslog_ssh_pass', _("Password for SSH connection"), $attrsText2);
	$form->addElement('text', 'syslog_ssh_port', _("SSH port"), $attrsText3);

	# Configuration
	$form->addElement('text', 'syslog_conf_dir', _("Configuration directory"), $attrsText);
	$form->addElement('text', 'syslog_db_rotate', _("Duration of retention of data"), $attrsText3);
	$form->addElement('text', 'refresh_monitoring', _("Refresh Interval for monitoring"), $attrsText3);
	$form->addElement('text', 'refresh_filters', _("Refresh Interval for filters"), $attrsText3);

	$redirect =& $form->addElement('hidden', 'o');
	$redirect->setValue($o);

	/*
	 * Form Rules
	 */	
	$form->applyFilter('_ALL_', 'trim');
	$form->addRule('syslog_db_server', _("Required Field"), 'required');
	$form->addRule('syslog_db_name', _("Required Field"), 'required');
	$form->addRule('syslog_db_user', _("Required Field"), 'required');
	$form->addRule('syslog_db_password', _("Required Field"), 'required');
	$form->addRule('syslog_db_logs', _("Required Field"), 'required');
	$form->addRule('syslog_db_logs_merge', _("Required Field"), 'required');
	$form->addRule('syslog_db_cache', _("Required Field"), 'required');
	$form->addRule('syslog_db_cache_merge', _("Required Field"), 'required');
	$form->addRule('syslog_ssh_server', _("Required Field"), 'required');
	$form->addRule('syslog_ssh_user', _("Required Field"), 'required');
	$form->addRule('syslog_ssh_pass', _("Required Field"), 'required');
	$form->addRule('syslog_ssh_user', _("Required Field"), 'required');
	$form->addRule('syslog_conf_dir', _("Required Field"), 'required');
	$form->addRule('syslog_db_rotate', _("Required Field"), 'required');
	$form->addRule('refresh_monitoring', _("Required Field"), 'required');
	$form->addRule('refresh_filters', _("Required Field"), 'required');

	if (isset($cfg_syslog)) {
		$form->setDefaults($cfg_syslog);
	} else {
		$form->setDefaults(array(
		"syslog_db"=>'syslog',
		"syslog_db_user"=>'syslogadmin',
		"syslog_db_logs"=>'logs',
		"syslog_db_logs_merge"=>'all_logs',
		"syslog_db_cache"=>'cache',
		"syslog_db_cache_merge"=>'all_cache',
		"syslog_ssh_user"=>'syslog',
		"syslog_ssh_port"=>'22',
		"syslog_conf_dir"=>'/usr/local/syslog/etc/',
		"syslog_db_rotate"=>'31',
		"refresh_monitoring"=>'10',
		"refresh_filters"=>'240'));
	}
	#End of form definition

	if ($form->validate()) {
		if ($form->getSubmitValue("submitC")) {
			updateSyslogConfigData();
		}
	}
	
	/*
	 * Smarty template Init
	 */
	$tpl = new Smarty();
	$tpl = initSmartyTpl($syslog_mod_path . "include/administration", $tpl);

	/*
	 * Add buttons
	 */
	if ($o == "m") {
		$subC =& $form->addElement('submit', 'submitC', _("Save"));
		$res =& $form->addElement('reset', 'reset', _("Reset"));
		$redirect->setValue("f");
	} else if ($o == "f") {
		$form->freeze();
		if (extension_loaded('ssh2')) {
			$form->addElement('button', 'export',  _("Export configuration"), array( "id" => "ajaxBtn", "onClick" => "javascript:ajax_generate();") );
		} else {
			print ("<div class='ssh2' align='center'>");
			print ("<br/>Export button is hidden because SSH2 libraries for PHP was not installed.");
			print ("<br/>Please refer to install guide for Centreon-Syslog-Frontend.");
			print ("<br/></div>");
		}
		$form->addElement('button', 'modify', _("Modify"), array("onClick"=>"javascript:window.location.href='?p=".$p."&o=m'"));
		
	}
	
	/*
	 * Apply a template definition
	 */
	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl);
	$renderer->setRequiredTemplate('{$label}&nbsp;<font color="red" size="1">*</font>');
	$renderer->setErrorTemplate('<font color="red">{$error}</font><br />{$html}');
	$form->accept($renderer);
	$tpl->assign('form', $renderer->toArray());
	$tpl->assign('o', $o);
	$tpl->assign('syslog_db_general', _("General DB information"));
	$tpl->assign('syslog_db_logs', _("Logs tables"));
	$tpl->assign('syslog_db_cache', _("Cache tables"));
	$tpl->assign('syslog_ssh', _("SSH information"));
	$tpl->assign('syslog_server_configuration', _("Configuration of syslog collector"));
	$tpl->assign('syslog_monitoring_configuration', _("Frontend configuration"));
	$tpl->assign('days', _("days"));
	$tpl->assign('seconds', _("seconds"));
	$tpl->assign('sort1', _("Database"));		
	$tpl->assign('sort2', _("SSH"));		
	$tpl->assign('sort3', _("Configuration"));
	$tpl->display($syslog_mod_path. "include/administration/template/formSyslogAdmin.ihtml");
 ?>