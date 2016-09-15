<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class IncomeController extends MainController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['get'],
                ],
            ],
            'access' => [
                'class'  => AccessControl::className(),
                'rules' =>  [
                    [
                        'actions' => ['login'],
                        'allow' => false,
                        'roles' => ['@']
                    ],
                ]
            ]
        ];
    }
    public function actionIndex($dt1 = null, $dt2 = null, $page = null)
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
        
        $sql = "SELECT
        CASE
        WHEN {$this->incGroup[1][1]} THEN '{$this->incGroup[1][0]}'
        WHEN {$this->incGroup[2][1]} THEN '{$this->incGroup[2][0]}'
        WHEN {$this->incGroup[3][1]} THEN '{$this->incGroup[3][0]}'
        WHEN {$this->incGroup[4][1]} THEN '{$this->incGroup[4][0]}'
        WHEN {$this->incGroup[5][1]} THEN '{$this->incGroup[5][0]}'
        END as ptype,
        COUNT(v.vn) as cvn,
        COUNT(DISTINCT v.hn) as chn,
        COUNT(DISTINCT IF(v.sex='1',v.hn,NULL)) as sm,
        COUNT(DISTINCT IF(v.sex='2',v.hn,NULL)) as sf,
        SUM(v.income) as income
        FROM vn_stat as v
        WHERE
        v.vstdate BETWEEN '{$dt1}' AND '{$dt2}'
        GROUP BY ptype ORDER BY income DESC";
        $data = $this->getRawdata($sql);
        
        return $this->render('index', ['sex' => $this->grander, 'dt1' => $dt1, 'dt2' => $dt2, 'data' => $data]);
    }
    
    public function actionIncpttype($ptype = null, $dt1 = null, $dt2 = null, $page = null)
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
        
        $this->params = ['dt1' => $dt1, 'dt2' => $dt2, 'ptype' => $ptype];
        
        $incKey = $this->array2d_search($this->incGroup, 0, $ptype);
        
        $sql = "SELECT
        t.pttype,t.name,
        COUNT(v.vn) as cvn,
        COUNT(DISTINCT v.hn) as chn,
        COUNT(DISTINCT IF(v.sex='1',v.hn,NULL)) as sm,
        COUNT(DISTINCT IF(v.sex='2',v.hn,NULL)) as sf,
        SUM(v.income) as income
        FROM vn_stat as v
        LEFT OUTER JOIN pttype as t ON(v.pttype=t.pttype)
        WHERE
        v.vstdate BETWEEN '{$dt1}' AND '{$dt2}' AND {$this->incGroup[$incKey][1]}
        GROUP BY t.pttype
        ORDER BY income DESC";
        
        $data = $this->getRawdata($sql);
        return $this->render('inctype', ['data' => $data, 'ptype' => $ptype, 'dt1' => $dt1, 'dt2' => $dt2, 'sex' => $this->grander]);
    }
    
    public function actionGroup($dt1 = null, $dt2 = null, $page = null)
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
        
        $sql = "SELECT /*cache*/ c.income,c.`name`,COUNT(DISTINCT o.an) as ipd,COUNT(DISTINCT o.vn) as opd,SUM(o.sum_price) as price
        FROM income as c
        LEFT OUTER JOIN opitemrece as o ON(c.income=o.income)
        WHERE
        o.vstdate BETWEEN '{$dt1}' AND '{$dt2}'
        GROUP BY c.income";
        
        $data = $this->getRawdata($sql);
        
        return $this->render('group', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2]);
    }        
}
