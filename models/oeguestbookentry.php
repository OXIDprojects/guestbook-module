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
 * Guestbook entry object manager.
 * Loads available guestbook entries, performs some SQL queries.
 *
 */
class oeGuestBookEntry extends \OxidEsales\Eshop\Core\Model\BaseModel
{

    /**
     * skipped fields
     *
     * @var array containing fields
     */
    //to skip oxcreate we must change this field to 'CURRENT_TIMESTAMP'
    //protected $_aSkipSaveFields = array( 'oxcreate' );

    /**
     * Current class name
     *
     * @var string classname
     */
    protected $_sClassName = 'oeguestbookentry';

    /**
     * Class constructor, executes parent method parent::oxI18n().
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('oeguestbookentry');
    }

    /**
     * Calls parent::assign and assigns gb entry writer data
     *
     * @param array $dbRecord database record
     *
     * @return bool
     */
    public function assign($dbRecord)
    {
        $result = parent::assign($dbRecord);

        if (isset($this->oeguestbookentry__oxuserid) && $this->oeguestbookentry__oxuserid->value) {
            $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            $this->oxuser__oxfname = new \OxidEsales\Eshop\Core\Field($db->getOne("select oxfname from oxuser where oxid=" . $db->quote($this->oeguestbookentry__oxuserid->value)));
        }

        return $result;
    }

    /**
     * Inserts new guestbook entry. Returns true on success.
     *
     * @return bool
     */
    protected function _insert()
    {
        // set oxcreate
        $this->oeguestbookentry__oxcreate = new oxField(date('Y-m-d H:i:s', oxRegistry::get("oxUtilsDate")->getTime()));

        return parent::_insert();
    }

    /**
     * Loads guestbook entries returns them.
     *
     * @param integer $iStart           start for sql limit
     * @param integer $iNrofCatArticles nr of items per page
     * @param string  $sSortBy          order by
     *
     * @return array $oEntries guestbook entries
     */
    public function getAllEntries($iStart, $iNrofCatArticles, $sSortBy)
    {
        $myConfig = $this->getConfig();
        $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        // loading entries
        $sSelect = 'select oeguestbookentry.*, oxuser.oxfname,
                    `oxuser`.`oxusername` AS `author`, `oeguestbookentry`.`oxcreate` AS `date`
            from oeguestbookentry left join oxuser on oeguestbookentry.oxuserid = oxuser.oxid ';
        $sSelect .= 'where oxuser.oxid is not null and oeguestbookentry.oxshopid = "' . $myConfig->getShopId() . '" ';

        // setting GB entry view restirction rules
        if ($myConfig->getConfigParam('oeGuestBookModerate')) {
            $oUser = $this->getUser();
            $sSelect .= " and ( oeguestbookentry.oxactive = '1' ";
            $sSelect .= $oUser ? " or oeguestbookentry.oxuserid = " . $db->quote($oUser->getId()) : '';
            $sSelect .= " ) ";
        }

        // setting sort
        if ($sSortBy) {
            $sSelect .= "order by $sSortBy ";
        }


        $oEntries = oxNew('oxlist');
        $oEntries->init('oeguestbookentry');

        $oEntries->setSqlLimit($iStart, $iNrofCatArticles);
        $oEntries->selectString($sSelect);

        return $oEntries;
    }

    /**
     * Returns count of all entries.
     *
     * @return integer $iRecCnt
     */
    public function getEntryCount()
    {
        $myConfig = $this->getConfig();
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        // loading entries
        $sSelect = 'select count(*) from oeguestbookentry left join oxuser on oeguestbookentry.oxuserid = oxuser.oxid ';
        $sSelect .= 'where oxuser.oxid is not null and oeguestbookentry.oxshopid = "' . $myConfig->getShopId() . '" ';

        // setting GB entry view restirction rules
        if ($myConfig->getConfigParam('oeGuestBookModerate')) {
            $oUser = $this->getUser();
            $sSelect .= " and ( oeguestbookentry.oxactive = '1' ";
            $sSelect .= $oUser ? " or oeguestbookentry.oxuserid = " . $oDb->quote($oUser->getId()) : '';
            $sSelect .= " ) ";
        }

        // loading only if there is some data
        $iRecCnt = (int) $oDb->getOne($sSelect);

        return $iRecCnt;
    }

    /**
     * Method protects from massive message flooding. Max number of
     * posts per day is limited in Admin next to max number of posts
     * per page.
     *
     * @param string $sShopid shop`s OXID
     * @param string $sUserId user`s OXID
     *
     * @return  bool    result
     */
    public function floodProtection($sShopid = 0, $sUserId = null)
    {
        $result = true;
        if ($sUserId && $sShopid) {
            $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            $sToday = date('Y-m-d');
            $sSelect = "select count(*) from oeguestbookentry ";
            $sSelect .= "where oeguestbookentry.oxuserid = " . $oDb->quote($sUserId) . " and oeguestbookentry.oxshopid = " . $oDb->quote($sShopid) . " ";
            $sSelect .= "and oeguestbookentry.oxcreate >= '$sToday 00:00:00' and oeguestbookentry.oxcreate <= '$sToday 23:59:59' ";
            $iCnt = $oDb->getOne($sSelect);

            $myConfig = $this->getConfig();
            if ((!$myConfig->getConfigParam('oeGuestBookMaxGuestBookEntriesPerDay')) || ($iCnt < $myConfig->getConfigParam('oeGuestBookMaxGuestBookEntriesPerDay'))) {
                $result = false;
            }
        }
        return $result;
    }
}
