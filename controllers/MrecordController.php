<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class MrecordController extends MainController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class'  => AccessControl::className(),
                'rules' =>  [
                    [
                        'actions' => ['family','opvisit','opvisitmonth'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ]
            ]
        ];
    }
    public function actionFamily()
    {
        $grand = $this->grander;
                
        //----------------------ประเภทบุคคล
        $sql = "SELECT
                t.regiment_type,t.regiment_name,
                SUM(if(p.sex='1',1,0)) as sm,
                SUM(if(p.sex='2',1,0)) as sf,
                COUNT(p.hn) as chn
                FROM patient as p
                INNER JOIN patient_regiment as r ON(p.hn=r.hn)
                INNER JOIN regiment_type as t ON(r.regiment_type=t.regiment_type)
                GROUP BY t.regiment_type;";
        //ดึงข้อมูล
        try{
            $qc = \Yii::$app->db->createCommand($sql)->queryAll();
        }catch(\yii\db\Exception $e){
            throw new \yii\web\ConflicHttpException("กรุณาตรวจสอบคำสั่ง SQL => <per>{$sql}</per>");
        }
        //นำข้อมูลไปใส่ใน Provider
        $data = new \yii\data\ArrayDataProvider([            
            'allModels' => $qc,
            'pagination' => FALSE,
        ]);
        return $this->render('family', ['sex' => $grand, 'data' => $data]);
    }
    
    public function actionOpvisit($dt1 = null, $dt2 = null, $page = null)
    {        
        $dt1 = empty($dt1)? date('Y-m-d') : $dt1;
        $dt2 = empty($dt2)? date('Y-m-d') : $dt2;
        $this->pages = empty($page)?1:$page;        
        
        if (Yii::$app->request->isPost) {
            if(!empty($_POST['dt1'])){
                $dt1 = date('Y-m-d', strtotime($_POST['dt1']));
                $dt2 = date('Y-m-d', strtotime($_POST['dt2']));
            }
            if(isset($_POST['export_type']))
                $this->pages = false;
        }
        
        $this->params = ['dt1' => $dt1, 'dt2' => $dt2];
        
        $sql = "SELECT /*cache*/
                vstdate,
                SUM(IF(vsttime BETWEEN '08:00:00' AND '15:59:59',1,0)) as v1,
                SUM(IF(vsttime BETWEEN '16:00:00' AND '23:59:59',1,0)) as v2,
                SUM(IF(vsttime BETWEEN '00:00:00' AND '07:59:59',1,0)) as v3,
                COUNT(vn) as cvn
                FROM ovst
                WHERE vstdate BETWEEN '{$dt1}' AND '{$dt2}'
                GROUP BY vstdate";
        $data = $this->getRawdata($sql);
        
        return $this->render('opvisit', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2]);
    }
    
    public function actionOpvisitmonth($dt1 = null, $dt2 = null, $page = null)
    {
        ini_set('max_execution_time', 300);
        $dt1 = empty($dt1)? date('Y-m-d') : $dt1;
        $dt2 = empty($dt2)? date('Y-m-d') : $dt2;
        $this->pages = empty($page)?1:$page;        
        
        if (Yii::$app->request->isPost) {
            if(!empty($_POST['dt1'])){
                $dt1 = date('Y-m-d', strtotime($_POST['dt1']));
                $dt2 = date('Y-m-d', strtotime($_POST['dt2']));
            }
            if(isset($_POST['export_type']))
                $this->pages = false;
        }
        
        $this->params = ['dt1' => $dt1, 'dt2' => $dt2];
        
        $sql = "SELECT /*cache*/                
        op.mm,op.ccv,op.vhn,ip.cca,ip.ahn
        FROM
        (SELECT
        DATE_FORMAT(vstdate,'%Y-%m') mm,
        COUNT(vn) as ccv,COUNT(DISTINCT hn) as vhn
        FROM vn_stat
        WHERE
        vstdate BETWEEN '{$dt1}' AND '{$dt2}'
        GROUP BY mm) as op
        LEFT JOIN
        (SELECT
        DATE_FORMAT(dchdate,'%Y-%m') mm,
        COUNT(an) as cca,COUNT(DISTINCT hn) as ahn
        FROM an_stat
        WHERE
        dchdate BETWEEN '{$dt1}' AND '{$dt2}'
        GROUP BY mm) as ip ON(op.mm=ip.mm)";
        $data = $this->getRawdata($sql);
        
        return $this->render('opvisitmonth', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2]);
    }
}
