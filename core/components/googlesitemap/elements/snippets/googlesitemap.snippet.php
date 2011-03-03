<?php
/**
 * GoogleSiteMap
 *
 * Copyright 2009-2010 by Shaun McCormick <shaun@modx.com>
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
 */
require_once $modx->getOption('googlesitemap.core_path',null,$modx->getOption('core_path').'components/googlesitemap/').'model/googlesitemap/googlesitemap.class.php';
$GoogleSiteMap = new GoogleSiteMap($modx,$scriptProperties);

/* setup default properties */
$googleSchema = $modx->getOption('googleSchema',$scriptProperties,'http://www.sitemaps.org/schemas/sitemap/0.9');
$containerTpl = $modx->getOption('containerTpl',$scriptProperties,'gContainer');
$startId = (int)$modx->getOption('startId',$scriptProperties,0);

/* get container tpl and content */
return $GoogleSiteMap->getChunk($containerTpl,array(
    'schema' => $googleSchema,
    'items' => $GoogleSiteMap->run($startId),
));
