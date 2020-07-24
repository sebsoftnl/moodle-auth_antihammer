
SEBSOFT ANTIHAMMER PLUGIN

The Sebsoft Anti Hammering Authentication Plugin offers you the possibility to prevent hammering your login system.
This plugin can be configured to "smart detect" so called hammering on IP basis or for users in general.
Hammering is the process of pretty much brute force attacking Moodle's login system.
This plugin detects the IP address of the remote client, and will track the entered username (and, if the
username exists, also the Moodle userid) and stores it's information to block the user and/or IP address
depending on the configuration of your authentication plugin.

When the plugin has been installed, you should enable or disable blocking by IP and/or username and
configure the timespan at which detection is valid and number of times an attempt can be made.

This plugin can also be configured to make use of the messaging API in moodle.
This is a specific setting that needs to be enabled; if not configured the messaging API will not be used.
Please note receiving messages is not configured for everybody by default. Every applicable person (usually
administrators) MUST configure their preferences if they'd like to receive these messages.

Moodle's lockout system vs Antihammer:
Moodle already has the capability to (temporarily) lock out users https://docs.moodle.org/30/en/Site_policies#Account_lockout)

However, this plugin will add to that functionality, enabling to also take a look at specific IP usage of users trying to login.
There is *no* interaction with the lock out users system of Moodle.

If you want to be able to use the default method of Moodle account locking, but want to use
this plugin for the additional functions of being able to block hammering/testing of passwords
from a certain IP, you need to enable the IP Settings of the antihammer plugin.
You *need* to keep the User mode/setting disabled if you wish to keep Moodle's standard account lockout.

Furthermore this function differs from the Moodle implementation as Moodle will also allow
you to configure if you want to send an e-mail with a unlock link.
The Antihammer authentication method does not do this, as it's more of a way to
provide additional security and possibly block attacks with admin notification.

*Warning*: Whatever you do, do *never* enable both the user mode in Antihammer
AND the account lockout feature together, this may/will cause unintended side effects.

Important note:
This plugin does not neccessarily prevent brute force hacking when IP detection is not configured.
When the only checks are done based on the username, and an attacker uses a different username on virtually
every request (dictionary hacking), a lot of log/status records will be created, but this plugin can't
really do anything (simple because the username is differing too often). In that case IP blocking might help.

Please note this authentication plugin creates administration menu items to view the logs and status tables.

INSTALLATION

- Copy the antihammer folder to your auth directory.
- Configure your authentication plugin.
- We're ready to run!
