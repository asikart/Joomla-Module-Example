<?php
/**
 * @package         Asikart.Module
 * @subpackage      mod_example
 * @copyright       Copyright (C) 2012 Asikart.com, Inc. All rights reserved.
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

abstract class modExampleHelper
{
    public static function getItems(&$params)
    {
        // init db
        // ===========================================================================
        $db     = JFactory::getDbo();
        $q      = $db->getQuery(true) ;
        
        
        // get Joomla! API
        // ===========================================================================
        $app     = JFactory::getApplication() ;
        $user    = JFactory::getUser() ;
        $date    = JFactory::getDate( 'now' , JFactory::getConfig()->get('offset') ) ;
        $uri     = JFactory::getURI() ;
        $doc     = JFactory::getDocument();
        
        
        
        // get Params and prepare data.
        // ===========================================================================
        $catid         = $params->get('catid', 1) ;
        $order         = $params->get('orderby', 'a.created') ;
        $dir           = $params->get('order_dir', 'DESC') ;
        
        
        
        // Category
        // =====================================================================================
        // if Choose all category, select ROOT category.
        if(!in_array(1, $catid)) {
            // if is array, implode it.
            if(is_array($catid)) $catid = implode(',', $catid) ;
            
            $q->where("a.catid IN ({$catid})") ;
        }
        
        
        
        // Published
        // =====================================================================================
        $q->where('a.published > 0') ;
        
        $nullDate = $db->Quote($db->getNullDate());
        $nowDate = $db->Quote($date->toSql(true));

        $q->where('(a.publish_up   = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')');
        $q->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
        
        
        
        // View Level
        // =====================================================================================
        $groups    = implode(',', $user->getAuthorisedViewLevels());
        $q->where('a.access IN ('.$groups.')');
        
        
        
        // Language
        // =====================================================================================
        if ($app->getLanguageFilter()) {
            $lang_code = $db->quote( JFactory::getLanguage()->getTag() ) ;
            $q->where("a.language IN ('{$lang_code}', '*')");
        }
        
        
        
        // Load Data
        // ===========================================================================
        $items = array() ;
        
        $q->select("a.*, b.*")
            ->from('#__example_items AS a')
            ->join('LEFT', '#__categories AS b ON a.catid = b.id')
            //->where("")
            ->order("{$order} {$dir}")
            ;
        
        $db->setQuery($q);
        $items = $db->loadObjectList();
        
        
        
        // Handle Data
        // ===========================================================================
        if( $items ):
        
            foreach( $items as $key => &$item ):
                $item->link = JRoute::_("index.php?option=com_example&view=item&id={$item->id}&alias={$item->alias}&catid={$item->catid}") ;
            endforeach;
            
        else:
            
            $items = range(1, 5) ;
            foreach( $items as $key => &$item ):
            
                $item = new JObject();
                $item->a_title   = 'Example data - ' . ( $key +1 );
                $item->link      = '#' ;
                $item->a_created = $date->toSQL(true) ;
                
            endforeach;
            
        endif ;
        
        
        return $items ;
    }
}
