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
class GoogleSiteMap {
    /**#@+
     * Creates an instance of the GoogleSiteMap class.
     */
    function GoogleSiteMap(&$modx,$config = array()) {
        $this->__construct($modx,$config);
    }
    /** @ignore */
    function __construct(&$modx,$config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge(array(
            'allowedtemplates' => '',
            'googleSchema' => 'http://www.google.com/schemas/sitemap/0.84',
            'hideDeleted' => true,
            'published' => true,
            'searchable' => true,
            'sortBy' => 'menuindex',
            'sortByAlias' => 'modResource',
            'sortDir' => 'ASC',
            'templateFilter' => 'id',
        ),$config);
    }
    /**#@-*/

    /**
     * Runs the sitemap generation.
     *
     * @access public
     * @return string The XML google output
     */
    function generate() {
        $xml = "<urlset xmlns=\"".$this->config['googleSchema']."\">\n";
        $xml .= $this->_run(0);
        $xml .= "</urlset>";
        return $xml;
    }

    /**
     * Run the Google SiteMap XML generation, recursively
     *
     * @access private
     * @param integer $currentParent The current parent resource the iteration
     * is on
     * @param integer $selfId If specified, will exclude this ID
     * @return string The generated XML
     */
    function _run($currentParent = 0,$selfId = -1) {
        $output = '';

        $c = $this->modx->newQuery('modResource');
        $c->leftJoin('modResource','Children');
        $c->select('modResource.*, COUNT(Children.id) AS children');
        $c->where(array(
            'parent' => $currentParent,
        ));
        if ($this->config['published']) {
            $c->where(array('published' => true));
        }
        if ($this->config['hideDeleted']) {
            $c->where(array('deleted' => false));
        }
        if ($this->config['searchable']) {
            $c->where(array('searchable' => true));
        }

        if (!empty($this->config['allowedtemplates'])) {
            $atpls = split(',',$this->config['allowedtemplates']);
            array_walk($atpls,'quoteArrayItem');
            $tpls = implode(',',$atpls);

            $c->innerJoin('modTemplate','modTemplate');
            $c->where(array(
                'modTemplate.'.$this->config['templateFilter'].' IN ('.$tpls.')',
            ));
        }

        $c->sortby($this->config['sortBy'],$this->config['sortDir']);
        $c->groupby('modResource.id');
        $children = $this->modx->getCollection('modResource',$c);

        foreach($children as $child) {
            $id = $child->get('id');
            if ($selfId == $id) continue;

            $url = $this->modx->getOption('site_url').$this->modx->makeUrl($id);
            $date = $child->get('editedon');
            $date = date("Y-m-d", strtotime($date));
            /* Get the date difference */
            $datediff = datediff("d", $date, date("Y-m-d"));
            if ($datediff <=1) {
                $priority = '1.0';
                $update = 'daily';
            } elseif (($datediff >1) && ($datediff<=7)) {
                $priority = '0.75';
                $update = 'weekly';
            } elseif (($datediff >7) && ($datediff<=30)) {
                $priority = '0.50';
                $update = 'weekly';
            } else {
                $priority = '0.25';
                $update = 'monthly';
            }
            $output .= "<url>\n";
            $output .= "<loc>$url</loc>\n";
            $output .= "<lastmod>$date</lastmod>\n";
            $output .= "<changefreq>$update</changefreq>\n";
            $output .= "<priority>$priority</priority>\n";
            $output .= "</url>\n";
            if ($child->get('children') > 0) {
                $output .= $this->_run($child->get('id'),$selfId);
            }
        }
        return $output;
    }
}

if (!function_exists('quoteArrayItem')) {
    function quoteArrayItem(&$v) { $v = '"'.$v.'"'; }
}