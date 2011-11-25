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

$refresh_options = getRefreshInfo();

if ($refresh_options["refresh_monitoring"] == 0) {
	$refresh_time = 10000;
} else {
	$refresh_time = $refresh_options["refresh_monitoring"] * 1000;
}
if ($refresh_options["refresh_filters"] == 0) {
	$refreshFilters_time = 10000;
} else {
	$refreshFilters_time = $refresh_options["refresh_filters"] * 1000;
}
?>

<script type="text/javascript" src="./modules/centreon-syslog-frontend/include/monitoring/javascript/xslt.js"></script>
<script type="text/javascript">
	var _sid = '<?php echo session_id(); ?>';
	var _mySyslogtime_reload = <?php echo $refresh_time; ?>;
	var _mySyslogtimeFilters_reload = <?php echo $refreshFilters_time; ?>;
	var _lock = 0;
	var _lockFilter = 0;
	var _mySyslogTimeOut = 0;
	var _mySyslogFiltersTimeOut = 0;
	var _start_label = "<?php echo _("start");?>";
	var _stop_label = "<?php echo _("stop");?>";

	function rebuild_page() {
		build_ajaxFilters();
		build_ajax();
	}
	
	function build_ajax() {
		_lock = 1;
		clearTimeout(_mySyslogTimeOut);
		var proc = new Transformation();

		if (document.getElementById('collector_id')) var collector_id = document.getElementById('collector_id').value;
		if (document.getElementById('filter_host')) var filter_host = document.getElementById('filter_host').value;
		if (document.getElementById('filter_facility')) var filter_facility = document.getElementById('filter_facility').value;
		if (document.getElementById('filter_Ffacility')) var filter_Ffacility = document.getElementById('filter_Ffacility').value;
		if (document.getElementById('filter_severity')) var filter_severity = document.getElementById('filter_severity').value;
		if (document.getElementById('filter_Fseverity')) var filter_Fseverity = document.getElementById('filter_Fseverity').value;
		if (document.getElementById('filter_program')) var filter_program = escape(document.getElementById('filter_program').value);

		var addrXML = "./modules/centreon-syslog-frontend/include/monitoring/xml/syslog_xml.php?sid=" + _sid + '&collector_id=' + collector_id + '&host=' + filter_host + '&facility=' + filter_facility + '&Ffacility=' + filter_Ffacility + '&severity=' + filter_severity + '&Fseverity=' + filter_Fseverity + '&program=' + filter_program;
		var addrXSL = "./modules/centreon-syslog-frontend/include/monitoring/xsl/syslog.xsl";

		proc.setXml(addrXML);
		proc.setXslt(addrXSL);
		proc.transform("ajaxLog");
		_lock = 0;

		_mySyslogTimeOut = setTimeout('build_ajax()', _mySyslogtime_reload);
	}

	function build_ajaxFilters() {
		_lockFilter = 1;
		clearTimeout(_mySyslogFiltersTimeOut);
		var proc = new Transformation();

		if (document.getElementById('collector_id')) var collector_id = document.getElementById('collector_id').value;
		if (document.getElementById('filter_host')) var filter_host = document.getElementById('filter_host').options[document.getElementById('filter_host').selectedIndex].value;
		if (document.getElementById('filter_facility')) var filter_facility = document.getElementById('filter_facility').options[document.getElementById('filter_facility').selectedIndex].value;
		if (document.getElementById('filter_Ffacility')) var filter_Ffacility = document.getElementById('filter_Ffacility').options[document.getElementById('filter_Ffacility').selectedIndex].value;
		if (document.getElementById('filter_severity')) var filter_severity = document.getElementById('filter_severity').options[document.getElementById('filter_severity').selectedIndex].value;
		if (document.getElementById('filter_Fseverity')) var filter_Fseverity = document.getElementById('filter_Fseverity').options[document.getElementById('filter_Fseverity').selectedIndex].value;
		if (document.getElementById('filter_program')) var filter_program = escape(document.getElementById('filter_program').options[document.getElementById('filter_program').selectedIndex].value);

		var addrXML = "./modules/centreon-syslog-frontend/include/monitoring/xml/syslog_filters_xml.php?sid=" + _sid + '&collector_id=' + collector_id + '&host=' + filter_host + '&facility=' + filter_facility + '&Ffacility=' + filter_Ffacility + '&severity=' + filter_severity + '&Fseverity=' + filter_Fseverity + '&program=' + filter_program;
		var addrXSL = "./modules/centreon-syslog-frontend/include/monitoring/xsl/syslog_filters.xsl";

		proc.setXml(addrXML);
		proc.setXslt(addrXSL);
		proc.transform("ajaxFilters");
		_lockFilter = 0;

		_mySyslogFiltersTimeOut = setTimeout('build_ajaxFilters()', _mySyslogtimeFilters_reload);
	}

	function stop_ajax() {
		clearTimeout(_mySyslogTimeOut);
		clearTimeout(_mySyslogtimeFilters_reload);
	}

	function start_ajax() {
		build_ajax();
		build_ajaxFilters();
	}

	function ajax_handler(value) {
		if (value == _stop_label) {
			document.getElementById('ajaxBtn').value = _start_label;
			document.getElementById('ajaxBtn').innerHTML =  _start_label;
			stop_ajax();
		}
		else {
			document.getElementById('ajaxBtn').value = _stop_label;
			document.getElementById('ajaxBtn').innerHTML =  _stop_label;
			start_ajax();
		}
	}
</script>