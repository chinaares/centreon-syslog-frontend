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

function ajax_generate()
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
			alert_ajax(xhr);
		}
	}
	
	xhr.open('GET', "./modules/Syslog/include/export/ssh2/ssh2_export.php", true);
	xhr.send(null);
}

function alert_ajax(xhr)
{
	var docXML= xhr.responseXML;
	var items = docXML.getElementsByTagName("status");
	
	alert (items.item(0).firstChild.data);
}
