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
		exit ();

	isset($_GET["host_id"]) ? $hG = $_GET["host_id"] : $hG = NULL;
	isset($_POST["host_id"]) ? $hP = $_POST["host_id"] : $hP = NULL;
	$hG ? $host_id = $hG : $host_id = $hP;

	isset($_GET["select"]) ? $cG = $_GET["select"] : $cG = NULL;
	isset($_POST["select"]) ? $cP = $_POST["select"] : $cP = NULL;
	$cG ? $select = $cG : $select = $cP;

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
	bindtextdomain("messages", "./modules/centreon-syslog/locale/");
	bind_textdomain_codeset("messages", "UTF-8");
	textdomain("messages");

	/*
	 * Path to the configuration dir
	 */
	global $syslog_mod_path;
	$syslog_mod_path = $centreon_path . "www/modules/centreon-syslog/";
	$syslog_configuration_path = $syslog_mod_path . "include/configuration/configHosts/";

	
	if (isset($_POST["o"]))
		$o = $_POST["o"];

	switch ($o)     {
		case "a"	: require_once($syslog_configuration_path."formHost.php"); break; #Add a host
		case "w"	: require_once($syslog_configuration_path."formHost.php"); break; #Watch a host
		case "m"	: require_once($syslog_configuration_path."formHost.php"); break; #Modify a host
		case "si"	: require_once($syslog_configuration_path."syslogImport.php"); break; #Import Syslog hosts
		case "l"	: require_once($syslog_configuration_path."listHosts.php"); break; #List hosts
		default		: require_once($syslog_configuration_path."listHosts.php"); break;
	}
 ?>