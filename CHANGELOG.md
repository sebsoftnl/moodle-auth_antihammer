Version 3.7.2 (build 2025120100)
* Fix loads of CI errors
* Better code compliance
* DISABLE code checks in privacy provider (as Moodle is buggy in the alphabetical order checks).
* We no longer prefer IP blocks before user blocks. They will both be processed without precedence.
  Effectively, this will put both an IP block AND a user block (when applicable).
* Added user based repeat offenders (before we only did IP based).

----------
Version 3.7.1 (build 2023011600)
* Fixed repeat offenders from settings.php (gave a section error).

----------
Version 3.7.0 (build 2022090100)
* Removed all MOODLE_INTERNAL checks from singular classes as per MDLSITE-5967.
* Fix several CI issues
* Minimum required Moodle version: 3.10

----------
Version 3.6.3 (build 2020121603)
* Runner now working more correct again; fixing heaps of coding standards issues.

----------
Version 3.6.2 (build 2020121602)
* Fixed 2 coding standards issues.
# Resolved #1 (missing notifyblocking string).
# Validated working for Moodle 4.0

----------
Version 3.6.1 (build 2020121601)
* Added warning message for when account lockout in Moodle and antihammer are both enabled.

----------
Version 3.6.0 (build 2020121600)
* Added more actions in tables, especially related to looking up IP addresses.
* Added option to remove a complete IP block.
* Verified working on Moodle 3.10
* Replaced most internal images for Moodle images, except for the hammer.
* Added notification/check related to this plugin not being enabled.
* Added repeat offenders functionality (BETA functionality!)
* Added IP whitelist

----------

Version 3.5.3 (build 2020070100)
* Added "courseid" to message(s) to prevent debugging output.
* Added option to remove all "current block records"
* Renamed .txt files to .md (readme, changelog)
* Added LICENSE.md
* Verified working on Moodle 3.9

----------

Version 3.5.1 (build 2018050301)
* Updated privacy provider.
----------

Version 3.5.0 (build 2018050300)
* Added privacy API.
----------

Version 3.3.0 (build 2017092501)
* Fixed bug as reported by Rekha C
* Version bump
----------

Version 3.3.0 (build 2017092500)
* Fixed deprecated pix_url references (replaced by image_url)
* Validated functionality for Moodle 3.3 onwards
* Minimum required Moodle version: 3.3
----------

Version 3.0.0 (build 2017050100)
* Code overhaul to comply to Moodle standards
* Validated for Moodle 3.0 onwards
