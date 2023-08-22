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
 * Strings for component 'block_ludifica', language 'en'
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Ludifica';

// Capabilities.
$string['ludifica:addinstance'] = 'Add a new Ludifica block';
$string['ludifica:myaddinstance'] = 'Add a new Ludifica block to Dashboard';
$string['ludifica:manage'] = 'Manage Ludifica block settings';

$string['privacy:metadata'] = 'The Ludifica block does not store privacy user information.';

$string['allbadges'] = 'View all';
$string['available'] = 'Quantity available';
$string['availabledate'] = 'Available until';
$string['avatar'] = 'Avatar';
$string['avatars'] = 'Avatars';
$string['avatarbust'] = 'Bust';
$string['avatarbuy'] = 'Buy the avatar';
$string['avatarbuymessage'] = 'Do you really want to buy this avatar?';
$string['avatardelete'] = 'Delete Avatar';
$string['avatar_help'] = 'Here you will be able to get an avatar, some of them are free and others can be purchased using the coins earned. To use an avatar you acquired you must click on the Use button, this will identify you in your personal area.';
$string['avatarnew'] = 'New avatar';
$string['avatarnotuse'] = 'Not can use this avatar';
$string['avatarsources'] = 'Sources';
$string['avatarsources_help'] = 'List of image URIs. One by line. The 0 line is for level 0 and equal for the other levels.
Can use the next dynamic tags for the URI: {name}, {level} and {levelname}.';
$string['avatartype'] = 'Type';
$string['avatartype_normal'] = 'Normal';
$string['avatartype_user'] = 'For specific user';
$string['avataruse'] = 'Use the avatar';
$string['avatarused'] = 'Avatar assigned';
$string['avatarusemessage'] = 'Do you really want change the avatar?';
$string['badges'] = 'Badges';
$string['badges_help'] = 'Gain badges and share them on your social networks!';
$string['badge_info'] = 'About the badge';
$string['badge_copy'] = 'Copy link';
$string['badge_expire'] = 'Expiry date: ';
$string['badge_facebook'] = 'Share on Facebook';
$string['badgemanage'] = 'Manage badges';
$string['badgelinkcopiedtoclipboard'] = 'Badge link copied to clipboard';
$string['badge_linkedin'] = 'Share as a badge on Linkedin';
$string['badge_linkedin_bio'] = 'Share as a publication on Linkedin';
$string['badge_share'] = 'Share';
$string['badge_share_title'] = 'Share your achievement!';
$string['badge_twitter'] = 'Share badge on Twitter';
$string['bought'] = 'Ticket bought';
$string['buy'] = 'Buy';
$string['buyticket'] = 'Buy ticket';
$string['buyticketmessage'] = 'Do you really want to buy this ticket?';
$string['changessaved'] = 'Changes saved';
$string['criteria_emisor'] = 'Issuer name: ';
$string['coinsbypoints'] = 'Coins by points';
$string['coinsbypoints_help'] = '';
$string['configheader_modules'] = 'Points by course modules completion';
$string['configmodules_help'] = 'Assign points to activities that should be considered.';
$string['cost'] = 'Cost';
$string['course-ranking_help'] = 'Earn points and climb the course\'s ranking!';
$string['currentlevel'] = 'You current level is <strong>{$a}</strong>';
$string['customtitle'] = 'Custom title';
$string['defaultlevel'] = 'Default level';
$string['deleted'] = 'Record deleted successfully';
$string['durationfield'] = 'Duration field';
$string['durationfield_help'] = 'A course field to specify the course duration time.';
$string['dynamichelps'] = 'Tab Help';
$string['dynamic_help-coinsbypoints'] = 'with <strong>{$a} coins</strong>.';
$string['dynamic_help-noactivities'] = 'There are no activities that give points in this course.';
$string['dynamic_help-pointsbyendcourse'] = 'Earn <strong>{$a} points</strong> by completing a course.';
$string['dynamic_help-pointsbyendcourseduration_site'] = 'Earn <strong>{$a}*X points</strong> by completing a course, be <strong>X</strong> the recommended duration of the course.';
$string['dynamic_help-pointsbyendcourseduration'] = 'Earn <strong>{$a} points</strong> by completing this course.';
$string['dynamic_help-pointsrecurrentlogin'] = 'and earn <strong>{$a} points</strong>.';
$string['dynamic_help-pointsbyday'] = 'After you start a streak, you will earn <strong>{$a} points every day</strong>.';
$string['dynamic_help-pointstocoins'] = 'Each time you earn <strong>{$a} points</strong> you will be awarded ';
$string['dynamic_help-pointsbyendmodule'] = 'Earn <strong>{$a} points</strong> for each resource you finish in this course.';
$string['dynamic_help-pointsbymodule'] = '{$a} points';
$string['dynamic_help-recurrentlogindays'] = 'Login <strong>{$a} days</strong> to begin a streak ';
$string['dynamic_help_title'] = 'Obtain points for the following items';
$string['edit'] = 'Edit';
$string['editnickname'] = 'Edit the nickname';
$string['emailinvalidpattern'] = 'Invalid email pattern';
$string['emailinvalidpattern_help'] = 'For a user to get points by changing email, the new email <strong>cannot meet</strong> the regular pattern that is set here. For example: with the pattern <em>@([^@]*\\\\.)?(tests\\\\.mail)</em>, those who define an email <em>@tests.mail</em> will not get points.';
$string['emailvalidpattern'] = 'Valid email pattern';
$string['emailvalidpattern_help'] = 'In order for a user to obtain points when changing the email, the new email <strong>must comply</strong> with the regular pattern that is configured here. For example: with the pattern <em>@([^@]*\\\\.)?(tests\\\\.mail)</em> only those who define an email <em>@tests.mail</em> will get points.';
$string['enabled'] = 'Enabled';
$string['errornotavatardata'] = 'Not avatar data';
$string['errornotticketdata'] = 'Not ticket data';
$string['eventavatar_created'] = 'Avatar created';
$string['eventavatar_deleted'] = 'Avatar deleted';
$string['eventavatar_updated'] = 'Avatar updated';
$string['eventcriteria_created'] = 'Improve criteria created';
$string['eventcriteria_updated'] = 'Improve criteria updated';
$string['eventticket_created'] = 'Ticket created';
$string['eventticket_updated'] = 'Ticket updated';
$string['eventticket_deleted'] = 'Ticket deleted';
$string['generalstate'] = 'General state';
$string['generalsettings'] = 'General';
$string['gift'] = 'Gift';
$string['give'] = 'Give';
$string['given'] = 'Ticket given';
$string['giveticket'] = 'Give a ticket';
$string['giveticketmessage'] = 'Choose the target contact';
$string['helptitle_pointsbycoursemodule'] = 'This course gives points for complete the following resources:';
$string['home'] = 'Home';
$string['homeurl'] = 'Home URL';
$string['homeurl_help'] = 'URL to redirect when the user click on the home button. Empty to hide the home button.';
$string['hideranking'] = 'Hide';
$string['improvecriteria'] = 'Improve badge criteria';
$string['improvecriteria_ncourses'] = 'N finished courses';
$string['improvecriteria_ncourses_label'] = '{$a} finished courses';
$string['improvecriteria_ncourses_n'] = 'N value';
$string['improvecriteria_ncourses_n_help'] = 'Number of finished courses required to apply the badge criteria.';
$string['improvecriterianot'] = 'The badge has no improve criteria';
$string['improvecriteriaudpate'] = 'Badge criteria update';
$string['improvecriteriasaved'] = 'Badge criteria saved';
$string['infodata'] = 'Related data (JSON format)';
$string['invalidbadgecriteriatype'] = "Invalid badge criteria type. Only cohort type is available.";
$string['labellevel'] = 'Level {$a}';
$string['lastmonth-ranking_help'] = 'List of best players in the current month.';
$string['levels'] = 'Levels';
$string['level_help'] = 'Collect points to take your avatar to the next level!';
$string['levelrequired'] = 'Level <em>{$a}</em> required';
$string['levels_help'] = 'One level by line, with the structure: Level name|max points.<br />
The max points in the last line are not required, unlimited points are assigned.<br />
The line number indicate the level order.';
$string['moreinfo'] = 'More information';
$string['maxtickets'] = 'You currently have the maximum quantity of this ticket.';
$string['missingfield'] = 'This field is required';
$string['name_help'] = 'Personalize the name that appears on your home page by clicking on the name. Once you have finished, press the Enter key to save the changes. The name that you define will appear in the site\'s position table.';
$string['newblocktitle'] = 'Gamification';
$string['newnickname'] = 'new value to {$a}';
$string['newticket'] = 'New ticket';
$string['nicknameexists'] = 'The alias is already in use by another user, please choose another alias.';
$string['nicknameunasined'] = 'Player {$a}';
$string['notuserbadges'] = 'Not badges yet';
$string['noavatars'] = 'Avatars not available yet';
$string['notavailable'] = 'No available to buy.';
$string['notavailabledate'] = 'The date to buy this ticket has already passed.';
$string['notbuy'] = 'Error buying';
$string['notcompliance'] = 'You don\'t compliance the requirements to buy it ticket.';
$string['notcontacts'] = 'You don\'t have contacts yet';
$string['notcostcompliance'] = 'Insufficient coins';
$string['notenabledbadge'] = 'You don\'t have this badge yet.';
$string['notgive'] = 'Error giving a ticket';
$string['notickets'] = 'Not available tickets yet';
$string['nottopyet'] = 'Not positions yet';
$string['notusertickets'] = 'Not tickets yet';
$string['numcoins'] = '{$a} coins';
$string['numpoints'] = '{$a} points';
$string['levelup'] = 'Level up!';
$string['overcomelevel'] = 'Get <strong>{$a->maxpoints} points</strong> to reach the level <strong>{$a->name}</strong>.';
$string['owner'] = 'Owner';
$string['playerhead'] = 'Player';
$string['pointshead'] = 'Points';
$string['pointsbychangemail'] = 'Points by change email';
$string['pointsbychangemail_help'] = 'Points assigned to the user when he modifies his profile and establishes a valid email address.
Points are only awarded once per user.
If no rule is configured, points will be given for this concept regardless of new mail.
You can configure both or a single rule (valid or invalid) and points will be given for this concept if the email complies with the configured rule.';
$string['pointsbyendcourse'] = 'Points by complete a course';
$string['pointsbyendcourse_help'] = '';
$string['pointsbyendcoursemodule'] = 'Points by complete a course module';
$string['pointsbyendcoursemodule_help'] = 'Only if previous option is enabled. Otherwise, will be evaluated with settings in course block instance.';
$string['pointsbyendallmodules'] = 'All course module give points';
$string['pointsbyendallmodules_help'] = 'Otherwise, previous points will be given when any course module is complete and particular settings in each course will be not considered.';
$string['pointsbynewuser'] = 'Points for every new user';
$string['pointsbynewuser_help'] = '';
$string['pointsbyrecurrentlogin1'] = 'Points to recurrent login';
$string['pointsbyrecurrentlogin1_help'] = '';
$string['pointsbyrecurrentlogin2'] = 'Points additionals by next days';
$string['pointsbyrecurrentlogin2_help'] = '';
$string['points_help'] = 'As you earn points you will be awarded with coins, use them in the store!';
$string['pointstocoins'] = 'Points to add coins';
$string['pointstocoins_help'] = '';
$string['positionhead'] = '#';
$string['ranking_button'] = 'Ranking';
$string['ranking_course'] = 'Course';
$string['ranking_last'] = 'Last month';
$string['ranking_site'] = 'Site';
$string['recorddeleted'] = 'Record deleted';
$string['recurrentlogindays'] = 'Recurrent login days';
$string['recurrentlogindays_help'] = 'Days to start to assign points. 0 to don\'t use this.';
$string['requiretext'] = 'Prerequisites';
$string['searchticket'] = 'Search';
$string['socialnetworks'] = 'Social networks to share badge';
$string['socialnetworks_help'] = '<em>One network by line, with the next structure:</em><br /><br />
twitter|https://twitter.com/intent/tweet?text={name}&url={url}<br /><br />
Write the site name in lowecase.<br /><br />
Additionally, if you want to include the share link of the achievement as a LinkedIn badge, you can use the next structure:<br /><br />
linkedin|https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME&name={name}&<strong>organizationId=<em>ORGANIZATION ID</strong></em>&issueYear={badgeyear}&issueMonth={badgemonth}&certUrl={url}&certId={badgeid}&credentialDoesNotExpire=1<br /><br />
<em><strong>organizationId</strong></em> is the only param that you will need to edit, it\'s the ID of the LinkedIn profile of the organization issuing the certificate, look how to get it <a href="https://bambuco.co/ludifica/ludifica-configuracion-general/#network" target="_blank">here</a>.<br /><br />
<em>You do not need to include all keys or parameters.</em>';
$string['socialnetworks_default'] = 'facebook|https://www.facebook.com/sharer/sharer.php?u={url}
twitter|https://twitter.com/intent/tweet?text={name}&url={url}
linkedin|https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME&name={name}&organizationId=ORGANIZATION ID&issueYear={badgeyear}&issueMonth={badgemonth}&certUrl={url}&certId={badgeid}&credentialDoesNotExpire=1';
$string['settingsheaderpointscourse'] = 'When end course';
$string['settingsheaderpointslogin'] = 'Login points';
$string['settingsheaderpointsmodules'] = 'Modules points';
$string['settingsheaderpointsother'] = 'Other points';
$string['settingsheadercoins'] = 'Coins';
$string['settingsheaderlevels'] = 'Levels';
$string['settingsheaderappearance'] = 'Appearance';
$string['settingsheaderbadges'] = 'Badges';
$string['showicon'] = 'Icons only';
$string['showranking'] = 'Show ranking';
$string['showtext'] = 'Text only';
$string['showtopbycourse'] = 'Top by course ►';
$string['showtopbysite'] = 'Top by site ►';
$string['showlastmonth'] = 'Last month top ►';
$string['site-ranking_help'] = 'Earn points and climb the site\'s ranking!';
$string['benefits'] = 'Benefits';
$string['tablastmonth'] = 'Tab Top on the last month';
$string['tabprofile'] = 'Tab Profile';
$string['tabprofile_help'] = 'All tabs are enabled with this option';
$string['tabtitle_lastmonth'] = 'Last month top';
$string['tabtitle_profile'] = 'Profile';
$string['tabtitle_topbycourse'] = 'Top by course';
$string['tabtitle_topbysite'] = 'Top by site';
$string['tabtitle_dynamichelps'] = 'Help';
$string['tabtopbycourse'] = 'Tab Top by course';
$string['tabtopbysite'] = 'Tab Top by site';
$string['tabview'] = 'Views tabs';
$string['tabview_help'] = 'Choose tab layout when more than one view is available.';
$string['templatetype'] = 'Template type';
$string['templatetype_help'] = 'Choose a template to change the block pages appearance';
$string['textandicon'] = 'Text and icons';
$string['ticket'] = 'Ticket';
$string['ticketdelete'] = 'Delete ticket';
$string['tickets'] = 'Tickets';
$string['ticketstype_default'] = 'Default';
$string['ticketstype'] = 'Type';
$string['ticketcode'] = 'Code';
$string['ticketcode_help'] = 'If empty, the system assign a random string to each user.';
$string['ticketavailabledate'] = 'Available date';
$string['ticketavailable'] = 'Amount available';
$string['tickets_help'] = 'Acquire a benefit using the earned coins, these can be virtual or real world incentives. You can keep the benefit for personal use and if you wish, you have the option to share it with a person in your contact list.';
$string['ticketnotavailable'] = 'The ticket is not available now, maybe was used in another session.';
$string['ticketbyuser'] = 'Amount by user';
$string['ticketsvalidate'] = 'Validate ticket';
$string['ticketused'] = 'Ticket used';
$string['thumbnail'] = 'Thumbnail';
$string['unlimited'] = 'Unlimited';
$string['unavailablewarning'] = 'You don\'t have this badge yet.';
$string['use'] = 'Use';
$string['usedat'] = 'Used at {$a}';
$string['useddate'] = 'Used date';
$string['usercode'] = 'User code';
$string['usercodes'] = 'User codes';
$string['usertickets'] = 'User tickets';
$string['useup'] = 'Use up';
