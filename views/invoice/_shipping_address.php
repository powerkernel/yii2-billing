<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2018 Power Kernel
 */

/* @var $info[] */
?>
<?php if (!empty($info['company'])): ?>
    <div><strong><?= $info['company'] ?></strong></div>
<?php else: ?>
    <div><strong><?= $info['contact_name'] ?></strong></div>
<?php endif; ?>

<?php if (!empty($info['street_address_1'])): ?>
    <div><?= $info['street_address_1'] ?></div><?php endif; ?>
<?php if (!empty($info['street_address_2'])): ?>
    <div><?= $info['street_address_2'] ?></div><?php endif; ?>
<?php if (!empty($info['city'])): ?>
    <div><?= $info['city'] ?><?= !empty($info['state']) ? ', ' . $info['state'] : '' ?><?= !empty($info['zip_code']) ? ', ' . $info['zip_code'] : '' ?><?= !empty($info['country']) ? ', ' . $info['country'] : '' ?></div><?php endif; ?>
<?php if (!empty($info['phone'])): ?>
    <div><?= Yii::$app->getModule('billing')->t('Phone:') ?> <?= $info['phone'] ?></div>
<?php endif; ?>