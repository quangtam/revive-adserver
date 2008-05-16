<?php

/*
+---------------------------------------------------------------------------+
| OpenX v${RELEASE_MAJOR_MINOR}                                                                |
| =======${RELEASE_MAJOR_MINOR_DOUBLE_UNDERLINE}                                                                |
|                                                                           |
| Copyright (c) 2003-2008 OpenX Limited                                     |
| For contact details, see: http://www.openx.org/                           |
|                                                                           |
| This program is free software; you can redistribute it and/or modify      |
| it under the terms of the GNU General Public License as published by      |
| the Free Software Foundation; either version 2 of the License, or         |
| (at your option) any later version.                                       |
|                                                                           |
| This program is distributed in the hope that it will be useful,           |
| but WITHOUT ANY WARRANTY; without even the implied warranty of            |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
| GNU General Public License for more details.                              |
|                                                                           |
| You should have received a copy of the GNU General Public License         |
| along with this program; if not, write to the Free Software               |
| Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
+---------------------------------------------------------------------------+
$Id$
*/

$file = '/lib/OA/Delivery/marketplace.php';
###START_STRIP_DELIVERY
if(isset($GLOBALS['_MAX']['FILES'][$file])) {
    return;
}
###END_STRIP_DELIVERY
$GLOBALS['_MAX']['FILES'][$file] = true;

/**
 * @package    MaxDelivery
 * @subpackage marketplace
 * @author     Matteo Beccati <matteo.beccati@openx.org>
 *
 * This library defines functions that need to be available to
 * marketplace-enabled delivery engine scripts
 *
 */

function MAX_marketplaceGetIdWithRedirect($scriptName = null)
{
    $aConf = $GLOBALS['_MAX']['CONF'];
    if (!empty($aConf['marketplace']['enabled'])) {
        $oxidOnly = $aConf['marketplace']['cacheTime'] == 0;
        $viewerId = MAX_cookieGetUniqueViewerId(false, $oxidOnly);
        if (!isset($viewerId) && !isset($_GET['openxid'])) {
            $scriptName = isset($scriptName) ? $scriptName : basename($_SERVER['SCRIPT_NAME']);
            $oxpUrl = MAX_commonGetDeliveryUrl($scriptName).'?'.$_SERVER['QUERY_STRING'].'&openxid=OPENX_ID';
            $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http').'://'.
            $url .= $aConf['marketplace']['idHost'].'/redir?r='.urlencode($oxpUrl);
            $url .= '&pid=OpenXDemo';
            $url .= '&cb='.mt_rand(0, PHP_INT_MAX);
            header("Location: {$url}");
            exit;
        }
    }
}

function MAX_marketplaceGetIdSpcGet($varPrefix)
{
    $aConf = $GLOBALS['_MAX']['CONF'];
    $script = '';
    if (!empty($aConf['marketplace']['enabled'])) {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http').'://'.
        $url .= $aConf['marketplace']['idHost'].'/jsox?n='.urlencode($varPrefix.'spc');
        $url .= '&pid=OpenXDemo';
        $url .= '&cb='.mt_rand(0, PHP_INT_MAX);

        $script .= "
    var {$varPrefix}spc=\"<\"+\"script type='text/javascript' \";
    {$varPrefix}spc+=\"src='".htmlspecialchars($url, ENT_QUOTES)."'><\"+\"/script>\";
    document.write({$varPrefix}spc);
";
    }

    return $script;
}

function MAX_marketplaceGetIdSpcDisplay($varPrefix)
{
    $aConf = $GLOBALS['_MAX']['CONF'];
    $script = '';
    if (!empty($aConf['marketplace']['enabled'])) {
        $script .= "
    {$varPrefix}spc+=\"&openxid=OPENX_ID'><\"+\"/script>\";";
    } else {
        $script .= "
    {$varPrefix}spc+=\"<\"+\"/script>\";";
    }

    if (empty($aConf['marketplace']['enabled'])) {
        $script .= "
    document.write({$varPrefix}spc);";
    }

    return $script;
}


?>
