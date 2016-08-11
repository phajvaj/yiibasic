<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends MainController
{
    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'access' => [
                'class' => AccessControl::ClassName(),
                'only' => ['logout', 'signup'],
                'rules' => [
                  [
                      'actions' => ['signup'],
                      'allow' => true,
                      'roles' => ['?'],
                  ],
                  [
                      'actions' => ['logout'],
                      'allow' => true,
                      'roles' => ['@'],
                  ],
                ],
            ],
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
        if(Yii::$app->user->isGuest){#แขกเข้ามาหรือยังไม่ได้ login
            /*$model = new LoginForm();            
            return $this->render('login', [
                'model' => $model,
            ]);*/
            return $this->redirect(['site/login']);
            #$this->redirect(\Yii::$app->urlManager->createUrl('site/login'));
        }            
            
        $grand = $this->grander;
        
        //----------------------จุดบริการ
        $sql = "SELECT o.ovstost,s.`name` as ostname,
                SUM(IF(v.sex='1',1,0)) as sm,
                SUM(IF(v.sex='2',1,0)) as sf,
                COUNT(o.ovstost) as vsu
                FROM ovst as o
                LEFT OUTER JOIN ovstost as s ON(o.ovstost=s.ovstost)
                LEFT OUTER JOIN vn_stat as v ON(o.vn=v.vn)
                WHERE o.vstdate = CURDATE()
                GROUP BY o.ovstost;";
        //ดึงข้อมูล
        try{
            $qc = \Yii::$app->db->createCommand($sql)->queryAll();
        }catch(\yii\db\Exception $e){
            throw new \yii\web\ConflicHttpException("กรุณาตรวจสอบคำสั่ง SQL => <per>{$sql}</per>");
        }
        //นำข้อมูลไปใส่ใน Provider
        $data1 = new \yii\data\ArrayDataProvider([            
            'allModels' => $qc,
            'pagination' => FALSE,
        ]);
        
        //----------------------การมาของผู้ป่วย
        $sql = "SELECT 
                o.ovstist,s.`name` as ostname,
                SUM(IF(v.sex='1',1,0)) as sm,
                SUM(IF(v.sex='2',1,0)) as sf,
                COUNT(o.ovstist) as vsu
                FROM ovst as o
                LEFT OUTER JOIN ovstist as s ON(o.ovstist=s.ovstist)
				LEFT OUTER JOIN vn_stat as v ON(o.vn=v.vn)
                WHERE o.vstdate = CURDATE()
                GROUP BY o.ovstist";
        //ดึงข้อมูล
        try{
            $qc = \Yii::$app->db->createCommand($sql)->queryAll();
        }catch(\yii\db\Exception $e){
            throw new \yii\web\ConflicHttpException("กรุณาตรวจสอบคำสั่ง SQL => <per>{$sql}</per>");
        }
        //นำข้อมูลไปใส่ใน Provider
        $data2 = new \yii\data\ArrayDataProvider([            
            'allModels' => $qc,
            'pagination' => FALSE,
        ]);
        
        //----------------------ER ระดับความรุนแรง
        $sql = "SELECT
                l.er_emergency_level_name as levname,
                SUM(IF(v.sex='1',1,0)) as sm,
                SUM(IF(v.sex='2',1,0)) as sf,
                COUNT(e.vn) as vsu
                FROM er_regist as e
                LEFT OUTER JOIN er_emergency_level as l ON(e.er_emergency_level_id=l.er_emergency_level_id)
                LEFT OUTER JOIN vn_stat as v ON(e.vn=v.vn)
                WHERE
                e.vstdate = CURDATE()
                GROUP BY e.er_emergency_level_id;";
        //ดึงข้อมูล
        try{
            $qc = \Yii::$app->db->createCommand($sql)->queryAll();
        }catch(\yii\db\Exception $e){
            throw new \yii\web\ConflicHttpException("กรุณาตรวจสอบคำสั่ง SQL => <per>{$sql}</per>");
        }
        //นำข้อมูลไปใส่ใน Provider
        $data3 = new \yii\data\ArrayDataProvider([            
            'allModels' => $qc,
            'pagination' => FALSE,
        ]);
        
        return $this->render('index', ['sex' => $grand, 'data1' => $data1, 'data2' => $data2, 'data3' => $data3]);
    }

    /**
     * Login action.
     *
     * @return string
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
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
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
}
