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
 * GoogleSiteMap build script
 *
 * @package googlesitemap
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

$root = dirname(dirname(__FILE__)).'/';
$sources= array (
    'root' => $root,
    'build' => $root .'_build/',
    'lexicon' => $root . '_build/lexicon/',
    'resolvers' => $root . '_build/resolvers/',
    'data' => $root . '_build/data/',
    'source_core' => $root.'core/components/googlesitemap',
    'source_assets' => $root.'assets/components/googlesitemap',
    'docs' => $root.'core/components/googlesitemap/docs/',
);
unset($root);

/* override with your own defines here (see build.config.sample.php) */
require_once dirname(__FILE__) . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx= new modX();
$modx->initialize('mgr');
$modx->setLogLevel(MODX_LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage('googlesitemap','1.0','beta1');
$builder->registerNamespace('googlesitemap',false,true,'{core_path}components/googlesitemap/');

/* load action/menu */
$action = include $sources['data'].'transport.action.php';

$vehicle= $builder->createVehicle($action,array (
    XPDO_TRANSPORT_PRESERVE_KEYS => false,
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
    XPDO_TRANSPORT_UNIQUE_KEY => array ('namespace','controller'),
    XPDO_TRANSPORT_RELATED_OBJECTS => true,
    XPDO_TRANSPORT_RELATED_OBJECT_ATTRIBUTES => array (
        'Menus' => array (
            XPDO_TRANSPORT_PRESERVE_KEYS => false,
            XPDO_TRANSPORT_UPDATE_OBJECT => true,
            XPDO_TRANSPORT_UNIQUE_KEY => array ('action', 'text'),
        ),
    ),
));
$builder->putVehicle($vehicle);
unset($vehicle,$action);

/* load system settings */
$settings = array();
include_once $sources['data'].'transport.settings.php';

$attributes= array(
    XPDO_TRANSPORT_UNIQUE_KEY => 'key',
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => false,
);
foreach ($settings as $setting) {
    $vehicle = $builder->createVehicle($setting,$attributes);
    $builder->putVehicle($vehicle);
}
unset($settings,$setting,$attributes);


/* create category */
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category','GoogleSiteMap');


/* create the snippet */
$snippet= $modx->newObject('modSnippet');
$snippet->set('id',0);
$snippet->set('name', 'GoogleSiteMap');
$snippet->set('description', 'Builds the Google SiteMap XML.');
$snippet->set('snippet',file_get_contents($sources['source_core'].'/googlesitemap.snippet.php'));

$properties = include $sources['data'].'properties.inc.php';
$snippet->setProperties($properties);
$category->addMany($snippet);

/* create category vehicle */
$attr = array(
    XPDO_TRANSPORT_UNIQUE_KEY => 'category',
    XPDO_TRANSPORT_PRESERVE_KEYS => false,
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
    XPDO_TRANSPORT_RELATED_OBJECTS => true,
    XPDO_TRANSPORT_RELATED_OBJECT_ATTRIBUTES => array (
        'modSnippet' => array(
            XPDO_TRANSPORT_PRESERVE_KEYS => false,
            XPDO_TRANSPORT_UPDATE_OBJECT => true,
            XPDO_TRANSPORT_UNIQUE_KEY => 'name',
        ),
    )
);
$vehicle = $builder->createVehicle($category,$attr);
$vehicle->resolve('file',array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));
$vehicle->resolve('file',array(
    'source' => $sources['source_assets'],
    'target' => "return MODX_ASSETS_PATH . 'components/';",
));
$builder->putVehicle($vehicle);

/* load lexicon strings */
$builder->buildLexicon($sources['lexicon']);

/* now pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
));

$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(MODX_LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

exit ();