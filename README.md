# tollwerk Backend Authentication for TYPO3

[![TYPO3 12](https://img.shields.io/badge/TYPO3-12-green.svg)](https://get.typo3.org/version/12)
[![TYPO3 13](https://img.shields.io/badge/TYPO3-13-green.svg)](https://get.typo3.org/version/13)
[![GNU General Public License](https://img.shields.io/badge/License-GPL%203%20or%20later-lightgray.svg)](https://www.gnu.org/licenses/gpl-3.0.en.html)

Extends the TYPO3 backend authentication to restrict admins and regular backend users to given IP addresses.

## Installation

1. Install the extension with composer.
    ```
    composer require tollwerk/tw-backend-auth
    ```

## Usage

Inside the TYPO3 Install Tool, go to _Maintenace > Extension Configuration > tw_backend_auth_ to restrict admin or
regular backend users to one or multiple IP addresses.

The default value for both is `*`, which means that there are no restrictions.
