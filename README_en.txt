#	Description:

Name: Centreon-Syslog-Frontend
Version: 1.5.x

#	Installation:

Installation is detailed on documentation available here: http://forge.centreon.com/projects/centreon-syslog/documents

1. Get last version of Syslog module here http://forge.centreon.com/projects/list_files/centreon-syslog

2. Start installation with install.sh script on root directory "centreon-syslog-frontend-2.x"

$> chmod +x install.sh
$> ./install.sh -u /etc/centreon

3. Go on Centreon web interface

Go on menu 'Administration > Modules > Setup'.
Syslog module must be present on modules list but not installed.
Click on right icon to start installation

4. Go on menu 'Administration > Modules > Syslog configuration'.

To fill the fields 

5. Go on menu 'Monitoring > Syslog > Monitoring'




#	FAQ

Q: During access on 'Monitoring > Syslog > Syslog reporting' page,
	error message describe 'DB Error: connect failed' or 'DB Error: insufficient permissions'

R: You must fill the fileds correctly or use database syslog user with good right.




#	Links:

	Centreon-Syslog
	
Documentation:	http://forge.centreon.com/projects/centreon-syslog/documents
SVN:	http://syslog.modules.centreon.com/svn
Trac:	http://forge.centreon.com/projects/show/centreon-syslog
Forum:	http://forum.centreon.com/forumdisplay.php?f=26

	Centreon
	
Wiki:	http://doc.centreon.com/
SVN:	http://svn.centreon.com/
Trac:	http://forge.centreon.com/projects/show/centreon
Forum:	http://forum.centreon.com/