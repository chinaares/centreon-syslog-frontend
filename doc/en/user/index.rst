.. _exploitation-label:

###########
User manual
###########

This chapter describes how to use this module

Real time monitoring
====================

Connect to Centreon GUI on main server and go to the menu "Monitoring -> Syslog -> Monitoring". 
First you have to select the collector (Centreon Syslog Server) defined in :ref:`configuration-label`:

.. image:: /_static/images/user/centreon_rt_1.png
   :align: center

.. note:: Only the last 50 events are shown. This page is updated every 15 seconds. You can change the interval of refreshment from general :ref:`configuration-label`:

During the first access to the page of "monitoring", all the filters are not selected by default.
The display thus presents the last fifty elements stored into database. Then in regular interval the page is automatically refreshed.

It is possible to filter the display following the criteria below:

=========== =============================================
Name        Description
=========== =============================================
Collectors  Name of the Centreon Syslog Server collector

Host        DNS or IP address who generated the event

Facility    Facility of the event

Severity    Severity of the event

Program     Name of program which generated the event

Message     Description of the event
=========== =============================================

Here a example of events filtered by severity more important or equal to 'warning':

.. image:: /_static/images/user/centreon_rt_2.png
   :align: center

You can use different filters in same time. If any events are shown it means that any event matches with your criteria.
The 'facility' and 'severity' filters can be used with mathematical operator. It is possible to select all the events "inferiors or equals" to severity "warn".
In this case, will be selected the events corresponding to the severities: emerg, panic, alert, critic, to error, err, warning and warn.

Each severity and facility is linked to a number defined in :ref:`appendices-label`.

.. note:: You can stop the refresh of the page using the "stop" button and restart the refresh using the "start" button.

Search and extract past events
==============================

Connect to Centreon GUI on main server and go to the menu "Monitoring -> Syslog -> Search".
First you have to select the collector (Centreon Syslog Server) defined in :ref:`configuration-label`:

.. image:: /_static/images/user/centreon_search_1.png
   :align: center

Having selected the collector, the events of last hour are shown:

.. image:: /_static/images/user/centreon_search_2.png
   :align: center

It is possible to filter the display following the criteria below:

=========== =============================================
Name        Description
=========== =============================================
Collectors  Name of the Centreon Syslog Server collector

From        Date and time of the beginning of search

To          Date and time of the end of search

Host        DNS or IP address who generated the event

Facility    Facility of the event

Severity    Severity of the event

Program     Name of program which generated the event

Message     Description of the event
=========== =============================================

.. note:: The use of "message" filter is based on MySQL LIKE type search.

Once the criteria of search selected, click the "filter" button to launch the search.

.. note:: It is possible that the search takes one certain time. Indeed, this one is realized on the totality of the recording in database on selected period.

Here is an example of events filtered by facility equal to 'syslog':

.. image:: /_static/images/user/centreon_search_3.png
   :align: center

You can export the result to CSV, XML or ODT file using the following icons:

.. image:: /_static/images/user/centreon_search_4.png
   :align: center

Appendices
==========

Type of errors
--------------

If you have the following message, it means that parameters to access to database on Centreon Syslog Server are incorrect:

.. image:: /_static/images/user/centreon_error_1.png
   :align: center

Modify your :ref:`configuration-label`:

If you have the following message, it means that you have a problem with merge cache table on Centreon Syslog Server

.. image:: /_static/images/user/centreon_error_2.png
   :align: center

Connect to your distant MySQL database and repear merge cache table.

The following message can be a problem. It can mean that that the insertion in database is stopped:

.. image:: /_static/images/user/centreon_error_3.png
   :align: center

Check Syslog daemon paramters using documentation of Centreon Syslog Server.

.. _appendices-label:

Severities and facilities correspondences
-----------------------------------------

Facility
~~~~~~~~

============== ======
Name           Value
============== ======
emerg, panic   0

alert          1

crit           2

error,err      3

warning, warn  4

notice         5

info           6

debug          7
============== ======

Severity
~~~~~~~~

========= ======
Name      Value
========= ======
kern      0

user      1

mail      2

daemon    3

auth      4

severity  5

syslog    6

lpr       7

news      8

uucp      9

cron      10

authpriv  11

ftp       12

local0    16

local1    17

local2    18

local3    19

local4    20

local5    21

local6    22

local7    23
========= ======

