# BLOCK Ludifica #

A block to implement a gamification strategy in site level.

Package tested in: 3.11+, 4.0+, 4.1+

![Block preview](pix/preview.png)

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/blocks/ludifica

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## ABOUT ##
- **Developed by:** David Herney - david dot bernal at bambuco dot co
- **GIT:** https://github.com/bambuco/moodle-block_ludifica
- **Documentation:** https://bambuco.co/ludifica/
- **Powered by:** BambuCo

## IN VERSION ##
2021031212:
- Multitenan support

2021031211:
- New default and eliza template

2021031208:
Improve criteria to assign badges:
- Implemented "N courses completed" criteria.

2021031207:
- New point allocation criteria: valid / invalid mail change

2021031206:
- Change visualization in not own profile

2021031205:
- New feature: configurable templates

2021031204:
- New points type: new user

2021031203:
- Contextualized help

2021031200:
- First version

## License ##

2023 David Herney @ BambuCo

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
