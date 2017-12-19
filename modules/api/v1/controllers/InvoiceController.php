<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace powerkernel\billing\modules\api\v1\controllers;


use powerkernel\billing\models\Invoice;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\VerbFilter;


/**
 * Class InvoiceController
 * @package powerkernel\billing\modules\api\v1\controllers
 */
class InvoiceController extends \yii\rest\Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'vcb-sms-check' => ['POST'],
            ],
        ];


        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
        ];
        $behaviors['access'] =
            [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['admin'],
                        'allow' => true,
                    ],
                ],
            ];

        return $behaviors;
    }


    /**
     * check sms VCB account in VND
     * test: curl -k -i -H "Accept:application/json" -H "Authorization: Basic base64 token" url
     */
    public function actionVcbSmsCheck()
    {
        if (!empty(Yii::$app->request->post('sms'))) {
            $sms = \Yii::$app->request->post('sms');
            /* check invoice id */
            $id = null;
            $model = null;
            $unpaidInvoices = Invoice::find()->where([
                'currency' => 'VND',
                'status' => Invoice::STATUS_PENDING,
            ])->all();
            foreach ($unpaidInvoices as $invoice) {
                if (preg_match("/({$invoice->id_invoice})/", $sms, $invoiceId)) {
                    $id = $invoice->id_invoice;
                    $model = $invoice;
                    break;
                }
            }

            if ($id != null) {
                $amount = number_format($model->total, 0, '.', $thousands_sep = ",");
                /* amount */
                if (!preg_match('/(\+' . $amount . ' VND)/', $sms, $creditAmt)) {
                    return [
                        'success' => false,
                        'data' => [
                            'message' => 'Wrong amount in WIRE Transfer'
                        ]
                    ];
                }

                /* set invoice paid if we are here*/
                $model->status = Invoice::STATUS_PAID;
                $model->payment_method = 'Bank Wire';
                //$model->save();

                return [
                    'success' => true,
                    'data' => [
                        'invoice' => $model->id_invoice,
                        'total' => \Yii::$app->formatter->asDecimal($model->total),
                        'bank' => $creditAmt[1],
                        'save' => $model->save()
                    ]
                ];
            }
            return [
                'success' => false,
                'data' => [
                    'message' => 'Cannot find invoice ID'
                ]
            ];
        }
        return [
            'success' => false,
            'data' => [
                'message' => 'Missing required parameters: sms'
            ]
        ];
    }
}


