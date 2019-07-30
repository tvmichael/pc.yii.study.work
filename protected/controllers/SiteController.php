<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
//
use app\models\Course;
use app\models\Currency;
use app\models\Exchange;
use app\models\ExchangeCurrency;
//
use app\models\PlaypayForm;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays Map.
     *
     * @return string
     */
    public function actionMap($map='')
    {
        return $this->render('/map/'.$map);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays Playpay page.
     *
     *  $cE - current exchange
     *  $cC - current currency
     *
     * @return string
     */
    public function actionPlaypay($cE = null, $cC = null)
    {
        $model = new PlaypayForm();

        $course = Course::find()->select(['ccy', 'base_ccy', 'buy', 'sale'])->asArray()->all();
        $currency = Currency::find()->asArray()->All();
        $exchange = Exchange::find()->asArray()->all();
        $exchangeCurrency = ExchangeCurrency::find()->asArray()->all();

        // поточний обмін
        if( !in_array($cE, ['1-2', '2-1']) ) $cE = '1-2';
        $cE = explode('-', $cE);

        // формуємо варіанти поточного обміну валют -  1)UAH, 2)USD,RUR
        $variantExchange = [];
        foreach ($cE as $e){
            if( empty($variantExchange[$e]) )$variantExchange[$e] = [];
            foreach ($exchangeCurrency as $ec){
                if ($e == $ec['exchangeId']){
                    foreach ($currency as $c){
                       if($ec['currencyId'] == $c['id']){ array_push($variantExchange[$e], $c['name']);}
                    }
                }
            }
        };

        // поточні валюти для обміну
        function makeC($cE, $variantExchange){
            $cC = [];
            foreach ($cE as $c)
                array_push($cC, $variantExchange[$c][0]);
            return $cC;
        };
        $cC = explode('-', $cC);
        if(count($cC) == 2 ) {
            $i = 0;
            foreach ($cE as $key => $e)
                if (in_array($cC[$key], $variantExchange[$e])) $i++;
            if ($i != 2) $cC = makeC($cE, $variantExchange);
        } else $cC = makeC($cE, $variantExchange);


        // курс валют приват24
        //$jsonurl = "https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5";
        //$json = trim(file_get_contents($jsonurl));
        //$course = json_decode($json, true);


        return $this->render('playpay', [
            'course' =>$course,
            'currency' => $currency,
            'exchange' => $exchange,
            'exchangeCurrency' => $exchangeCurrency,
            'cE' => $cE,
            'cC' => $cC,
            'variantExchange' => $variantExchange,
            'model' => $model,
        ]);
    }

    /***
     * Displays Exchange page.
     *
     *
     * @return string
     */
    public function actionExchange()
    {

        if(Yii::$app->request->isAjax){
            $data= Yii::$app->request->post();
        }

        sleep(1);


        $dataJson = [
            'success' => false,
            'data' =>
                [
                'cardFrom' => $data['cardFrom'],
                'cardTo' => $data['cardTo'],
                'sumFrom' => $data['sumFrom'],
                'sumTo' => $data['sumTo'],
                'cFrom' => $data['cFrom'],
                'cTo' => $data['cTo'],
                'curs' => $data['curs'],
                ]
        ];

        return $this->asJson($dataJson);
        //return $this->render('exchange');
    }

    public function actionPopupWindow(){
        return $this->render('popup-window');
    }
}
