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

	/**
	 * Determin if an IP address is valid or not
	 * @param  string  $ip  IP address to test
	 * @return bool  true = valid, false= not valid
	 */
  	function is_ip_address($ip) {
    	if(is_string($ip) && ereg('^([0-9]{1,3})\.([0-9]{1,3})\.' .
                               '([0-9]{1,3})\.([0-9]{1,3})$',
                               $ip, $part)) {
      	if($part[1] <= 255 && $part[2] <= 255 &&
          	$part[3] <= 255 && $part[4] <= 255)
        	return true;
    	}
    	return false;
 	}

	/**
	 * Determin if an IP address is valid on INTERNET or not
	 *
	 * @param  string  $ip  IP address to test
	 * @return bool  true = valid, false= not valid
	 */
	function valid_internet_ip($ip) {
		if(!empty($ip) && ip2long($ip)!=-1) {
			$reserved_ip = array (
			array('0.0.0.0','2.255.255.255'),
			array('10.0.0.0','10.255.255.255'),
			array('127.0.0.0','127.255.255.255'),
			array('169.254.0.0','169.254.255.255'),
			array('172.16.0.0','172.31.255.255'),
			array('192.0.2.0','192.0.2.255'),
			array('192.168.0.0','192.168.255.255'),
			array('255.255.255.0','255.255.255.255')
			);
	  
			foreach ($reserved_ip as $r)
			{
				$min = ip2long($r[0]);
	          	$max = ip2long($r[1]);
	          	if((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) 
	          		return false;
			}
	      	return true;
		}
		else return false;
	}

	/**
	 * Determin if a string is a FQDN
	 *
	 * @param  string  $host       Host to test
	 *         bool    $force_dot  Force do in the address
	 * @return bool  true = valid, false= not valid
	 */
	function check_fqdn($host, $force_dot=false) {
    	if(!ereg("^\[?[0-9\.]+\]?$", $host)) { // Check if domain is IP
	      	$fqdn = explode(".", $host);
	      	if(count($fqdn) < 2) {
	        	return false;
	      	}
	      	
	      	for ($i = 0; $i < count($domain_array); $i++) {
	        	if(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $fqdn[$i])) {
	          		return false;
	        	}
	      	}
	      	return true;
		}
		return false;
	}

	function getMachineNameFromIP($ip) {
		$hostname = getDNSFromIP($ip);
		
		$pattern = '/(?<name>\w+).(?<name>\w+).(?<name>\w+)/';
		
		if(preg_match($pattern, $hostname, $matches, PREG_OFFSET_CAPTURE, 3)) {
			echo "is DNS: ".$matches[0];
		} else {
			echo "not DNS: ".$matches[0];
		}
		
	}
	
	function getIPFromDNS($dns) {
		return gethostbyname($dns);
	}
	
	function getDNSFromIP($ip) {
		return gethostbyaddr($ip);
	}
?>

