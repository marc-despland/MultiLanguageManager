<?php
/*
 * Created on 27 mai 2007
 *
 * http://www.art122-5.net
 * Marc Despland 
 * 
 * This is the configuration file
 * 
 * Documentation about installation, how to use or modification could be found at :
 * http://www.art122-5.net/index.php/MultiLanguageManager_Extension
 *
 * Copyright (C) 2007  Marc Despland
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
 */


$mgAvailableLanguage=array(
 		'en' => 'multilanguagemanager_en',
 		'fr' => 'multilanguagemanager_fr');

$mgLanguagePermisionsKey='language'; 
$wgGroupPermissions['*'][$mgLanguagePermisionsKey] = false;
$wgGroupPermissions['user'][$mgLanguagePermisionsKey] = true;
$wgGroupPermissions['sysop'][$mgLanguagePermisionsKey] = true;
?>
