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
use Yii;
use modernkernel\billing\models\BitcoinAddress;
use modernkernel\billing\models\BitcoinAddressSearch;
use yii\filters\AccessControl;
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
        $this->view->title = Yii::t('billing', 'Bitcoin Address');
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
     * @param $address
     * @return bool
     */
    public function actionCheckPayment($address){
        $btcAddr=BitcoinAddress::find()->where([
            'address'=>$address,
            'id_account'=>Yii::$app->user->id,
            'status'=>BitcoinAddress::STATUS_USED
        ])->one();
        if($btcAddr){
            return $btcAddr->checkPayment();
        }
        return false;
    }

    /**
     * bitcoin payment
     * @param $s string
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionPayment($s){

        $this->layout = Yii::$app->view->theme->basePath . '/account.php';
        $this->view->title=Yii::$app->getModule('billing')->t('Pay with Bitcoin');
        $session=Yii::$app->session[$s];
        $invoice=Invoice::findOne($session['invoice']);
        $address=BitcoinAddress::findOne($session['address']);
        $time=$session['time'];
        if(empty($invoice) or empty($address) or empty($time)) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        /* if expired, return */
        $now=time();
        $point=$now-(integer)\modernkernel\billing\models\Setting::getValue('btcPaymentTime');
        if($time<$point){
            return $this->redirect($invoice->getInvoiceUrl());
        }

        /* if invoice paid, return */
        if($invoice->status!=Invoice::STATUS_PENDING){
            return $this->redirect($invoice->getInvoiceUrl());
        }

        /* set btc info */
        //$btc=0.095; // manual BTC amount
        $bitcoin['amount']=$address->request_balance;;
        $bitcoin['address']=$address->address;
        $bitcoin['date']=$address->updated_at;
        $bitcoin['url']='bitcoin:'.$bitcoin['address'].'?amount='.$bitcoin['amount'];
        /* QR Code */
        $qrCode = new QrCode($bitcoin['url']);
        $qrCode->setSize(500);
        $pngWriter = new PngWriter();
        $bitcoin['base64QR']=base64_encode($pngWriter->writeString($qrCode));

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
