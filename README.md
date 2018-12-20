Yii2 Billing
============
Yii2 Billing

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist powerkernel/yii2-billing "*"
```

or add

```
"powerkernel/yii2-billing": "*"
```

to the require section of your `composer.json` file, then run

```
php yii mongodb-migrate --migrationPath=@vendor/powerkernel/yii2-billing/migrations/ --migrationCollection=billing_migration
```