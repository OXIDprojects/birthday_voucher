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
 */
 
class birthday_mail extends birthday_mail_parent
{
	protected $_sBirthdayEmailTemplate = "birthday_mail_html.tpl";
	protected $_sBirthdayEmailPlainTemplate = "birthday_mail_plain.tpl";

  public function sendBirthdayEmailToUser($oUser, $oVoucher, $sVoucherNo)
  {
      $myConfig = $this->getConfig();

      $oShop = $this->_getShop();

			$bvoucherconfig = oxConfig::getInstance()->getConfigParam( 'aBirthdayVoucherConfig' );
      // cleanup
      $this->_clearMailer();

      $oLang = oxLang::getInstance();
      $iOrderLang = $oLang->getObjectTplLanguage();

      // if running shop language is different from admin lang. set in config
      // we have to load shop in config language
      if ( $oShop->getLanguage() != $iOrderLang ) {
          $oShop = $this->_getShop( $iOrderLang );
      }

		$this->setSmtp( $oShop );
		$ViewConf = $this->getViewConfig();
		
		// create messages
		$oSmarty = $this->_getSmarty();
		$oSmarty->assign( "charset", $oLang->translateString("charset"));
		$oSmarty->assign( "shop", $oShop );
		$oSmarty->assign( "oViewConf", $oShop );
		$oSmarty->assign( "user", $oUser );
		$oSmarty->assign( "voucher", $oVoucher );
		$oSmarty->assign( "voucherno", $sVoucherNo);
		$aCurrencies = $myConfig->getCurrencyArray();
		$oSmarty->assign( "currency", $aCurrencies[0] );
		$oSmarty->assign( "imageurl", $ViewConf->getNoSslImageDir( false )); // geändert
		$oSmarty->assign( "fVouchermin", oxLang::getInstance()->formatCurrency($oVoucher->oxvoucherseries__oxminimumvalue->value)); //geändert
		$oSmarty->assign( "fVoucherDiscount", oxLang::getInstance()->formatCurrency($oVoucher->oxvoucherseries__oxdiscount->value)); //geändert
		$oVoucherBegin = new DateTime($oVoucher->oxvoucherseries__oxbegindate->value);
		$oVoucherEnd = new DateTime($oVoucher->oxvoucherseries__oxenddate->value);
		$oSmarty->assign( "fVoucherBegin", $oVoucherBegin->format('d.m.Y'));
		$oSmarty->assign( "fVoucherEnd", $oVoucherEnd->format('d.m.Y'));	 

		$oOutputProcessor = oxNew( "oxoutput" );
		$aNewSmartyArray = $oOutputProcessor->processViewArray($oSmarty->get_template_vars(), "birthday_mail");
		foreach ($aNewSmartyArray as $key => $val)
		    $oSmarty->assign( $key, $val );
		
		$this->setBody( $oSmarty->fetch( $myConfig->getTemplatePath( $this->_sBirthdayEmailTemplate, false ) ) );
		$this->setAltBody( $oSmarty->fetch( $myConfig->getTemplatePath( $this->_sBirthdayEmailPlainTemplate, false ) ) );
		$sSubject = $bvoucherconfig["mailsubject"];
		$sRecipient = $oUser->oxuser__oxusername;
		$sFullName_Reci = $oUser->oxuser__oxfname->value . " " . $oUser->oxuser__oxlname->value;
		$sFullname_Owner = $oShop->oxshops__oxname->value;
		$this->setFrom( $oShop->oxshops__oxorderemail->value, $sFullName_Owner );
		$this->setSubject( $sSubject );
		$this->setRecipient( $sRecipient, $sFullname_Reci);

		$blSuccess = $this->send();

    return $blSuccess;

    }
}