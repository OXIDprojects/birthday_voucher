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
class birthday_start extends birthday_start_parent 
{
       

  public function render()
  {
			
    if ($this->birthday_checkmethod())
		{
  		$bdadmin = oxnew("birthday_admin");		
  		$this->_aViewData['bvdate']  = $bdadmin->send_birthdaymail();
		}
		
		parent::render();
    return $this->_sThisTemplate;
  }
    
	protected function birthday_checkmethod() {
		$bvoucherconfig = oxConfig::getInstance()->getConfigParam( 'aBirthdayVoucherConfig' );
		if ($bvoucherconfig['method'] == "auto")
			return true;
		else
			return false;
	}
}