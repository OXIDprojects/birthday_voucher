<?php
$aModule = array(
    'id'              => 'birthday',
    'title'           => 'Geburtstagsgutschein',
    'version'         => '1.3',
    'author'          => 'Stefan Korn / Mod. Christian Bernhard',
    'url'             => 'http://www.meinestruempfe.de',
    'email'           => 'chris@dreamride.de',
    'description' =>  array(
        'de'=>'Sendet automatisch einen Geburtstagsgutschein',
        'en'=>'sends birthdaycoupon automatically',
    ),
    'extend' => array(
					 'start' => 'birthday/birthday_start',
           'oxadmindetails' => 'birthday/birthday_admin',
					 'oxemail' => 'birthday/birthday_mail',
    ),
    'templates' => array(
        'birthday_interface.tpl' => 'birthday/admin/out/tpl/birthday_interface.tpl',
        'birthday_mail_html.tpl' => 'birthday/email/html/birthday_mail_html.tpl',
        'birthday_mail_plain.tpl' => 'birthday/email/plain/birthday_mail_plain.tpl',
    ),
);