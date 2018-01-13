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
    <div><strong><?= $info['f_name'] ?> <?= $info['l_name'] ?></strong></div>
<?php endif; ?>

<?php if (!empty($info['address'])): ?>
    <div><?= $info['address'] ?></div><?php endif; ?>
<?php if (!empty($info['address2'])): ?>
    <div><?= $info['address2'] ?></div><?php endif; ?>
<?php if (!empty($info['city'])): ?>
    <div><?= $info['city'] ?><?= !empty($info['state']) ? ', ' . $info['state'] : '' ?><?= !empty($info['zip']) ? ', ' . $info['zip'] : '' ?><?= !empty($info['country']) ? ', ' . $info['country'] : '' ?></div><?php endif; ?>
<?php if (!empty($info['phone'])): ?>
    <div><?= Yii::$app->getModule('billing')->t('Phone:') ?> <?= $info['phone'] ?></div>
<?php endif; ?>
<div><?= Yii::$app->getModule('billing')->t('Email:') ?> <?= $info['email'] ?></div>
<?php if (!empty($info['tax_id'])): ?>
    <div><?= Yii::$app->getModule('billing')->t('Tax:') ?> <?= $info['tax_id'] ?></div>
<?php endif; ?>
