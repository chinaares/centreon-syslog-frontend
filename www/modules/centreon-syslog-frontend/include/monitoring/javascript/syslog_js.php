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
 * SVN : $URL
 * SVN : $Id: syslog_js.php 378 2010-03-23 22:00:23Z lpinsivy $
 * 
 */

if ($cfg_syslog["syslog_refresh_monitoring"] == 0) {
	$refresh_time = 10000;
} else {
	$refresh_time = $cfg_syslog["syslog_refresh_monitoring"] * 1000;
}
if ($cfg_syslog["syslog_refresh_filters"] == 0) {
	$refreshFilters_time = 10000;
} else {
	$refreshFilters_time = $cfg_syslog["syslog_refresh_filters"] * 1000;
}
?>

<script type="text/javascript" src="./modules/centreon-syslog/include/monitoring/javascript/xslt.js"></script>
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

	function build_ajax() {
		_lock = 1;
		var proc = new Transformation();

		if (document.getElementById('filter_host')) {
			var filter_host = document.getElementById('filter_host').value;
			var filter_facility = document.getElementById('filter_facility').value;
			var filter_Ffacility = document.getElementById('filter_Ffacility').value;
			var filter_severity = document.getElementById('filter_severity').value;
			var filter_Fseverity = document.getElementById('filter_Fseverity').value;
			var filter_program = escape(document.getElementById('filter_program').value);

			var addrXML = "./modules/centreon-syslog/include/monitoring/xml/syslog_xml.php?sid=" + _sid + '&host=' + filter_host + '&facility=' + filter_facility + '&Ffacility=' + filter_Ffacility + '&severity=' + filter_severity + '&Fseverity=' + filter_Fseverity + '&program=' + filter_program;
			var addrXSL = "./modules/centreon-syslog/include/monitoring/xsl/syslog.xsl";

			proc.setXml(addrXML);
			proc.setXslt(addrXSL);
			proc.transform("ajaxLog");
			_lock = 0;
		}

		_mySyslogTimeOut = setTimeout('build_ajax()', _mySyslogtime_reload);
	}

	function build_ajaxFilters() {
		_lockFilter = 1;
		var proc = new Transformation();

		if (document.getElementById('filter_host')) {
			var filter_host = document.getElementById('filter_host').options[document.getElementById('filter_host').selectedIndex].value;
			var filter_facility = document.getElementById('filter_facility').options[document.getElementById('filter_facility').selectedIndex].value;
			var filter_Ffacility = document.getElementById('filter_Ffacility').options[document.getElementById('filter_Ffacility').selectedIndex].value;
			var filter_severity = document.getElementById('filter_severity').options[document.getElementById('filter_severity').selectedIndex].value;
			var filter_Fseverity = document.getElementById('filter_Fseverity').options[document.getElementById('filter_Fseverity').selectedIndex].value;
			var filter_program = escape(document.getElementById('filter_program').options[document.getElementById('filter_program').selectedIndex].value);
		}

		var addrXML = "./modules/centreon-syslog/include/monitoring/xml/syslog_filters_xml.php?sid=" + _sid + '&host=' + filter_host + '&facility=' + filter_facility + '&Ffacility=' + filter_Ffacility + '&severity=' + filter_severity + '&Fseverity=' + filter_Fseverity + '&program=' + filter_program;
		var addrXSL = "./modules/centreon-syslog/include/monitoring/xsl/syslog_filters.xsl";

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