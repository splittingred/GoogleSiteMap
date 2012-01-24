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
 * Properties English Topic for GoogleSiteMap
 *
 * @package googlesitemap
 * @subpackage lexicon
 * @language en
 */
$_lang['prop_gsm.allowedtemplates_desc'] = 'A comma-separated list of Template IDs to filter by. Will only filter if a value is set.';
$_lang['prop_gsm.context_desc'] = 'Limit to the specified Context(s). If empty, will grab Resources from current Context. Defaults to empty, can support a comma-separated list.';
$_lang['prop_gsm.maxdepth_desc'] = 'The depth down the tree to grab Resources. If set to empty or 0, will grab all depths.';
$_lang['prop_gsm.excluderesources_desc'] = 'Optional. A comma-separated list of Resources to skip.';
$_lang['prop_gsm.excludechildrenof_desc'] = 'Optional. A comma-separated list of Resources whose children to skip.';
$_lang['prop_gsm.hidedeleted_desc'] = 'If true, will show only nondeleted Resources.';
$_lang['prop_gsm.showhidden_desc'] = 'If true, will show Resources hidden from menus.';
$_lang['prop_gsm.googleschema_desc'] = 'The location of the GoogleSiteMap schema.';
$_lang['prop_gsm.published_desc'] = 'If true, will only show published resources.';
$_lang['prop_gsm.searchable_desc'] = 'If true, will only show searchable resources.';
$_lang['prop_gsm.sortby_desc'] = 'The field to sort the results by.';
$_lang['prop_gsm.sortbyalias_desc'] = 'The class to use as the alias for the sortBy property.';
$_lang['prop_gsm.sortdir_desc'] = 'The direction to sort in.';
$_lang['prop_gsm.startid_desc'] = 'Optional. The ID of a Resource to start the sitemap from. 0 will do the entire site.';
$_lang['prop_gsm.templatefilter_desc'] = 'The modTemplate column to filter by.';
$_lang['prop_gsm.itemtpl_desc'] = 'The Chunk to use for each result item.';
$_lang['prop_gsm.containertpl_desc'] = 'The Chunk to use for the output container.';
$_lang['prop_gsm.where_desc'] = 'Optional. A JSON object of parameters to pass into the where statement.';
$_lang['prop_gsm.prioritytv_desc'] = 'Optional. The name or ID of a TV to use to override the priority for a page.';