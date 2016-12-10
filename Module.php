<?php

namespace modernkernel\billing;

use Yii;

/**
 * billing module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'modernkernel\billing\controllers';
    public $defaultRoute='info';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //\Yii::configure($this, require(__DIR__ . '/config.php'));
        $this->registerTranslations();
        $this->registerMailer();
    }

    /**
     * Config Mailer for the Module
     */
    public function registerMailer()
    {
        Yii::$app->mailer->setViewPath($this->basePath . '/mail');
        Yii::$app->mailer->htmlLayout = '@common/mail/layouts/html';
    }

    /**
     * Register translation for the Module
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['billing'] = [
            'class' => 'common\components\DbMessageSource',
            'on missingTranslation' => function ($event) {
                $event->sender->insertMissingTranslation($event);
            },
        ];
    }

    /**
     * Translate message
     * @param $message
     * @param array $params
     * @param null $language
     * @return mixed
     */
    public static function t($message, $params = [], $language = null)
    {
        return Yii::$app->getModule('billing')->translate($message, $params, $language);
    }

    /**
     * Translate message
     * @param $message
     * @param array $params
     * @param null $language
     * @return mixed
     */
    public static function translate($message, $params = [], $language = null)
    {
        return Yii::t('billing', $message, $params, $language);
    }
}
