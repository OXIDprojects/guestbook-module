<?php
/**
 * This file is part of OXID eSales Guestbook module.
 *
 * OXID eSales GuestBook module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales Guestbook module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales Guestbook module.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category      module
 * @package       guestbook
 * @author        OXID eSales AG
 * @link          http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2016
 */

/**
 * Guestbook manager.
 * Sets template, that arranges two other templates ("oeguestbookadminguestbooklist.tpl"
 * and "oeguestbookadminguestbookmain.tpl") to frame.
 * Admin Menu: User information -> Guestbook.
 */
class oeGuestBookAdminGuestBook extends \OxidEsales\Eshop\Application\Controller\Admin\AdminController
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'oeguestbookadminguestbook.tpl';
}
