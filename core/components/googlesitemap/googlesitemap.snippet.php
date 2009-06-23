<?php
/**
 * GoogleSiteMap
 *
 * Copyright 2009 by Shaun McCormick <shaun@collabpad.com>
 *
 * - Based on Michal Till's MODx Evolution GoogleSiteMap_XML snippet
 *
 * GoogleSiteMap is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * GoogleSiteMap is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * GoogleSiteMap; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package googlesitemap
 */
/**
 * GoogleSiteMap for MODx Revolution
 *
 * @package googlesitemap
 * @author Shaun McCormick <shaun@collabpad.com>
 * @version 1.0
 */
$gsm_path = $modx->getOption('core_path').'components/googlesitemap/';
if (!function_exists('datediff')) {
    include_once $core_path.'datediff.function.php';
}
if (!class_exists('GoogleSiteMap')) {
    $modx->addPackage('googlesitemap',$gsm_path.'model/');
    $modx->loadClass('googlesitemap.GoogleSiteMap',$gsm_path.'model/',true,true);
}
$GoogleSiteMap = new GoogleSiteMap($modx,$scriptProperties);
return $GoogleSiteMap->generate();