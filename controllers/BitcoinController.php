<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace modernkernel\billing\controllers;

use common\components\BackendFilter;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use modernkernel\billing\models\Invoice;
use modernkernel\fontawesome\Icon;
use Yii;
use modernkernel\billing\models\BitcoinAddress;
use modernkernel\billing\models\BitcoinAddressSearch;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BitcoinController implements the CRUD actions for BitcoinAddress model.
 */
class BitcoinController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'backend' => [
                'class' => BackendFilter::className(),
                'actions' => [
                    'index', 'view', 'generate'
                ],
            ],

            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['admin'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['payment', 'check-payment'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all BitcoinAddress models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->view->title = Yii::t('billing', 'Bitcoin Address');
        $searchModel = new BitcoinAddressSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BitcoinAddress model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model=$this->findModel($id);

        /* metaData */
        //$title=$model->title;
        $this->view->title = Yii::t('billing', 'BitcoinAddress');
        //$keywords = $model->tags;
        //$description = $model->desc;
        //$metaTags[]=['name'=>'keywords', 'content'=>$keywords];
        //$metaTags[]=['name'=>'description', 'content'=>$description];
        /* Facebook */
        //$metaTags[]=['property' => 'og:title', 'content' => $title];
        //$metaTags[]=['property' => 'og:description', 'content' => $description];
        //$metaTags[]=['property' => 'og:type', 'content' => '']; // article, product, profile etc
        //$metaTags[]=['property' => 'og:image', 'content' => '']; //best 1200 x 630
        //$metaTags[]=['property' => 'og:url', 'content' => ''];
        //$metaTags[]=['property' => 'fb:app_id', 'content' => ''];
        //$metaTags[]=['property' => 'fb:admins', 'content' => ''];
        /* Twitter */
        //$metaTags[]=['name'=>'twitter:card', 'content'=>'summary_large_image']; // summary, summary_large_image, photo, gallery, product, app, player
        //$metaTags[]=['name'=>'twitter:site', 'content'=>Setting::getValue('twitterSite')];
        // Can skip b/c we already have og
        //$metaTags[]=['name'=>'twitter:title', 'content'=>$title];
        //$metaTags[]=['name'=>'twitter:description', 'content'=>$description];
        //$metaTags[]=['name'=>'twitter:image', 'content'=>''];
        //$metaTags[]=['name'=>'twitter:data1', 'content'=>''];
        //$metaTags[]=['name'=>'twitter:label1', 'content'=>''];
        //$metaTags[]=['name'=>'twitter:data2', 'content'=>''];
        //$metaTags[]=['name'=>'twitter:label2', 'content'=>''];
        /* jsonld */
        //$imageObject=$model->getImageObject();
        //$jsonLd = (object)[
        //    '@type'=>'Article',
        //    'http://schema.org/name' => $model->title,
        //    'http://schema.org/headline'=>$model->desc,
        //    'http://schema.org/articleBody'=>$model->content,
        //    'http://schema.org/dateCreated' => Yii::$app->formatter->asDate($model->created_at, 'php:c'),
        //    'http://schema.org/dateModified' => Yii::$app->formatter->asDate($model->updated_at, 'php:c'),
        //    'http://schema.org/datePublished' => Yii::$app->formatter->asDate($model->published_at, 'php:c'),
        //    'http://schema.org/url'=>Yii::$app->urlManager->createAbsoluteUrl($model->viewUrl),
        //    'http://schema.org/image'=>(object)[
        //        '@type'=>'ImageObject',
        //        'http://schema.org/url'=>$imageObject['url'],
        //        'http://schema.org/width'=>$imageObject['width'],
        //        'http://schema.org/height'=>$imageObject['height']
        //    ],
        //    'http://schema.org/author'=>(object)[
        //        '@type'=>'Person',
        //        'http://schema.org/name' => $model->author->fullname,
        //    ],
        //    'http://schema.org/publisher'=>(object)[
        //    '@type'=>'Organization',
        //    'http://schema.org/name'=>Yii::$app->name,
        //   'http://schema.org/logo'=>(object)[
        //        '@type'=>'ImageObject',
        //       'http://schema.org/url'=>Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->homeUrl.'/images/logo.png')
        //    ]
        //    ],
        //    'http://schema.org/mainEntityOfPage'=>(object)[
        //        '@type'=>'WebPage',
        //        '@id'=>Yii::$app->urlManager->createAbsoluteUrl($model->viewUrl)
        //    ]
        //];

        /* OK */
        //$data['title']=$title;
        //$data['metaTags']=$metaTags;
        //$data['jsonLd']=$jsonLd;
        //$this->registerMetaTagJsonLD($data);


        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * generate btc addresses
     * @return \yii\web\Response
     */
    public function actionGenerate(){
        BitcoinAddress::generate();
        return $this->redirect(['index']);
    }

    /**
     * check payment
     * @param $address
     */
    public function actionCheckPayment($address){
        $client = new Client(['baseUrl' => 'https://blockexplorer.com/api/']);
        $response = $client->get('addr/'.$address.'/balance')->send();
        $balance=$response->getContent();
        if($balance==0){
            echo Icon::widget(['icon'=>'refresh fa-spin']).' '.Yii::$app->getModule('billing')->t('Waiting payment...');
        } else {
            echo Html::beginTag('div', ['class'=>'alert alert-success']);
                echo Html::beginTag('h4');
                    echo Icon::widget(['icon'=>'check']);
                    echo '&nbsp;';
                    echo Yii::$app->getModule('billing')->t('Payment received!');
                echo Html::endTag('h4');
            echo Html::endTag('div');
        }

    }

    /**
     * bitcoin payment
     * @param $invoice
     * @return string
     */
    public function actionPayment($invoice){

        $this->layout = Yii::$app->view->theme->basePath . '/account.php';
        $this->view->title=Yii::$app->getModule('billing')->t('Pay with Bitcoin');
        $invoice=Invoice::findOne($invoice);
        /* validate invoice, convert invoice amt to BTC */

        /* check if this invoice have address assign, not older then 7 days  */

        /* get new address */
        $address=BitcoinAddress::find()->where(['status'=>BitcoinAddress::STATUS_NEW])->one();
        $bitcoin['amount']=0.000168;
        $bitcoin['address']=$address->address;
        $bitcoin['url']='bitcoin:'.$bitcoin['address'].'?amount='.$bitcoin['amount'];
        /* QR Code */
        $qrCode = new QrCode($bitcoin['url']); //format=>bitcoin:15AotgE3CPm3yuekKVeErbSj7YxnmifbY9?amount=0.006124
        $qrCode->setSize(500);
        $pngWriter = new PngWriter($qrCode);
        $bitcoin['base64QR']=base64_encode($pngWriter->writeString());



        return $this->render('payment', [
            'bitcoin'=>$bitcoin,
            'invoice'=>$invoice
        ]);
    }

    /**
     * Finds the BitcoinAddress model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return BitcoinAddress the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BitcoinAddress::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
