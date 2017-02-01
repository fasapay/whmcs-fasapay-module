# FasaPay WHMCS Payment Gateway Module #

## Summary ##

This Payment Gateway modules allow you to integrate [FasaPay](https://www.fasapay.com) with the WHMCS
platform. Currently only support __USD__ as currency. 

## Install Guide ##

* Upload/copy
  * ```fasapay.php``` to ```modules/gateways/```
  * ```callback/fasapay.php``` to ```modules/gateways/callback/```
* Enable module in __Setup > Payment Gateways__
* __Important__: It is important that the gateway is not activated until step 1 is complete for either the third party or merchant gateway modules. This is because the type of module created is stored in the database upon activation.
* Enter your FasaPay Account Number, Store Name and Secret Word
* The Secret Word and Store Name are the same that you inputed in the __FasaPay Member Area > SCI > Store__

## SCI Store Setting ##
This Module required you to create Store at FasaPay Member Area > SCI > Store
[FasaPay Store Setting](https://www.fasapay.com/member/sci/store)

* fp_status_url
   http:://www.yourdomain.com/modules/gateways/callback/fasapay.php
* fp_status_method
   POST

## Minimum Requirements ##

For the latest WHMCS minimum system requirements, please refer to
http://docs.whmcs.com/System_Requirements
