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

use Tollwerk\TwBackendAuth\Utility\IpUtility;
use TYPO3\CMS\Core\Authentication\AbstractAuthenticationService;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class AdminIpAuthService extends AbstractAuthenticationService
{
    /**
     * @param string $allowedIpAdresses String with allowed IP addresses like '10.0.0.1' or '10.0.0.1, 127.0.0.1' or '123.123.123.0/24'
     *
     * @return bool
     */
    public function checkAllowedIpAddresses(string $allowedIpAdresses): bool
    {
        // Check if allowed IP addresses are empty or '*' (= "all").
        if (empty($allowedIpAdresses) || $allowedIpAdresses === '*') {
            // Allow further authentication.
            return true;
        }

        // Grant access if users remote IP address matches one of the given allowed addresses.
        $allowedIps = GeneralUtility::trimExplode(',', $allowedIpAdresses);
        foreach ($allowedIps as $allowedIp) {
            // If current $allowedIp is a CIDR notation, check if client IP is inside the given range.
            // See https://en.wikipedia.org/wiki/Classless_Inter-Domain_Routing.
            if (strpos($allowedIp, '/') !== false) {
                $cidrRange = IpUtility::cidrToRange($allowedIp);
                $isInRange = IpUtility::isInRange($_SERVER['REMOTE_ADDR'], $cidrRange[0], $cidrRange[1]);
                if ($isInRange) {
                    // Allow further authentication.
                    return true;
                }
            }

            // If $allowedIp is a single IP address, check if client IP is this IP address.
            if ($_SERVER['REMOTE_ADDR'] === $allowedIp) {
                // Allow further authentication.
                return true;
            }
        }

        // Deny authentication.
        return false;
    }

    /**
     * @param array $user TYPO3 Backend User record
     *
     * @return int
     *
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     */
    public function authUser(array $user): int
    {
        // Get extension configuration.
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $configuration = $extensionConfiguration->get('tw_backend_auth');

        // Check admin users.
        if (!empty($user['admin']) && !empty($configuration['allowed_ips']['admin_users'])) {
            DebuggerUtility::var_dump('check admin..');
            return $this->checkAllowedIpAddresses($configuration['allowed_ips']['admin_users']);
        }

        // Check regular users.
        if (empty($user['admin']) && !empty($configuration['allowed_ips']['regular_users'])) {
            return $this->checkAllowedIpAddresses($configuration['allowed_ips']['regular_users']);
        }

        // Allow further authentication.
        return 100;
    }
}
