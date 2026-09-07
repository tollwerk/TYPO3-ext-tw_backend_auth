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

namespace Tollwerk\TwBackendAuth\Service\Authentication;

use TYPO3\CMS\Core\Authentication\AbstractAuthenticationService;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AdminIpAuthService extends AbstractAuthenticationService
{
    public function authUser(array $user): int
    {
        // Get extension configuration.
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $configuration = $extensionConfiguration->get('tw_backend_auth');

        // Check admin users.
        if (!empty($user['admin']) && $user['admin'] >= 1) {
            if (empty($configuration['allowed_ips']['admin_users']) || $configuration['allowed_ips']['admin_users'] === '*') {
                // Allow further authentication.
                return 100;
            }

            // Grant access if users remote IP address matches one of the given allowed addresses.
            $allowedIps = GeneralUtility::trimExplode(',', $configuration['allowed_ips']['admin_users']);
            foreach($allowedIps as $allowedIp) {
                if ($_SERVER['REMOTE_ADDR'] === $allowedIp) {
                    // Allow further authentication.
                    return 100;
                }
            }
        }

        // Check regular users.
        if (empty($user['admin'])) {
            if (empty($configuration['allowed_ips']['regular_users']) || $configuration['allowed_ips']['regular_users'] === '*') {
                // Allow further authentication.
                return 100;
            }

            // Grant access if users remote IP address matches one of the given allowed addresses.
            $allowedIps = GeneralUtility::trimExplode(',', $configuration['allowed_ips']['regular_users']);
            foreach($allowedIps as $allowedIp) {
                if ($_SERVER['REMOTE_ADDR'] === $allowedIp) {
                    // Allow further authentication.
                    return 100;
                }
            }
        }

        // Deny authentication.
        return 0;
    }
}
