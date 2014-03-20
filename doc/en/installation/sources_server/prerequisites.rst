.. _prerequisites-label:

=============================
Installation of prerequisites
=============================

This section describes how to install prerequisites for Centreon Syslog Frontend from sources.

Installation for Debian / Ubuntu
--------------------------------

This section describes the installation of libssh2 for Debian / Ubuntu operating system.

Prerequisites
^^^^^^^^^^^^^

Here is the list of packages to be pre-installed:

* php5-dev
* openssl
* libssl-dev
* gcc
* make

Also, update the packages::

  $ apt-get update
  $ apt-get upgrade

Create the working directory
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Create the working directory::

  $ cd /tmp
  $ mkdir libssh2
  $ cd libssh2

Download the packages::

  $ wget http://www.libssh2.org/download/libssh2-1.2.1.tar.gz
  $ wget http://pecl.php.net/get/ssh2-0.11.0.tgz

Installation of libssh2
^^^^^^^^^^^^^^^^^^^^^^^

Run the following commands::

  $ tar -xzvf libssh2-1.2.1.tar.gz
  $ cd libssh2-1.2.1
  $ ./configure && make all install

The installation of libssh2 is finished.

Installation of ssh2
^^^^^^^^^^^^^^^^^^^^

Run the following commands::

  $ tar -xzvf ssh2-0.11.0.tgz
  $ cd ssh2-0.11.0
  $ phpize && ./configure --with-ssh2 && make

To finish the installation, copy the ssh2.so file to the directory for the PHP extensions. 
This directory can be different depending on your Linux distribution and PHP build::

  $ cp modules/ssh2.so /usr/lib/php5/20060613+lfs

The installation of ssh2 is finished.

.. note:: if your version of PHP is 5.3 you can have a bug, please see http://pecl.php.net/bugs/bug.php?id=16727

Integration of the extension SSH into Apache
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Run the following commands::

  $ echo "extension=ssh2.so" > /etc/php5/cli/conf.d/ssh2.ini
  $ echo "extension=ssh2.so" > /etc/php5/apache2/conf.d/ssh2.ini

Then restart Apache to apply modification::

  $ /etc/init.d/apache2 restart

To check if SSH2 library is correctly installed you can run the following command::

  $ php -i |grep ssh
  Registered PHP Streams => php, file, http, ftp, compress.bzip2, compress.zlib, https, ftps, ssh2.shell, ssh2.exec, ssh2.tunnel, ssh2.scp, ssh2.sftp
  ssh2
  libssh2 version => 1.1
  banner => SSH-2.0-libssh2_1.1

Installation for Redhat / CentOS
--------------------------------

This section describes the installation of libssh2 for Redhat / CentOS operating system.

Prerequisites
^^^^^^^^^^^^^

Here is the list of packages to be pre-installed:

* php5-dev
* php-devel
* openssl
* openssl-devel
* libssl-dev
* gcc
* make

Also, update the packages::

  $ yum update
  $ yum upgrade

Create the working directory
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Create the working directory::

  $ cd /tmp
  $ mkdir libssh2
  $ cd libssh2

Download the packages::

  $ wget http://www.libssh2.org/download/libssh2-1.2.1.tar.gz
  $ wget http://pecl.php.net/get/ssh2-0.11.0.tgz

Installation of libssh2
^^^^^^^^^^^^^^^^^^^^^^^

Run the following commands::

  $ tar -xzvf libssh2-1.2.1.tar.gz
  $ cd libssh2-1.2.1
  $ ./configure && make all install

The installation of libssh2 is finished.

Installation of ssh2
^^^^^^^^^^^^^^^^^^^^

Run the following commands::

  $ tar -xzvf ssh2-0.11.0.tgz
  $ cd ssh2-0.11.0
  $ phpize && ./configure --with-ssh2 && make

To finish the installation, copy the ssh2.so file to the directory for the PHP extensions.
This directory can be different depending on your Linux distribution::

  $ cd modules

For 32 bits operating system::

  $ cp -R ssh2.so /usr/lib/php/modules

For 64 bits operating system::

  $ cp -R ssh2.so /usr/lib64/php/modules

The installation of ssh2 is finished.

Integration of the extension SSH into Apache
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Run the following command::

  $ echo "extension=ssh2.so" >> /etc/php.ini

Then restart Apache to apply modification::

  $ /etc/init.d/httpd restart

To control if SSH2 library is correctly installed you can run the following command::

  $ php -i |grep ssh
  Registered PHP Streams => php, file, http, ftp, compress.bzip2, compress.zlib, https, ftps, ssh2.shell, ssh2.exec, ssh2.tunnel, ssh2.scp, ssh2.sftp
  ssh2
  libssh2 version => 1.1
  banner => SSH-2.0-libssh2_1.1
