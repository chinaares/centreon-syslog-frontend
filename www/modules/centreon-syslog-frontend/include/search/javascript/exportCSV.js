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

function ajax_generateCSV_XML(value)
{
	var xhr=null;
	
	if (window.XMLHttpRequest) { 
		xhr = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) 
	{
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xhr.onreadystatechange = function() 
	{
		if (xhr.readyState <= 3)
		{
			if (!document.getElementById("centreonMsg_img"))
			{
				_setAlign("centreonMsg", "center");
				_setTextStyle("centreonMsg", "bold");
				_setImage("centreonMsg", "./img/misc/ajax-loader.gif");
				_setText("centreonMsg", " Loading...");
				_setValign("centreonMsg", "bottom");
			}
		} 
		else if (xhr.readyState == 4 && xhr.status == 200)
		{
			_clear("centreonMsg");
		}
	}

	var collector = document.forms['Formfilter'].elements['collectors'].value;
	var filter_host = document.forms['Formfilter'].elements['filter_host'].value;
	var filter_facility = document.forms['Formfilter'].elements['filter_facility'].value;
	var filter_Ffacility = document.forms['Formfilter'].elements['filter_Ffacility'].value;
	var filter_severity = document.forms['Formfilter'].elements['filter_severity'].value;
	var filter_Fseverity = document.forms['Formfilter'].elements['filter_Fseverity'].value;
	var filter_program = escape(document.forms['Formfilter'].elements['filter_program'].value);
	var start_date = document.getElementById('StartDate').value;
	var start_time = document.getElementById('StartTime').value;
	var end_date = document.getElementById('EndDate').value;
	var end_time = document.getElementById('EndTime').value;

	var _addr = "./modules/centreon-syslog-frontend/include/export/csv/csv_export.php?type=" + value + '&collector=' + collector + '&host=' + filter_host + '&facility=' + filter_facility + '&Ffacility=' + filter_Ffacility + '&severity=' + filter_severity + '&Fseverity=' + filter_Fseverity + '&program=' + filter_program + '&start_date=' + start_date + '&start_time=' + start_time + '&end_date=' + end_date + '&end_time=' + end_time
	window.open(_addr);
}

function ajax_generateODT(value)
{
	var xhr=null;
	
	if (window.XMLHttpRequest) { 
		xhr = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) 
	{
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xhr.onreadystatechange = function() 
	{
		if (xhr.readyState <= 3)
		{
			if (!document.getElementById("centreonMsg_img"))
			{
				_setAlign("centreonMsg", "center");
				_setTextStyle("centreonMsg", "bold");
				_setImage("centreonMsg", "./img/misc/ajax-loader.gif");
				_setText("centreonMsg", " Loading...");
				_setValign("centreonMsg", "bottom");
			}
		} 
		else if (xhr.readyState == 4 && xhr.status == 200)
		{
			_clear("centreonMsg");
		}
	}

	var collector = document.forms['Formfilter'].elements['collectors'].value;
	var filter_host = document.forms['Formfilter'].elements['filter_host'].value;
	var filter_facility = document.forms['Formfilter'].elements['filter_facility'].value;
	var filter_Ffacility = document.forms['Formfilter'].elements['filter_Ffacility'].value;
	var filter_severity = document.forms['Formfilter'].elements['filter_severity'].value;
	var filter_Fseverity = document.forms['Formfilter'].elements['filter_Fseverity'].value;
	var filter_program = escape(document.forms['Formfilter'].elements['filter_program'].value);
	var start_date = document.getElementById('StartDate').value;
	var start_time = document.getElementById('StartTime').value;
	var end_date = document.getElementById('EndDate').value;
	var end_time = document.getElementById('EndTime').value;

	var _addr = "./modules/centreon-syslog-frontend/include/export/odtPHP/odtPHP_export.php?type=" + value + '&collector=' + collector + '&host=' + filter_host + '&facility=' + filter_facility + '&Ffacility=' + filter_Ffacility + '&severity=' + filter_severity + '&Fseverity=' + filter_Fseverity + '&program=' + filter_program + '&start_date=' + start_date + '&start_time=' + start_time + '&end_date=' + end_date + '&end_time=' + end_time
	window.open(_addr);
}