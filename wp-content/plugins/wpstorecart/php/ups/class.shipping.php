<?php
/*
Copyright (c) 2009, John Josef
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
Neither the name of the <ORGANIZATION> nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
/*
    Example of UPS

    loadHelper('class.shipping');
    $rate = new Ups;
    $rate->upsProduct("2DA"); // See upsProduct() function for codes
    $rate->origin("32825", "US"); // Use ISO country codes!
    $rate->dest("87540", "US"); // Use ISO country codes!
    $rate->rate("CC"); // See the rate() function for codes
    $rate->container("CP"); // See the container() function for codes
    $rate->weight("3");
    $rate->rescom("COM"); // See the rescom() function for codes
    $quote = $rate->getQuote();

    $rate->setSelectRate('2DA', $quote);
    $rate->upsProduct("3DS");
    $quote = $rate->getQuote();
    $rate->setSelectRate('3DS', $quote);
    $rate->upsProduct("GND");
    $quote = $rate->getQuote();
    $rate->setSelectRate('GND', $quote);

    echo $rate->displayRatesHtml("data[shipping-type]", "radio");
*/

  class Ups{
      private $productCodes = array(
          "1DM" => 'UPS Next Day Air Early AM',
          "1DA" => 'UPS Next Day Air',
          "1DP" => 'UPS Next Day Air Saver',
          "2DM" => 'UPS 2nd Day Air Early AM',
          "2DA" => 'UPS 2nd Day Air',
          "3DS" => 'UPS 3 Day Select',
          "GND" => 'UPS Ground',
          "STD" => 'UPS Canada',
          "XPR" => 'UPS Worldwide Express',
          "XDM" => 'UPS Worldwide Express Plus',
          "XPD" => 'UPS Worldwide Expedited',
      );
      private $selectedCodes = array();

    function upsProduct($prod){
    /*

     1DM == Next Day Air Early AM
     1DA == Next Day Air
     1DP == Next Day Air Saver
     2DM == 2nd Day Air Early AM
     2DA == 2nd Day Air
     3DS == 3 Day Select
     GND == Ground
     STD == Canada Standard
     XPR == Worldwide Express
     XDM == Worldwide Express Plus
     XPD == Worldwide Expedited

    */
      $this->upsProductCode = $prod;
    }

    function setSelectRate($rate, $price = 0)
    {
        $this->selectedCodes[$rate] = $price;
    }

    function displayRatesHtml($variable = 'shipping-type', $type = 'select', $selected = '')
    {
        if(!count($this->selectedCodes))
            $codeList = $this->productCodes;
        else
            $codeList = $this->selectedCodes;

        $output = '';
        switch($type)
        {
            case 'select':
                foreach($codeList as $code => $value)
                {
                    if(is_numeric($value) && $value > 0)
                        $showValue = '$' . sprintf('%.2f', $value);
                    else
                        $showValue = '';
                    $output .= '<option value="' . $code . '"' . ($code == $selected ? ' selected="selected"' : '') . '>' . $this->productCodes[$code] . ' ' . $showValue . '</option>';
                }
            break;
            case 'radio':
                foreach($codeList as $code => $value)
                {
                      if(is_numeric($value) && $value > 0)
                        $showValue = '$' . sprintf('%.2f', $value);
                    else
                        $showValue = '';
                    $output .= '<p><label class="radio"><input class="radio" type="radio" name="' . $variable . '" value="' . $code . '"' . ($code == $selected ? ' checked="checked"' : '') . ' /> ' . $this->productCodes[$code] . ' ' . $showValue . '</label></p>';
                }
            break;
        }
        return $output;
    }

    function origin($postal, $country){
      $this->originPostalCode = $postal;
      $this->originCountryCode = $country;
    }

    function dest($postal, $country){
      $this->destPostalCode = $postal;
          $this->destCountryCode = $country;
    }

    function rate($foo){
      switch($foo){
        case "RDP":
          $this->rateCode = "Regular+Daily+Pickup";
          break;
        case "OCA":
          $this->rateCode = "On+Call+Air";
          break;
        case "OTP":
          $this->rateCode = "One+Time+Pickup";
          break;
        case "LC":
          $this->rateCode = "Letter+Center";
          break;
        case "CC":
          $this->rateCode = "Customer+Counter";
          break;
      }
    }

    function container($foo){
          switch($foo){
        case "CP": // Customer Packaging
          $this->containerCode = "00";
          break;
        case "ULE": // UPS Letter Envelope
          $this->containerCode = "01";
          break;
        case "UT": // UPS Tube
          $this->containerCode = "03";
          break;
        case "UEB": // UPS Express Box
          $this->containerCode = "21";
          break;
        case "UW25": // UPS Worldwide 25 kilo
          $this->containerCode = "24";
          break;
        case "UW10": // UPS Worldwide 10 kilo
          $this->containerCode = "25";
          break;
      }
    }

    function weight($foo){
      $this->packageWeight = $foo;
    }

    function rescom($foo){
        switch($foo){

            case "RES": // Residential Address
                $this->resComCode = "1";
            break;
            case "COM": // Commercial Address
                $this->resComCode = "0";
            break;
            case "DET": // Let UPS Determine
                $this->resComCode = "2";
            break;
        }
    }

    function getQuote(){
        $url = "http://www.ups.com/using/services/rave/qcost_dss.cgi?AppVersion=1.2&AcceptUPSLicenseAgreement=yes&ResponseType=application/x-ups-rss&ActionCode=3&RateChart=" . $this->rateCode . "&DCISInd=0&SNDestinationInd1=0&SNDestinationInd2=0&ResidentialInd=" . $this->resComCode . "&PackagingType=" . $this->containerCode . "&ServiceLevelCode=" . $this->upsProductCode . "&ShipperPostalCode=" . $this->originPostalCode . "&ConsigneePostalCode=" . $this->destPostalCode . "&ConsigneeCountry=" . $this->destCountryCode . "&PackageActualWeight=" . $this->packageWeight . "&DeclaredValueInsurance=0";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch,CURLOPT_TIMEOUT, 5);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $result=curl_exec ($ch);
        curl_close($ch);
        $result = explode("%", $result);

        $errcode = $result[4];
        switch($errcode){
          case 3:
            $returnval = $result[14];
            break;
        }
        if(! $returnval) { $returnval = "error"; }
        return $returnval;
     }
  }

?>