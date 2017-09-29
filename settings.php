<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */
use common\Core;

$s = [
    /* Billing */
    ['key' => 'merchantName', 'value' => '', 'title' => 'Merchant Name', 'description' => 'Merchant name', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'string' => []])],
    ['key' => 'merchantAddress', 'value' => '', 'title' => 'Merchant Address', 'description' => 'Merchant address', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'string' => []])],
    ['key' => 'merchantCity', 'value' => '', 'title' => 'Merchant City', 'description' => 'Merchant city', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'string' => []])],
    ['key' => 'merchantState', 'value' => '', 'title' => 'Merchant State', 'description' => 'Merchant state', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
    ['key' => 'merchantZip', 'value' => '', 'title' => 'Merchant Zip', 'description' => 'Merchant zip code', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
    ['key' => 'merchantCountry', 'value' => '', 'title' => 'Merchant Country', 'description' => 'Merchant country', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required'=>[], 'string' => []])],
    ['key' => 'merchantPhone', 'value' => '', 'title' => 'Merchant Phone', 'description' => 'Merchant phone', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'string' => []])],
    ['key' => 'merchantEmail', 'value' => '', 'title' => 'Merchant Email', 'description' => 'Merchant email', 'group' => 'Merchant', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'email' => []])],

    /* Paypal */
    ['key' => 'paypalSandbox', 'value' => '', 'title' => 'Paypal Sandbox Mode', 'description' => 'Set yes to enable sandbox', 'group' => 'Paypal', 'type' => 'dropDownList', 'data' => json_encode(Core::getYesNoOption()), 'default' => '1', 'rules' => json_encode(['required' => [], 'boolean' => []])],
    ['key' => 'paypalClientID', 'value' => '', 'title' => 'Paypal Client ID', 'description' => 'Live client ID', 'group' => 'Paypal', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
    ['key' => 'paypalSecret', 'value' => '', 'title' => 'Paypal Secret', 'description' => 'Live secret string', 'group' => 'Paypal', 'type' => 'passwordInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
    ['key' => 'paypalSandboxClientID', 'value' => '', 'title' => 'Paypal Sandbox Client ID', 'description' => 'Sandbox client ID', 'group' => 'Paypal', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],
    ['key' => 'paypalSandboxSecret', 'value' => '', 'title' => 'Paypal Sandbox Secret', 'description' => 'Sandbox secret string', 'group' => 'Paypal', 'type' => 'passwordInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],

    /* Bitcoin */
    ['key' => 'btcPaymentTime', 'value' => '', 'title' => 'BTC Payment Timeout', 'description' => 'Payment timeout in seconds', 'group' => 'Bitcoin', 'type' => 'textInput', 'data' => '[]', 'default' => '900', 'rules' => json_encode(['integer' => ['min'=>300, 'max'=>3600]])],
    ['key' => 'btcWalletXPub', 'value' => '', 'title' => 'BTC Wallet xPub', 'description' => 'Wallet extended public key', 'group' => 'Bitcoin', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],

    /* order API */
    ['key' => 'currencyLayerAPI', 'value' => '', 'title' => 'Currency Layer API', 'description' => 'Access API key for currency conversion', 'group' => 'MISC', 'type' => 'passwordInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['string' => []])],


];
return [];