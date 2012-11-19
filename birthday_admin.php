<?php
/**
 *    This file is part of the module Birthday Voucher for OXID eShop Community Edition.
 *
 *    The module Birthday Voucher for OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    The module Birthday Voucher for OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.stefan-korn.de 
 */
class birthday_admin extends birthday_admin_parent
{
  
  protected $_sThisTemplate = 'birthday_interface.tpl';
	protected $_aBvoucherlog = array();
		
	public function render()
    {
		$aBvoucherconfig = oxConfig::getInstance()->getConfigParam( 'aBirthdayVoucherConfig' );
		$sBvouchersentdate = oxConfig::getInstance()->getConfigParam( 'sBirthdayVoucherSent' );
		$this->_aViewData["bvoucherconfig"] = $aBvoucherconfig;
		$this->_aViewData["bvouchersentdate"] = $sBvouchersentdate;
		$this->_aViewData["bvoucherlogmail"] = $this->_aBvoucherlog;
		$this->_aViewData["bvouchermailrecipients"] = $this->get_birthdaymail_recipients();
		$this->_aViewData["bvouchertoday"] = $this->get_today_date();		
		
    parent::render();
		return $this->_sThisTemplate;
	}
	
	public function get_birthday_childs($onetimesale)
	{	
  	$today_date = getdate();	
  	if ($today_date["mon"] < 10)
  		$birthmonth = "0" . $today_date["mon"];
  	else
  		$birthmonth = $today_date["mon"];
  	if ($today_date["mday"] < 10)
  		$birthday = "0" . $today_date["mday"];
  	else
  		$birthday = $today_date["mday"];		
  	$sSql = "SELECT oxid FROM oxuser WHERE OXBIRTHDATE LIKE '%-" . $birthmonth . "-" . $birthday . "'";
  	if ($onetimesale == 'false' )
  		$sSql .= "AND OXPASSWORD <> ''";
  	
  	$rs = oxDb::getDb(2)->SelectLimit( $sSql);
  	return $rs;	
	}
	
	public function check_newsletter($useroxid)
	{
		$oDb = oxDb::getDb();
		$sSQL = "SELECT COUNT(*) FROM oxnewssubscribed WHERE OXUSERID = '" . $useroxid . "' AND OXDBOPTIN = 1";
		return $oDb->getOne($sSQL);	
	}
	
	public function get_voucher_details()
	{
  	$oVoucherSeries = oxNew('oxvoucherserie');
  	$sSql = "SELECT oxid FROM oxvoucherseries WHERE OXSERIENR = 'Geburtstagsgutschein'";
  	$rs = oxDb::getDb(2)->SelectLimit( $sSql);
  	 while (!$rs->EOF) {
  		
  		$oVoucherSeries->load($rs->fields['oxid']);
  		$rs->MoveNext();
  		
  		}
	 return $oVoucherSeries;
	}
	
	public function create_voucher($oUser, $serie)
	{
    $oNewVoucher = oxNew( "oxvoucher" );
    $oNewVoucher->oxvouchers__oxvoucherserieid = new oxField( $serie );
    $bvoucherconfig = oxConfig::getInstance()->getConfigParam( 'aBirthdayVoucherConfig' );
    $aActyear = getdate();
    $sVoucherNo = $bvoucherconfig["prefix"]."-".$aActyear["year"]."-".$oUser->oxuser__oxcustnr->value.substr($oUser->oxuser__oxfname->value, 1, 2).substr($oUser->oxuser__oxlname->value, 2, 2);
    $oNewVoucher->oxvouchers__oxvouchernr = new oxField( $sVoucherNo );
    $oNewVoucher->save();
    
    return $sVoucherNo;
	}
	
	public function get_today_date()
	{
  	$bv_today = getdate();
  	return $bv_today["year"] . "-" . $bv_today["mon"] . "-" . $bv_today["mday"];	
	}
	
	public function send_birthdaymail()
	{	
	
  $bv_date = $this->get_today_date();
  	
  if (oxConfig::getInstance()->getConfigParam( 'sBirthdayVoucherSent' ) != $bv_date)
    {
    
		$oVoucherSeries = $this->get_voucher_details();
    $aUsers = $this->get_birthdaymail_recipients();
		foreach ($aUsers as $oUser)
			{
				$oEmail = oxNew( 'oxemail' );
				$oEmail->sendBirthdayEmailToUser($oUser, $oVoucherSeries, $this->create_voucher($oUser, $oVoucherSeries->oxvoucherseries__oxid->value));
			}
		$this->_aBvoucherlog = $aUsers;
		oxConfig::getInstance()->saveShopConfVar( 'str', 'sBirthdayVoucherSent', $bv_date );
		}
  	
  
  }
	
	public function get_birthdaymail_recipients()
	{
	$aUsers = array();
	$bvoucherconfig = oxConfig::getInstance()->getConfigParam( 'aBirthdayVoucherConfig' );
	$rs = $this->get_birthday_childs($bvoucherconfig["onetimesale"]);
	while (!$rs->EOF) {
			if ($bvoucherconfig["onlynewsletter"] == 'false' || $this->check_newsletter($rs->fields['oxid']) > 0)
			{
  			$oUser= oxNew( 'oxuser' );
  			$oUser->load($rs->fields['oxid']);			
  			$aUsers[$i] = $oUser;
  			$i++;
			}
      $rs->MoveNext();
	}
	return $aUsers;
	}
	
	public function birthday_edit_config()
	{
		$bvoucherconfig = array();
		$bvoucherconfig["method"] = oxConfig::getParameter( "bvoucher_method");
		if (oxConfig::getParameter( "bvoucher_onetimesale"))
			$bvoucherconfig["onetimesale"] = "true";
		else
			$bvoucherconfig["onetimesale"] = "false";
		if (oxConfig::getParameter( "bvoucher_onlynewsletter"))
			$bvoucherconfig["onlynewsletter"] = "true";
		else
			$bvoucherconfig["onlynewsletter"] = "false";
		  $bvoucherconfig["prefix"] = oxConfig::getParameter( "bvoucher_prefix");
		  $bvoucherconfig["mailsubject"] = oxConfig::getParameter( "bvoucher_mailsubject");
		  oxConfig::getInstance()->saveShopConfVar( 'arr', 'aBirthdayVoucherConfig', $bvoucherconfig );
		
	}
}