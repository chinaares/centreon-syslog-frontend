========================
Centreon Syslog Frontend 
========================

The centreon Syslog Frontend is the graphical user interface for Centreon main server.

Run the following command as privileged user::

   $ yum install centreon-syslog-frontend

YUM suggests the installation of the latest version of the packages::

  ==========================================================================================================
   Package                       Arch        Version               Repository                          Size
  ==========================================================================================================
  Installing:
   centreon-syslog-frontend      noarch      1.5.0-1               ces-standard-noarch                131 k
  Installing for dependencies:
   libssh2                       x86_64      1.2.9-1.el5.rf        rpmforge                           286 k
   ssh2                          x86_64      0.11.0-3              ces-standard-deps                   77 k
  
  Transaction Summary
  ==========================================================================================================
  Install       3 Package(s)
  Upgrade       0 Package(s)
  
  Total download size: 494 k
  Is this ok [y/N]: y

Enter 'y' and press ENTER key to install package on your server.


YUM downloads the package and installs the latter::

  Installed:
  centreon-syslog-frontend.noarch 0:1.5.0-1
  
  Dependency Installed:
    libssh2.x86_64 0:1.2.9-1.el5.rf                          ssh2.x86_64 0:0.11.0-3
  
  Complete!

The package centreon-syslog-frontend is now installed on your server.

.. note:: To conclude installation, see :ref:`webinstall-label`
