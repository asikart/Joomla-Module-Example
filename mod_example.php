<?php
/**
 * @package         Asikart.Module
 * @subpackage      mod_example
 * @copyright       Copyright (C) 2012 Asikart.com, Inc. All rights reserved.
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$items              = modExampleHelper::getItems($params) ;
$moduleclass_sfx    = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_example', $params->get('layout', 'default'));