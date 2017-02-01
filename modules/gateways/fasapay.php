<?php
/**
 * WHMCS FasaPay Payment Gateway Module
 *
 * Payment Gateway modules allow you to integrate payment solutions with the
 * WHMCS platform.
 *
 * For more information, please refer to the online documentation.
 *
 * @see http://docs.whmcs.com/Gateway_Module_Developer_Docs
 *
 * @author FasaPay Development Team
 * @version 2.0
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related capabilities and
 * settings.
 *
 * @see http://docs.whmcs.com/Gateway_Module_Meta_Data_Parameters
 *
 * @return array
 */
function fasapay_MetaData()
{
    return array(
        'DisplayName' => 'FasaPay Payment Gateway',
        'APIVersion' => '1.1', // Use API Version 1.1
        'DisableLocalCredtCardInput' => true,
        'TokenisedStorage' => false,
    );
}

/**
 * Define gateway configuration options.
 *
 * The fields you define here determine the configuration options that are
 * presented to administrator users when activating and configuring your
 * payment gateway module for use.
 *
 * Supported field types include:
 * * text
 * * password
 * * yesno
 * * dropdown
 * * radio
 * * textarea
 *
 * Examples of each field type and their possible configuration parameters are
 * provided in the sample function below.
 *
 * @return array
 */
function fasapay_config()
{
    return array(
        // the friendly display name for a payment gateway should be
        // defined here for backwards compatibility
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'FasaPay',
        ),
        'fp_account' => array(
            'FriendlyName' => 'Fasapay Account',
            'Type' => 'text',
            'Size' => '8',
            'Default' => 'FP12345',
            'Description' => 'Enter your FasaPay Account Number Here',
        ),
        'fp_store' => array(
            'FriendlyName' => 'FasaPay Store Name',
            'Type' => 'text',
            'Size' => '25',
            'Default' => 'WHMCS Store',
            'Description' => 'Enter your Store name here. Please use the same name as the one defined in your FasaPay Member Area > SCI > Store ',
        ),
//        'fp_currency' => array(
//            'FriendlyName' => 'FasaPay Store Name',
//            'Type' => 'dropdown',
//           'Options' => array(
//                'IDR'=>'IDR',
//                'USD'=>'USD'
//            ),
//            'Default' => 'USD',
//            'Description' => 'Enter your Store name here. Please use the same name as the one defined in your FasaPay Member Area > SCI > Store ',
//        ),
        // a password field type allows for masked text input
        'fp_sword' => array(
            'FriendlyName' => 'FasaPay Store Secret Word',
            'Type' => 'password',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your Store Secret word here. Please use the same secret word as the one defined in your FasaPay Member Area > SCI > Store',
        ),

        // the yesno field type displays a single checkbox option
        'fp_sandbox' => array(
            'FriendlyName' => 'Sandbox Mode',
            'Type' => 'yesno',
            'Description' => 'Tick if you want to use https://sandbox.fasapay.com (Testing Only)',
        ),
    );
}

/**
 * Payment link.
 *
 * Required by third party payment gateway modules only.
 *
 * Defines the HTML output displayed on an invoice. Typically consists of an
 * HTML form that will take the user to the payment gateway endpoint.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see http://docs.whmcs.com/Payment_Gateway_Module_Parameters
 *
 * @return string
 */
function fasapay_link($params)
{
    // Gateway Configuration Parameters
    $fp_acc = $params['fp_account'];
    $fp_store = $params['fp_store'];
    $fp_sword = $params['fp_sword'];
    $fp_sandbox = $params['fp_sandbox'];

    // Invoice Parameters
    $invoiceId = $params['invoiceid'];
    $description = $params["description"];
    $amount = $params['amount'];
    $currencyCode = 'USD';

    // Client Parameters
    $firstname = $params['clientdetails']['firstname'];
    $lastname = $params['clientdetails']['lastname'];
    $email = $params['clientdetails']['email'];
    $address1 = $params['clientdetails']['address1'];
    $address2 = $params['clientdetails']['address2'];
    $city = $params['clientdetails']['city'];
    $state = $params['clientdetails']['state'];
    $postcode = $params['clientdetails']['postcode'];
    $country = $params['clientdetails']['country'];
    $phone = $params['clientdetails']['phonenumber'];

    // System Parameters
    $companyName = $params['companyname'];
    $systemUrl = $params['systemurl'];
    $returnUrl = $params['returnurl'];
    $langPayNow = $params['langpaynow'];
    $moduleDisplayName = $params['name'];
    $moduleName = $params['paymentmethod'];
    $whmcsVersion = $params['whmcsVersion'];

    if($fp_sandbox){
      $url = 'https://sandbox.fasapay.com/sci/';
    } else {
      $url = 'https://sci.fasapay.com';
    }

    $transid = $invoiceid."0".rand(1000, 9999);

    $postfields = array();
    $postfields['fp_acc'] = $fp_acc;
    $postfields['fp_store'] = $fp_store;
    $postfields['fp_item'] = $description;
    $postfields['fp_merchant_ref'] = $transid;
    $postfields['fp_amnt'] = $amount;
    $postfields['fp_currency'] = $currencyCode;
    $postfields['invoiceid'] = $invoiceId;
    $postfields['fp_status_url'] = $systemUrl . '/modules/gateways/callback/' . $moduleName . '.php';
    $postfields['fp_status_method'] = 'POST';
    $postfields['fp_success_url'] = $returnUrl;
    $postfields['fp_success_method'] = 'POST';
    $postfields['fp_fail_url'] = $returnUrl;
    $postfields['fp_fail_method'] = 'POST';

    $htmlOutput = '<form method="post" action="' . $url . '">';
    foreach ($postfields as $k => $v) {
        $htmlOutput .= '<input type="hidden" name="' . $k . '" value="' . $v . '" />';
    }
    $htmlOutput .= '<input type="submit" value="' . $langPayNow . '" />';
    $htmlOutput .= '</form>';

    return $htmlOutput;
}
