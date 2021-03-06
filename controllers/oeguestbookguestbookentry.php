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
 * Guest book entry manager class.
 * Manages guestbook entries, denies them, etc.
 */
class oeGuestBookGuestBookEntry extends \OxidEsales\Eshop\Application\Controller\FrontendController
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'page/guestbook/oeguestbookguestbook.tpl';

    /**
     * Guestbook form id, prevents double entry submit
     *
     * @var string
     */
    protected $_sGbFormId = null;

    /**
     * Method applies validation to entry and saves it to DB.
     * On error/success returns name of action to perform
     * (on error: "guestbookentry?error=x"", on success: "guestbook").
     *
     * @return string
     */
    public function saveEntry()
    {
        if (!oxRegistry::getSession()->checkSessionChallenge()) {
            return;
        }

        $request = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\Request::class);
        $sReviewText = trim(( string ) $request->getRequestParameter('rvw_txt'));
        $sShopId = $this->getConfig()->getShopId();
        $sUserId = oxRegistry::getSession()->getVariable('usr');

        // guest book`s entry is validated
        if (!$sUserId) {
            $sErrorMessage = 'OEGUESTBOOK_ERROR_MESSAGE_GUESTBOOK_ENTRY_ERR_LOGIN_TO_WRITE_ENTRY';
            oxRegistry::get("oxUtilsView")->addErrorToDisplay($sErrorMessage);

            //return to same page
            return;
        }

        if (!$sShopId) {
            $sErrorMessage = 'OEGUESTBOOK_ERROR_MESSAGE_GUESTBOOK_ENTRY_ERR_UNDEFINED_SHOP';
            oxRegistry::get("oxUtilsView")->addErrorToDisplay($sErrorMessage);

            return 'guestbookentry';
        }

        // empty entries validation
        if ('' == $sReviewText) {
            $sErrorMessage = 'OEGUESTBOOK_ERROR_MESSAGE_GUESTBOOK_ENTRY_ERR_REVIEW_CONTAINS_NO_TEXT';
            oxRegistry::get("oxUtilsView")->addErrorToDisplay($sErrorMessage);

            return 'guestbookentry';
        }

        // flood protection
        $oEntrie = oxNew('oeGuestBookEntry');
        if ($oEntrie->floodProtection($sShopId, $sUserId)) {
            $sErrorMessage = 'OEGUESTBOOK_ERROR_MESSAGE_GUESTBOOK_ENTRY_ERR_MAXIMUM_NUMBER_EXCEEDED';
            oxRegistry::get("oxUtilsView")->addErrorToDisplay($sErrorMessage);

            return 'guestbookentry';
        }

        // double click protection
        if ($this->canAcceptFormData()) {
            // here the guest book entry is saved
            $oEntry = oxNew('oeGuestBookEntry');
            $oEntry->oeguestbookentry__oxshopid = new oxField($sShopId);
            $oEntry->oeguestbookentry__oxuserid = new oxField($sUserId);
            $oEntry->oeguestbookentry__oxcontent = new oxField($sReviewText);
            $oEntry->save();
        }

        return 'guestbook';
    }
}
