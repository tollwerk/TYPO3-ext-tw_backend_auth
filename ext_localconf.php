<?php

/*
 *  This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

if (!defined('TYPO3')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
    'tw_backend_auth',
    'auth',
    \Tollwerk\TwBackendAuth\Service\Authentication\AdminIpAuthService::class,
    [
        'title' => 'Restrict backend admins to IP',
        'description' => 'Restrict backend users with admin rights to given IP addresses',
        'subtype'     => 'authUserBE',
        'available'   => true,
        'priority'    => 60,
        'quality'     => 80,
        'os'          => '',
        'exec'        => '',
        'className'   => \Tollwerk\TwBackendAuth\Service\Authentication\AdminIpAuthService::class,

    ],
);
