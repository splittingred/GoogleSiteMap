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
class GoogleSiteMap {
    /** @var modX $modx */
    public $modx;
    /** @var array $chunks */
    public $chunks = array();

    /**
     * Creates an instance of the GoogleSiteMap class.
     *
     * @var modX $modx
     * @var array $config
     */
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('googlesitemap.core_path',null,$this->modx->getOption('core_path').'components/googlesitemap/');
        $this->config = array_merge(array(
            'allowedtemplates' => '',
            'context' => '',
            'googleSchema' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
            'hideDeleted' => true,
            'published' => true,
            'searchable' => true,
            'sortBy' => 'menuindex',
            'sortByAlias' => 'modResource',
            'sortDir' => 'ASC',
            'templateFilter' => 'id',
            'itemSeparator' => "\n",
            'itemTpl' => 'gItem',
            'chunksPath' => $corePath.'elements/chunks/',
            'maxDepth' => 0,
            'excludeResources' => '',
            'where' => '',
            'excludeChildrenOf' => '',
            'showHidden' => false,
            'priorityTV' => 0,
        ),$config);
    }

    /**
     * Run the Google SiteMap XML generation, recursively
     *
     * @param int $currentParent The current parent resource the iteration
     * is on
     * @param int $selfId If specified, will exclude this ID
     * @param int $depth
     * @return string The generated XML
     */
    public function run($currentParent,$selfId = -1,$depth = 0) {
        if (!empty($this->config['maxDepth']) && $depth >= $this->config['maxDepth']) return '';
        $output = '';

        /* get children */
        $c = $this->getQuery($currentParent);
        $children = $this->modx->getCollection('modResource',$c);

        /** @var modResource $child */
        foreach ($children as $child) {
            $id = $child->get('id');
            if ($selfId == $id) continue;

            $canParse = true;
            if ($this->config['searchable']) {
                $canParse = $canParse && $child->get('searchable');
            }
            if ($this->config['published']) {
                $canParse = $canParse && $child->get('published');
            }
            if ($this->config['hideDeleted']) {
                $canParse = $canParse && !$child->get('deleted');
            }
            if (empty($this->config['showHidden'])) {
                $canParse = $canParse && (!$child->get('hidemenu') || $child->get('class_key') == 'Article');
            }

            if ($canParse) {
                $url = $this->modx->makeUrl($id,'','','full');

                $date = $child->get('editedon') ? $child->get('editedon') : $child->get('createdon');
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
                if (!empty($this->config['priorityTV'])) {
                    $priorityTV = $child->getTVValue($this->config['priorityTV']);
                    if (!empty($priorityTV)) {
                        $priority = $priorityTV;
                    }
                }
                /* add item to output */
                $output .= $this->getChunk($this->config['itemTpl'],array(
                    'url' => $url,
                    'date' => $date,
                    'update' => $update,
                    'priority' => $priority,
                )).$this->config['itemSeparator'];
            }

            /* if children, recurse */
            if ($child->get('children') > 0) {
                $output .= $this->run($child->get('id'),$selfId,$depth+1);
            }
        }
        return $output;
    }

    public function getQuery($currentParent) {
        /* build query */
        $c = $this->modx->newQuery('modResource');
        $c->leftJoin('modResource','Children');
        $c->select($this->modx->getSelectColumns('modResource','modResource'));
        $c->select(array(
            'COUNT(Children.id) AS children',
        ));
        $c->where(array(
            'parent' => $currentParent,
        ));

        /* if restricting to contexts */
        if (!empty($this->config['context'])) {
            $ctxs = $this->prepareForIn($this->config['context']);
            $c->where(array(
                'modResource.context_key:IN' => $ctxs,
            ));
        } else {
            $c->where(array('modResource.context_key' => $this->modx->context->get('key')));
        }

        /* if excluding resources */
        if (!empty($this->config['excludeResources'])) {
            $ex = $this->prepareForIn($this->config['excludeResources']);
            $c->where(array(
                'modResource.id:NOT IN' => $ex,
            ));
        }

        /* if excluding all children of certain resources */
        if (!empty($this->config['excludeChildrenOf'])) {
            $excludingParents = is_array($this->config['excludeChildrenOf']) ? $this->config['excludeChildrenOf'] : explode(',',$this->config['excludeChildrenOf']);
            $excludedChildren = array();
            foreach ($excludingParents as $excludingParent) {
                $childrenIds = $this->modx->getChildIds($excludingParent,10);
                $excludedChildren = array_merge($excludedChildren,$childrenIds);
            }
            $excludedChildren = array_unique($excludedChildren);
            $c->where(array(
                'modResource.id:NOT IN' => $excludedChildren,
            ));
        }

        /* if restricting to templates */
        if (!empty($this->config['allowedtemplates'])) {
            $tpls = $this->prepareForIn($this->config['allowedtemplates']);
            $c->innerJoin('modTemplate','Template');
            $c->where(array(
                'Template.'.$this->config['templateFilter'].':IN' => $tpls,
            ));
        }

        /* where filtering */
        if (!empty($this->config['where'])) {
            $where = is_array($this->config['where']) ? $this->config['where'] : $this->modx->fromJSON($this->config['where']);
            $c->where($where);
        }

        /* sorting/grouping */
        $c->sortby($this->config['sortBy'],$this->config['sortDir']);
        $c->groupby('modResource.id');

        return $c;
    }

    /**
     * Prepares a comma-separated list for an IN statement
     * @param string $str
     * @param string $delimiter
     * @return array
     */
    protected function prepareForIn($str,$delimiter = ',') {
        $cslArray = is_array($str) ? $str : explode($delimiter,$str);
        $cslArray = array_unique($cslArray);
        $results = array();
        foreach ($cslArray as $item) {
            $results[] = $item;
        }
        return $results;
    }


    /**
     * Gets a Chunk and caches it; also falls back to file-based templates
     * for easier debugging.
     *
     * @access public
     * @param string $name The name of the Chunk
     * @param array $properties The properties for the Chunk
     * @return string The processed content of the Chunk
     */
    public function getChunk($name,$properties = array()) {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            $chunk = $this->_getTplChunk($name);
            if (empty($chunk)) {
                $chunk = $this->modx->getObject('modChunk',array('name' => $name),true);
                if ($chunk == false) return false;
            }
            $this->chunks[$name] = $chunk->getContent();
        } else {
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }

    /**
     * Returns a modChunk object from a template file.
     *
     * @access private
     * @param string $name The name of the Chunk. Will parse to name.chunk.tpl
     * @param string $postFix
     * @return modChunk/boolean Returns the modChunk object if found, otherwise
     * false.
     */
    private function _getTplChunk($name,$postFix = '.chunk.tpl') {
        $chunk = false;
        $f = $this->config['chunksPath'].strtolower($name).$postFix;
        if (file_exists($f)) {
            $o = file_get_contents($f);
            /** @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name',$name);
            $chunk->setContent($o);
        }
        return $chunk;
    }
}



if (!function_exists('quoteArrayItem')) {
    function quoteArrayItem(&$v) { $v = '"'.$v.'"'; }
}
if (!function_exists('datediff')) {
    /**
     * @param string $interval Can be:
        yyyy - Number of full years
        q - Number of full quarters
        m - Number of full months
        y - Difference between day numbers
        (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The
        datediff is "-32".)
        d - Number of full days
        w - Number of full weekdays
        ww - Number of full weeks
        h - Number of full hours
        n - Number of full minutes
        s   - Number of full seconds (default)
     * @param $datefrom
     * @param $dateto
     * @param bool $using_timestamps
     * @return float|int|string
     * @package googlesitemap
     */
    function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
        if (!$using_timestamps) {
            $datefrom = strtotime($datefrom, 0);
            $dateto = strtotime($dateto, 0);
        }
        $difference = $dateto - $datefrom; /* Difference in seconds */
        switch($interval) {
            case 'yyyy': /* Number of full years */
                $years_difference = floor($difference / 31536000);
                if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
                    $years_difference--;
                }
                if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
                    $years_difference++;
                }
                $datediff = $years_difference;
                break;

            case 'q': /* Number of full quarters */
                $quarters_difference = floor($difference / 8035200);
                while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
                    $quarters_difference++;
                }
                $quarters_difference--;
                $datediff = $quarters_difference;
                break;

            case 'm': /* Number of full months */
                $months_difference = floor($difference / 2678400);
                while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
                    $months_difference++;
                }
                $months_difference--;
                $datediff = $months_difference;
                break;

            case 'y': /* Difference between day numbers */
                $datediff = date('z',$dateto) - date('z',$datefrom);
                break;

            case 'd': /* Number of full days */
                $datediff = floor($difference / 86400);
                break;

            case 'w': /* Number of full weekdays */
                $days_difference = floor($difference / 86400);
                $weeks_difference = floor($days_difference / 7); /* Complete weeks */
                $first_day = date('w', $datefrom);
                $days_remainder = floor($days_difference % 7);
                /* Do we have a Saturday or Sunday in the remainder? */
                $odd_days = $first_day + $days_remainder;
                if ($odd_days > 7) { /* Sunday */
                    $days_remainder--;
                }
                if ($odd_days > 6) { /* Saturday */
                    $days_remainder--;
                }
                $datediff = ($weeks_difference * 5) + $days_remainder;
                break;

            case 'ww': /* Number of full weeks */
                $datediff = floor($difference / 604800);
                break;

            case 'h': /* Number of full hours */
                $datediff = floor($difference / 3600);
                break;

            case 'n': /* Number of full minutes */
                $datediff = floor($difference / 60);
                break;

            default: /* Number of full seconds (default) */
                $datediff = $difference;
                break;
        }
        return $datediff;
    }
}