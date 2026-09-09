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

namespace Tollwerk\TwBackendAuth\Utility;

class IpUtility
{
    /**
     * Get the lower and upper IP range limits for a given CIDR notation
     *
     * @param string $cidr CIDR notation like '10.0.0.0/24'
     *
     * @return array
     */
    public static function cidrToRange(string $cidr): array
    {
            $range = [];
            $cidr = explode('/', $cidr);
            $range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
            $range[1] = long2ip((ip2long($range[0])) + (1 << (32 - (int)$cidr[1])) - 1);

            return $range;
    }

    /**
     * Check if an IP address is inside a given IP range
     *
     * @param string $ip         IP address to check
     * @param string $lowerLimit Lower IP range limit
     * @param string $upperLimit Upper IP range limit
     *
     * @return bool
     */
    public static function isInRange(string $ip, string $lowerLimit, string $upperLimit): bool
    {
        $ipLong = ip2long($ip);
        return ip2long($lowerLimit) <= $ipLong && $ipLong <= ip2long($upperLimit);
    }
}
