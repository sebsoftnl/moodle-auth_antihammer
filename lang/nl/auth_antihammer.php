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
 * Language file for auth_antihammer, NL
 *
 * File         auth_antihammer.php
 * Encoding     UTF-8
 *
 * @package     auth_antihammer
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
$string['auth_antihammerdescription'] = 'Gebruikers kunnen niet inloggen. Deze authenticatiemodule doet dienst als een blokkeringssysteem voor logins';
$string['pluginname'] = 'Anti-hammering / Login blocker';
$string['promo'] = 'Anti-hammering / loginblocker authenticatie plugin voor Moodle';
$string['promodesc'] = 'Deze plugin is ontwikkeld door Sebsoft Managed Hosting & Software Development
    (<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://www.sebsoft.nl</a>).<br /><br />
    {$a}<br /><br />';
$string['auth_antihammer'] = 'antihammer Anti-hammering / Login blocker';
$string['ap:log'] = 'Antihammer logs';
$string['title:report:logs'] = 'Antihammer logs';
$string['ap:report'] = 'Antihammer rapportage';
$string['ap:logdetails'] = 'Antihammer log details';
$string['title:report:hammer'] = 'Antihammer rapportage';
$string['mail:blocking:subject'] = 'Login Anti-hammering: gebruiker of IP adres geblokkeerd';
$string['mail:blocking:message'] = '<p>Beste {$a->firstname} {$a->lastname}</p>
<p>Er is een blokkade in werking gesteld ten gevolge van teveel login pogingen</p>
<p>Het gaat om IP adres {$a->ip}, gebruikersnaam {$a->username}</p>
<p>Met vriendelijke groet,<br/>Support systeem</p>';
$string['thead:action'] = 'actie';
$string['thead:userid'] = 'userid';
$string['thead:module'] = 'module';
$string['thead:type'] = 'type';
$string['thead:msg'] = 'bericht';
$string['thead:code'] = 'code';
$string['thead:datecreated'] = 'datum aangemaakt';
$string['type:ip'] = 'IP blokkade';
$string['type:user'] = 'Gebruikersblokkade';
$string['type:info'] = 'Informatie';
$string['action:delete:logitem'] = 'Verwijder log item';
$string['action:delete:hammeritem'] = 'Verwijder hammering item';
$string['action:view:logitem'] = 'Log details bekijken';
$string['action:confirm-delete-log'] = 'Verwijder log item';
$string['action:confirm-delete-hammer'] = 'Verwijder hammering item';
$string['thead:username'] = 'gebruikersnaam';
$string['thead:ip'] = 'IP';
$string['thead:count'] = 'detectieteller';
$string['thead:firstattempt'] = 'eerste poging';
$string['thead:blocked'] = 'geblokkeerd';
$string['thead:blocktime'] = 'tijdstip blokkade';
$string['antihammer:delete'] = 'Records verwijderen';
$string['antihammer:administration'] = 'Auth antihammer beheren';
$string['err:sqltable:set_sql'] = 'set_sql() is onbruikbaar. Deze tabel creert zijn eigen SQL en is niet aanpasbaar.';
$string['auth_antihammer_ipsettings'] = 'IP blokkade instellingen';
$string['auth_antihammer_ipsettings_desc'] = '';
$string['blockip'] = 'Blokkeren op basis van IP adres';
$string['ip_attemptcounter'] = 'Tijdspanne login pogingen voor IP blokkade';
$string['ip_attempts'] = 'Maximum aantal login pogingen voor IP blokkade';
$string['autoclear_ipblock'] = 'Automatisch opheffen IP blokkade?';
$string['autoclear_ipblock_after'] = 'Tijdspanne voor automatisch opheffen IP blokkade';
$string['auth_antihammer_usersettings'] = 'Instellingen gebruikersblokkade';
$string['auth_antihammer_usersettings_desc'] = '';
$string['blockusername'] = 'Blokkeren op basis van gebruikersnaam?';
$string['attempts'] = 'Maximum aantal login pogingen';
$string['attemptcounter'] = 'Tijdspanne login pogingen';
$string['autoclear_userblock'] = 'Automatisch opheffen gebruikersblokkade?';
$string['autoclear_userblock_after'] = 'Tijdspanne voor automatisch opheffen gebruikersblokkade';
$string['auth_antihammer_messagesettings'] = 'Berichten / notificatie instellingen';
$string['auth_antihammer_messagesettings_desc'] = '';
$string['usemessaging'] = 'Berichten API gebruiken?';
$string['usemessaging_desc'] = 'Vink deze optie aan als je van de moodle berichten API gebruik wilt maken om personen te informeren';
$string['notifymainadmin'] = 'Altijd standaard beheerder inlichten?';
$string['notifymainadmin_desc'] = 'Vink deze optie aan als je altijd de standaard Moodle beheerder per e-mail wilt informeren.<br/>
NOTE: De standaard Moodle beheerder <i>kan</i> two notificaties ontvangen als bovenstaande optie is aangevinkt, en de beheerder ook
de option om notificaties per email te ontvangen heeft geconfigureerd!';
$string['auth_antihammer_miscsettings'] = 'Overige instellingen';
$string['auth_antihammer_miscsettings_desc'] = '';
$string['blockpage'] = 'Pagina die wordt getoond wanneer een blokkade voorkomt';
$string['autocleanlog'] = 'Automatisch logs opschonen?';
$string['autocleanlog_after'] = 'Automatisch logs opschonen wanneer ouder dan';
$string['str:blocked:page'] = 'Je account en/of IP adres is geblokkeerd.';
$string['log:info:blocked'] = 'Geblokkeerd: {$a}';
$string['messageprovider:antihammerblocking'] = 'Notificatie tbv IP/account blokkade';
$string['auth_antihammer_ipblocksettings'] = 'IP blokkade toevoegen aan site configuratie?';
$string['auth_antihammer_ipblocksettings_desc'] = 'WAARSCHUWING! Je zult deze optie nagenoeg nooit willen gebruiken.<br/>
Je loopt het risico om jezelf compleet buiten te sluiten van de site, speciaal wanneer je een netwerk gebruikt waar veel mensen hetzelfde IP adres delen.<br/>
Als, om welke reden dan ook, je echt deze optie wilt gebruiken, stel dan minstens 1 IP adres in dat specifiek toegestaan is, en zet de globale optie om de
"allow" lijst te verwerken VOOR de blokkadelijst. Als je dit niet doet loop je een HOOD risico om ook beheerders uit te sluiten!.';
$string['addcfgipblock'] = 'Toevoegen IP blokkade aan site configuratie';
$string['addcfgipblock_desc'] = 'Vink deze optie aan als je een IP blokkade tevens wilt toevoegen aan de systeemlijst met <a href="{$a}">geblokkeerde IP addressen</a>.';
$string['task:logcleaner'] = 'Antihammer logs opschonen';
$string['config:autocleanlog:disabled'] = 'Automatisch opschonen van antihammer logs is uitgezet in de globale configuratie';
$string['err:blocked:ip'] = 'Hammering gedetecteerd: IP adres = {$a->ip} (IP is geblokkeerd)';
$string['err:blocked:user'] = 'Hammering gedetecteerd: Gebruikersnaam = {$a->username} IP adres = {$a->ip} (IP en/of gebruikersnaam is geblokkeerd)';

$string['privacy:metadata:auth_antihammer:userid'] = 'De primaire database sleutel van de Moodle gebruiker voor wie "hammering" tijdelijk is/wordt gelogd.';
$string['privacy:metadata:auth_antihammer:username'] = 'De gebruikersnaam van de Moodle gebruiker voor wie "hammering" tijdelijk is gelogd.';
$string['privacy:metadata:auth_antihammer:ip'] = 'IP adres van de Moodle gebruiker voor wie "hammering" tijdelijk is gelogd.';
$string['privacy:metadata:auth_antihammer:blocked'] = 'Indicatie of de gedetecteerde Moodle gebruiker tijdelijk is geblokkeerd.';
$string['privacy:metadata:auth_antihammer:firstattempt'] = 'Tijdstip waarop de gegevens zijn aangemaakt.';
$string['privacy:metadata:auth_antihammer:blocktime'] = 'Tijdstip tot wanneer de gebruiker is geblokkeerd.';
$string['privacy:metadata:auth_antihammer_log:userid'] = 'De primaire database sleutel van de Moodle gebruiker voor wie "hammering" tijdelijk is gelogd.';
$string['privacy:metadata:auth_antihammer_log:data'] = 'Geserialiseerde informatie voor de Moodlle gebruiker, welke onder andere de gedetecteerde gebruikersnaam en het IP adres bevat.';
$string['privacy:metadata:auth_antihammer_log:datecreated'] = 'Tijdstip waarop de gegevens zijn aangemaakt.';
$string['privacy:metadata:auth_antihammer'] = 'Authenticatiemethode antihammer slaat gegevens op gerelateerd aan (hammering) login pogingen voor gebruikers';
$string['privacy:metadata:auth_antihammer_log'] = 'Authenticatiemethode antihammer log slaat historische/log gegevens op gerelateerd aan (hammering) login pogingen voor gebruikers';

$string['delete:all'] = 'Alle rijen verwijderen';
$string['plugin:notenabled'] = 'Antihammering plugin is geinstalleerd maar nog niet geactiveerd.<br/>
Dit betekent dat deze plugin op dit moment niet werkt.<br/>
Indien je gebruik wenst te maken van de functionaliteit van deze plugin, schakel deze dan in via de lijst met authenticatieplugins via sitebeheer.
';
$string['action:delete:ipblock'] = 'IP blokkade verwijderen';
$string['lookup:whatismyip'] = 'Opzoeken op whatismyipaddress.com';
$string['lookup:iplookup'] = 'IP Lookup (moodle)';
$string['title:report:repeatoffenders'] = 'Antihammer recidivisten';
$string['ap:ro'] = 'Recidivisten';
$string['action:delete:roitem'] = 'Verwijder dit recidivisten IP adres item';
$string['thead:blockcounter'] = 'Blokkadeteller';
$string['thead:nextblockduration'] = 'Volgende blokkadeduur';
$string['plugin:getremoteaddrconf:notification'] = 'De core instellingen die bepalen hoe een extern IP adres wordt bepaald kan problemen opleveren.<br/>
De HTTP Header configuratie kijkt niet naar HTTP_CLIENT_IP, HTTP_X_FORWARDED_FOR of beiden.<br/>
Indien je server achter een reverse proxy staat zou dit kunnen betekenen dat het verkeerde IP adres wordt gelogd.<br/>
Om dit op te lossen kun je de instelling aanpassen in voor "getremoteaddrconfig" in de <a href="/admin/settings.php?section=http" target="_new">HTTP instellingen</a>';
$string['ipwhitelist'] = 'IP Whitelist';
$string['ipwhitelist_desc'] = 'Je kunt hier een whitelist voor IP addressen opgeven die <i>niet</i> geblokkeerd worden.<br/>
Geef elk IP adres in op een <i>nieuwe regel</i>.<br/>
Deze IP adressen worden niet meegenomen in de blokkadelijsten van antihammer.<br/>We ondersteunen voor nu nog <i>geen</i> wildcards.<br/>
Dit is met name handig als er via een intern netwerk met Moodle gewerkt wordt waar vele computers hetzelfde IP adres gebruiken, bijvoorbeeld op scholen en universiteiten.
';
$string['ip:whitelist'] = 'IP adres toevoegen aan whitelist';
$string['thead:whitelisted'] = 'Staat op whitelist?';
$string['warn:moodlelockoutactive'] = 'Moodle <a href="{$a}">account lockout</a> is actief naast antihammer\'s instelling.<br/>
Dit kan ongewenste neveneffecten hebben (zoals full lockout). Overweeg aub een van beiden te deactiveren.';
$string['antihammer:notifyblocking'] = 'Notificaties ontvangen ten behoeve van account blokkades.';
