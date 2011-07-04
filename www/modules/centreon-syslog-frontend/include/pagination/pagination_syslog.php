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
 * SVN : $URL:$
 * SVN : $Id:$
 * 
 */

	include ("@CENTREON_ETC@centreon.conf.php");

	/*
	 * Defined path
	 */
	$syslog_mod_path = $centreon_path . "www/modules/centreon-syslog-frontend/";
	$path = $syslog_mod_path . "include/pagination/";	

	global $num, $limit, $search, $url, $pearDB;
	global $search_type_service, $search_type_host, $host_name;

	isset ($_GET["type"]) ? $type = $_GET["type"] : $stype = NULL;
	isset ($_GET["o"]) ? $o = $_GET["o"] : $o = NULL;

	( isset($_POST["filter_program"]) && ($_POST["filter_program"] != ""  )) ? $filter_programP = $_POST["filter_program"] : $filter_programP = NULL;
	( isset($_GET["filter_program"]) && ($_GET["filter_program"] != ""  )) ? $filter_programG = $_GET["filter_program"] : $filter_programG = NULL;	
	( isset($_POST["filter_host"]) && ($_POST["filter_host"] != "" )) ? $filter_hostP = $_POST["filter_host"] : $filter_hostP = NULL;
	( isset($_GET["filter_host"]) && ($_GET["filter_host"] != "" )) ? $filter_hostG = $_GET["filter_host"] : $filter_hostG = NULL;	
	( isset($_POST["filter_facility"]) && ($_POST["filter_facility"] != "" )) ? $filter_facilityP = $_POST["filter_facility"] : $filter_facilityP = NULL;
	( isset($_GET["filter_facility"]) && ($_GET["filter_facility"] != "" )) ? $filter_facilityG = $_GET["filter_facility"] : $filter_facilityG = NULL;	
 	( isset($_POST["filter_Ffacility"]) && ($_POST["filter_Ffacility"] != "" )) ? $filter_FfacilityP = $_POST["filter_Ffacility"] : $filter_FfacilityP = NULL;
	( isset($_GET["filter_Ffacility"]) && ($_GET["filter_Ffacility"] != "" )) ? $filter_FfacilityG = $_GET["filter_Ffacility"] : $filter_FfacilityG = NULL;	
 	( isset($_POST["filter_severity"]) && ($_POST["filter_severity"] != "" )) ? $filter_severityP = $_POST["filter_severity"] : $filter_severityP = NULL;
 	( isset($_GET["filter_severity"]) && ($_GET["filter_severity"] != "" )) ? $filter_severityG = $_GET["filter_severity"] : $filter_severityG = NULL; 	
 	( isset($_POST["filter_Fseverity"]) && ($_POST["filter_Fseverity"] != "" )) ? $filter_FseverityP = $_POST["filter_Fseverity"] : $filter_FseverityP = NULL;
 	( isset($_GET["filter_Fseverity"]) && ($_GET["filter_Fseverity"] != "" )) ? $filter_FseverityG = $_GET["filter_Fseverity"] : $filter_FseverityG = NULL; 	
 	( isset($_POST["filter_msg"]) && ($_POST["filter_msg"] != "" )) ? $filter_msgP = $_POST["filter_msg"] : $filter_msgP = NULL;
 	( isset($_GET["filter_msg"]) && ($_GET["filter_msg"] != "" )) ? $filter_msgG = $_GET["filter_msg"] : $filter_msgG = NULL;

 	if (isset($_GET["StartDate"])) {
		$StartDate = $_GET["StartDate"];
	} else if (isset($_POST["StartDate"])) {
		$StartDate = $_POST["StartDate"];
	} else if (isset($_POST["start_hidden"])) {
		$StartDate = $_POST["start_hidden"];
	}

	if (isset($_GET["EndDate"])) {
		$EndDate = $_GET["EndDate"];
	} else if (isset($_POST["EndDate"])) {
		$EndDate = $_POST["EndDate"];
	} else if (isset($_POST["end_hidden"])) {
		$EndDate = $_POST["end_hidden"];
	}

	if (isset($_GET["StartTime"])) {
		$StartTime = $_GET["StartTime"];
	} else if (isset($_POST["StartTime"])) {
		$StartTime = $_POST["StartTime"];
	} else if (isset($_POST["start_time"])) {
		$StartTime = $_POST["start_time"];
	} 

	if (isset($_GET["EndTime"])) {
		$EndTime = $_GET["EndTime"];
	} else if (isset($_POST["EndTime"])) {
		$EndTime = $_POST["EndTime"];
	} else if (isset($_POST["end_time"])) {
		$EndTime = $_POST["end_time"];
	} 
 	
	$filter_program = ( isset($filter_programP) ) ? $filter_program = $filter_programP :$filter_program = $filter_programG ;
	$filter_host = ( isset($filter_hostP)) ? $filter_host = $filter_hostP  : $filter_host = $filter_hostG;
	$filter_facility = ( isset($filter_facilityP)) ? $filter_facility = $filter_facilityP  : $filter_facility = $filter_facilityG;
	$filter_Ffacility = ( isset($filter_FfacilityP)) ? $filter_Ffacility = $filter_FfacilityP  : $filter_Ffacility = $filter_FfacilityG;	
	$filter_severity = ( isset($filter_severityP)) ? $filter_severity = $filter_severityP  : $filter_severity = $filter_severityG;
	$filter_Fseverity = ( isset($filter_FseverityP)) ? $filter_Fseverity = $filter_FseverityP  : $filter_Fseverity = $filter_FseverityG;
	$filter_msg = ( isset($filter_msgP)) ? $filter_msg = $filter_msgP  : $filter_msg = $filter_msgG;
	$end = ( isset($endP)) ? $end = $endP  : $end = $endG;
	$start = ( isset($startP)) ? $start = $startP  : $start = $startG;             
	$end_time = ( isset($end_timeP)) ? $end_time = $end_timeP  : $end_time = $end_timeG;
	$start_time = ( isset($start_timeP)) ? $start_time = $start_timeP  : $start_time = $start_timeG;   

	global $rows, $p, $lang, $gopt, $pagination;
	
	$tab_order = array("sort_asc" => "sort_desc", "sort_desc" => "sort_asc"); 	

	if (isset($_GET["search_type_service"])){
		$search_type_service = $_GET["search_type_service"];
		$oreon->search_type_service = $_GET["search_type_service"];
	} else if (isset($oreon->search_type_service))
		 $search_type_service = $oreon->search_type_service;
	else
		$search_type_service = NULL;

	if (isset($_GET["search_type_host"])){
		$search_type_host = $_GET["search_type_host"];
		$oreon->search_type_host = $_GET["search_type_host"];
	} else if (isset($oreon->search_type_host))
		 $search_type_host = $oreon->search_type_host;
	else
		$search_type_host = NULL;
	
	if (!isset($_GET["search_type_host"]) && !isset($oreon->search_type_host) && !isset($_GET["search_type_service"]) && !isset($oreon->search_type_service)){
		$search_type_host = 1;
		$oreon->search_type_host = 1;
		$search_type_service = 1;
		$oreon->search_type_service = 1;
	}

	$url_var = "";
	
	if (isset($_GET["order"])){
		$url_var .= "&order=".$_GET["order"];
		$order = $_GET["order"];
	}
	if (isset($_GET["sort_types"])){
		$url_var .= "&sort_types=".$_GET["sort_types"];
		$sort_type = $_GET["sort_types"];
	}
	
	if (isset($filter_program))
		$url_var .= "&filter_program=".$filter_program;
	if (isset($filter_host))
		$url_var .= "&filter_host=".$filter_host;
	if (isset($filter_facility))
		$url_var .= "&filter_facility=".$filter_facility;
	if (isset($filter_Ffacility))
		$url_var .= "&filter_Ffacility=".$filter_Ffacility;
	if (isset($filter_severity))
		$url_var .= "&filter_severity=".$filter_severity;
	if (isset($filter_Fseverity))
		$url_var .= "&filter_Fseverity=".$filter_Fseverity;	
	if (isset($filter_msg))
		$url_var .= "&filter_msg=".$filter_msg;
	
	if (isset($EndTime))
		$url_var .= "&EndTime=".$EndTime;
    if (isset($StartTime))
		$url_var .= "&StartTime=".$StartTime;
      
    if (isset($EndDate))
		$url_var .= "&EndDate=".$EndDate;
    if (isset($StartDate))
		$url_var .= "&StartDate=".$StartDate;

	# Smarty template Init
	$tpl = new Smarty();
	$tpl = initSmartyTpl($path, $tpl);


	$page_max = ceil($rows / $limit);
	if ($num > $page_max && $rows)
		$num = $page_max - 1;
		
	$pageArr = array();
	$istart = 0;
	for($i = 5, $istart = $num; $istart && $i > 0; $i--)
		$istart--;
	for($i2 = 0, $iend = $num; ( $iend <  ($rows / $limit -1)) && ( $i2 < (5 + $i)); $i2++)
		$iend++;
	for ($i = $istart; $i <= $iend; $i++){
		$pageArr[$i] = array("url_page"=>"./main.php?p=".$p."&num=$i&limit=".$limit."&search=".$search."&type=".$type."&o=" . $o . $url_var, "label_page"=>"<b>".($i +1)."</b>","num"=> $i);
	}

	if ($i > 1)							
		$tpl->assign("pageArr", $pageArr);

	$tpl->assign("num", $num);
	$tpl->assign("previous", _("previous"));
	$tpl->assign("next", _("next"));

	if (($prev = $num - 1) >= 0)
		$tpl->assign('pagePrev', ("./main.php?p=".$p."&num=$prev&limit=".$limit."&search=".$search."&type=".$type."&o=" . $o .$url_var));
	
	if (($next = $num + 1) < ($rows/$limit))
		$tpl->assign('pageNext', ("./main.php?p=".$p."&num=$next&limit=".$limit."&search=".$search."&type=".$type."&o=" . $o .$url_var));
	
	if (($rows / $limit) > 0)
		$tpl->assign('pageNumber', ($num +1)."/".ceil($rows / $limit));
	else
		$tpl->assign('pageNumber', ($num)."/".ceil($rows / $limit));

	#Select field to change the number of row on the page

	for ($i = 10; $i <= 100; $i = $i +10)
		$select[$i]=$i;
	if (isset($gopt[$pagination]) && $gopt[$pagination])
		$select[$gopt[$pagination]]=$gopt[$pagination];
	if (isset($rows) && $rows)
		$select[$rows]=$rows;
	ksort($select);
		
	$form = new HTML_QuickForm('select_form', 'GET' );
	$selLim =& $form->addElement('select', 'l', _("Lines: "), $select, array("onChange" => "setL(this.value);  this.form.submit()"));
	$selLim->setSelected($limit);
	
	#Element we need when we reload the page
	$form->addElement('hidden', 'p');
	$form->addElement('hidden', 'search');
	$form->addElement('hidden', 'num');
	$form->addElement('hidden', 'order');
	$form->addElement('hidden', 'type');
	$form->addElement('hidden', 'sort_types');
	$tab = array ("p" => $p, "search" => $search, "num"=>$num);
	$form->setDefaults($tab);

	# Init QuickForm
	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl);
	$form->accept($renderer);

	$tpl->assign("limite", $limite);
	$tpl->assign("begin", $num);
	$tpl->assign("end", $limit);
	$tpl->assign("page", _("Pages : "));
	$tpl->assign("order", $_GET["order"]);
	$tpl->assign("tab_order", $tab_order);
	$tpl->assign('javascript', "<SCRIPT LANGUAGE='JavaScript'>
	function setL(_this){
		var _l = document.getElementsByName('l');
		
				
		document.forms['Formfilterhidden'].elements['limit'].value = _this;
		_l[0].value = _this;
		_l[1].value = _this;
				
		document.forms['Formfilterhidden'].elements['filter_host'].value = document.forms['Formfilter'].elements['filter_host'].value  ;
		document.forms['Formfilterhidden'].elements['filter_facility'].value = document.forms['Formfilter'].elements['filter_facility'].value  ;
		document.forms['Formfilterhidden'].elements['filter_Ffacility'].value = document.forms['Formfilter'].elements['filter_Ffacility'].value  ;
		document.forms['Formfilterhidden'].elements['filter_severity'].value = document.forms['Formfilter'].elements['filter_severity'].value  ;
		document.forms['Formfilterhidden'].elements['filter_Fseverity'].value = document.forms['Formfilter'].elements['filter_Fseverity'].value  ;
		document.forms['Formfilterhidden'].elements['filter_program'].value = document.forms['Formfilter'].elements['filter_program'].value  ;
		document.forms['Formfilterhidden'].elements['filter_msg'].value = document.forms['Formfilter'].elements['filter_msg'].value  ;
		document.forms['Formfilterhidden'].elements['start_hidden'].value = document.forms['Formfilter'].elements['StartDate'].value  ;
		document.forms['Formfilterhidden'].elements['start_time'].value = document.forms['Formfilter'].elements['StartTime'].value  ;
		document.forms['Formfilterhidden'].elements['end_hidden'].value = document.forms['Formfilter'].elements['EndDate'].value  ;
		document.forms['Formfilterhidden'].elements['end_time'].value = document.forms['Formfilter'].elements['EndTime'].value  ;			
	}
</SCRIPT>" );
	
	$tpl->assign('select_form', $renderer->toArray());
	$tpl->display($path. "template/pagination_syslog.ihtml");
?>