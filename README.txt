
SEBSOFT / MOODULES ANTIHAMMER PLUGIN

The Sebsoft / Moodules Anti Hammering Authentication Plugin offers you the possibility to
prevent hammering your login system.
This plugin can be configured to "smart detect" so called hammering in IP basis or for users in general.
Hammering is the process of pretty much brute force attacking Noodle's login system.
This plugin detects the IP address of the remote client, and will track the entered username (and, if the
username exists, also the Moodle userid) and stores it's information to block the user and/or IP address
depending on the configuration of your authentication plugin.

When the plugin has been installed, you should enable or disable blicking by IP and/or username and
configure the timespan at which detection is valid and number of times an attempt can be made.

Important note:
This plugin does not neccessarily prevent brute force hacking when IP detection is not configured.
When the only checks are done based on the username, and an attacker user a different username on virtually
every request (dictionary hacking), a lot of log/status records will be created, but this plugin can't
really do anything (simple because the username is differing too often). In that case IP blocking might help.

Please note this authentication plugin has an accompanying block to view the logs and status tables.
This block is available under the name "antihammerstats" and allows you to remove status and/log records.

INSTALLATION

- Copy the antihammer folder to your auth directory.
- Configure your authentication plugin.
- We're ready to run!
