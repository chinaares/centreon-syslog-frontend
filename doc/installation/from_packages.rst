==============
Using packages
==============

Merethis provides RPM for its products through Centreon Entreprise Server (CES). Open source products are available for free from our
repository.

These packages have been successfully tested with CentOS and RedHat 5.x / 6.x.

*************
Prerequisites
*************

In order to use RPM from the CES repository, you have to install the appropriate repo file. 

CES 2.2
-------

Run the following command as privileged user::

  $ wget http://yum.centreon.com/standard/2.2/ces-standard.repo -O /etc/yum.repos.d/ces-standard.repo

The repo file is now installed.

CES 3.0
-------

Run the following command as privileged user::

  $ wget http://yum.centreon.com/standard/3.0/stable/ces-standard.repo -O /etc/yum.repos.d/ces-standard.repo

The repo file is now installed.

************
Installation
************

Use following documentation to install the module

.. toctree::

   rpm_server/index

