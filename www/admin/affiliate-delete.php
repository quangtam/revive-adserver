<?php

/*
+---------------------------------------------------------------------------+
| Openads v${RELEASE_MAJOR_MINOR}                                                              |
| ============                                                              |
|                                                                           |
| Copyright (c) 2003-2007 Openads Limited                                   |
| For contact details, see: http://www.openads.org/                         |
|                                                                           |
| Copyright (c) 2000-2003 the phpAdsNew developers                          |
| For contact details, see: http://www.phpadsnew.com/                       |
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


// Require the initialisation file
require_once '../../init.php';

// Required files
require_once MAX_PATH . '/lib/OA/Dal.php';
require_once MAX_PATH . '/www/admin/config.php';
require_once MAX_PATH . '/www/admin/lib-zones.inc.php';
require_once MAX_PATH . '/lib/OA/Central/AdNetworks.php';

// Register input variables
phpAds_registerGlobal ('returnurl');

// Initialise Ad  Networks
$oAdNetworks = new OA_Central_AdNetworks();

// Security check
OA_Permission::enforceAccount(OA_ACCOUNT_MANAGER);
OA_Permission::enforceAccessToObject('affiliates', $affiliateid);

/*-------------------------------------------------------*/
/* Main code                                             */
/*-------------------------------------------------------*/

if (!empty($affiliateid))
{
	$doAffiliates = OA_Dal::factoryDO('affiliates');
	$doAffiliates->affiliateid = $affiliateid;

    // User unsubscribed from adnetworks
    $doAffiliates->get($affiliateid);
    $oacWebsiteId = ($doAffiliates->an_website_id) ? $doAffiliates->an_website_id : $doAffiliates->as_website_id;
    $aPublisher = array(
        array(
                'id'            => $affiliateid,
                'an_website_id' => $oacWebsiteId,
            )
        );
    $oAdNetworks->unsubscribeWebsites($aPublisher);
	$doAffiliates->delete();
}

if (empty($returnurl))
	$returnurl = 'affiliate-index.php';

Header("Location: ".$returnurl);

?>