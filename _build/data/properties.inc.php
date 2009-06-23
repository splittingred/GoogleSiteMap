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
 * @package googlesitemap
 */
$properties = array(
    array(
        'name' => 'allowedtemplates',
        'desc' => 'A comma-separated list of Template IDs to filter by. Will only filter if a value is set.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'hideDeleted',
        'desc' => 'If true, will show only nondeleted Resources.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'googleSchema',
        'desc' => 'The location of the GoogleSiteMap schema.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'http://www.google.com/schemas/sitemap/0.84',
    ),
    array(
        'name' => 'published',
        'desc' => 'If true, will only show published resources.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'searchable',
        'desc' => 'If true, will only show searchable resources.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'sortBy',
        'desc' => 'The field to sort the results by.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'menuindex',
    ),
    array(
        'name' => 'sortByAlias',
        'desc' => 'The class to use as the alias for the sortBy property.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'modResource',
    ),
    array(
        'name' => 'sortDir',
        'desc' => 'The direction to sort in.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'ASC',
    ),
    array(
        'name' => 'templateFilter',
        'desc' => 'The modTemplate column to filter by.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'id',
    ),
);
return $properties;