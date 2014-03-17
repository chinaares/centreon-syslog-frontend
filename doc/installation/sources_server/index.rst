========================
Centreon Syslog Frontend
========================

The centreon Syslog Frontend is the graphical user interface for Centreon main server.

.. warning:: If one of the following action failed the installation failed too. You must correct failed before restart installation process.

Extract sources
===============

Copy the tarball into '/tmp' directory and run the following commands::

  $ tar xzf centreon-syslog-frontend-1.5.0.tar.gz
  $ cd centreon-syslog-frontend-1.5.0
  $ dos2unix install.sh libinstall/*

Install module
==============

Start installation using following command::

  $ bash install.sh -u /etc/centreon
  
  ################################################################################
  #                                                                              #
  #                 Thanks for using Centreon Syslog Frontend                    #
  #                                  v 1.5.0                                     #
  #                                                                              #
  ################################################################################
  ------------------------------------------------------------------------
          Checking all needed binaries
  ------------------------------------------------------------------------
  rm                                                         OK
  cp                                                         OK
  mv                                                         OK
  /bin/chmod                                                 OK
  /bin/chown                                                 OK
  echo                                                       OK
  more                                                       OK
  mkdir                                                      OK
  find                                                       OK
  /bin/grep                                                  OK
  /bin/cat                                                   OK
  /bin/sed                                                   OK
  Parameters was loaded with success                         OK
  
  ------------------------------------------------------------------------
          Update Module Name
  ------------------------------------------------------------------------
  Update module name "Syslog" to "centreon-syslog":          OK
  
  ------------------------------------------------------------------------
          Checking php extension
  ------------------------------------------------------------------------
  SSH2 extension for PHP:                                    OK
  XML-Writer extension for PHP:                              OK
  
  ------------------------------------------------------------------------
          Install Centreon Syslog Frontend web interface
  ------------------------------------------------------------------------
  Changing macros                                            OK
  Setting right                                              OK
  Setting owner/group                                        OK
  Delete old install module                                  OK
  Copying module                                             OK

  ------------------------------------------------------------------------
          End of Centreon Syslog Frontend installation
  ------------------------------------------------------------------------
  Installation of Centreon Syslog Frontend is finished       OK
  See README and the log file for more details.
  
  ################################################################################
  #                                                                              #
  #       Go to the URL : http://your-server/centreon/ to finish the setup       #
  #                                                                              #
  #       Report bugs at                                                         #
  #          http://forge.centreon.com/projects/show/centreon-syslog             #
  #                                                                              #
  ################################################################################

Your module is installed

Notice: replace "/etc/centreon" directory by the "etc" directy of Centreon of your 
installation. The installation will use parameters of Centreon installation to 
install this module.

.. note:: To finish installation, see :ref:`webinstall-label`
