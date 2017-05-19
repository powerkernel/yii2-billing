<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */
use common\Core;

$s = [
    /* Billing */
    ['key' => 'merchantName', 'value' => '', 'title' => 'Merchant Name', 'description' => 'Merchant name', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'string' => []])],
    ['key' => 'merchantAddress', 'value' => '', 'title' => 'Merchant Address', 'description' => 'Merchant address', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'string' => []])],
    ['key' => 'merchantCity', 'value' => '', 'title' => 'Merchant City', 'description' => 'Merchant city', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'string' => []])],
    ['key' => 'merchantState', 'value' => '', 'title' => 'Merchant State', 'description' => 'Merchant state', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
    ['key' => 'merchantZip', 'value' => '', 'title' => 'Merchant Zip', 'description' => 'Merchant zip code', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
    ['key' => 'merchantCountry', 'value' => '', 'title' => 'Merchant Country', 'description' => 'Merchant country', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required'=>[], 'string' => []])],
    ['key' => 'merchantPhone', 'value' => '', 'title' => 'Merchant Phone', 'description' => 'Merchant phone', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'string' => []])],
    ['key' => 'merchantEmail', 'value' => '', 'title' => 'Merchant Email', 'description' => 'Merchant email', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'email' => []])],

    /* Paypal */
    ['key' => 'paypalSandbox', 'value' => '', 'title' => 'Paypal Sandbox Mode', 'description' => 'Set yes to enable sandbox', 'group' => 'Billing', 'type' => 'dropDownList', 'data' => json_encode(Core::getYesNoOption()), 'default' => '1', 'rules' => json_encode(['required' => [], 'boolean' => []])],
    ['key' => 'paypalClientID', 'value' => '', 'title' => 'Paypal Client ID', 'description' => 'Live client ID', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
    ['key' => 'paypalSecret', 'value' => '', 'title' => 'Paypal Secret', 'description' => 'Live secret string', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
    ['key' => 'paypalSandboxClientID', 'value' => '', 'title' => 'Paypal Sandbox Client ID', 'description' => 'Sandbox client ID', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
    ['key' => 'paypalSandboxSecret', 'value' => '', 'title' => 'Paypal Sandbox Secret', 'description' => 'Sandbox secret string', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],

    /* Bitcoin */
    ['key' => 'btcPaymentTime', 'value' => '', 'title' => 'BTC Payment Timeout', 'description' => 'Payment timeout in seconds', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '900', 'rules' => json_encode(['integer' => ['min'=>300, 'max'=>3600]])],
    ['key' => 'btcWalletXPub', 'value' => '', 'title' => 'BTC Wallet xPub', 'description' => 'Wallet extended public key', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],

    /* order API */
    ['key' => 'currencyLayerAPI', 'value' => '', 'title' => 'Currency Layer API', 'description' => 'Access API key for currency conversion', 'group' => 'Billing', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],


];
return $s;