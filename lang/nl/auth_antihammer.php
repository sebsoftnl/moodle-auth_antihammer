<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * De-generated language file
 *
 * File         : auth_antihammer.php
 * Encoding     : UTF-8
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * 
 **/
$string['auth_antihammerdescription'] = 'Gebruikers kunnen niet inloggen. Deze authenticatiemodule doet dienst als een blokkeringssysteem voor logins';
$string['pluginname'] = 'antihammer Anti-hammering / Login blocker';
$string['antihammer'] = 'antihammer Anti-hammering / Login blocker';
$string['promodesc'] = 'Deze plugin is ontwikkeld door Sebsoft Managed Hosting & Software Development
    (<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://www.sebsoft.nl</a>).<br /><br />
    {$a}<br /><br />';
$string['auth_antihammer'] = 'antihammer Anti-hammering / Login blocker';
$string['attemptcounter'] = 'Tijdspanne login pogingen';
$string['attempts'] = 'Maximum aantal login pogingen';
$string['autoclear_after'] = 'Tijdspanne voor automatisch opheffen (seconden)';
$string['autoclear_blocked'] = 'Automatisch opheffen blokkade?';
$string['blockip'] = 'Blokkeren op basis van IP adres';
$string['blocklangcode'] = 'Taal identifier die wordt gebruikt om een bericht te tonen wanneer blokkade voorkomt';
$string['blockpage'] = 'Pagina die wordt getoond wanneer een blokkade voorkomt';
$string['blockusername'] = 'Blokkeren op basis van gebruikersnaam?';
$string['notificationemail'] = 'Notificatie email voor blokkades';
$string['notificationemail_fname'] = 'Notificatie ontvanger voornaam';
$string['notificationemail_lname'] = 'Notificatie ontvanger achternaam';
$string['str:blocked:page'] = 'Je account en/of IP adres is geblokkeerd.';
$string['log:info:blocked'] = 'Geblokkeerd: {$a}';
$string['mail:blocking:subject'] = 'Login Anti-hammering: gebruiker of IP adres geblokkeerd';
$string['mail:blocking:message'] = '<p>Beste {$a->firstname} {$a->lastname}</p>
<p>Er is een blokkade in werking gesteld ten gevolge van teveel login pogingen</p>
<p>Het gaat om IP adres {$a->ip}, gebruikersnaam {$a->username}</p>
<p>Met vriendelijke groet,<br/>Support systeem</p>';