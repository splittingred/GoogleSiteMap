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
 * @package googlesitemap
 */
$properties = array(
    array(
        'name' => 'allowedtemplates',
        'desc' => 'prop_gsm.allowedtemplates_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'context',
        'desc' => 'prop_gsm.context_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'maxDepth',
        'desc' => 'prop_gsm.maxdepth_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 0,
    ),
    array(
        'name' => 'hideDeleted',
        'desc' => 'prop_gsm.hidedeleted_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'googleSchema',
        'desc' => 'prop_gsm.googleschema_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
    ),
    array(
        'name' => 'published',
        'desc' => 'prop_gsm.published_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'searchable',
        'desc' => 'prop_gsm.searchable_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'excludeResources',
        'desc' => 'prop_gsm.excluderesources_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'sortBy',
        'desc' => 'prop_gsm.sortby_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'menuindex',
    ),
    array(
        'name' => 'sortByAlias',
        'desc' => 'prop_gsm.sortbyalias_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'modResource',
    ),
    array(
        'name' => 'sortDir',
        'desc' => 'prop_gsm.sortdir_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'ASC',
    ),
    array(
        'name' => 'templateFilter',
        'desc' => 'prop_gsm.templatefilter_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'id',
    ),
    array(
        'name' => 'itemTpl',
        'desc' => 'prop_gsm.itemtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'gItem',
    ),
    array(
        'name' => 'containerTpl',
        'desc' => 'prop_gsm.containertpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'gContainer',
    ),
);
return $properties;