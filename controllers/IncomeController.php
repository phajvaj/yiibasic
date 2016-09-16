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
            'access' => [
                'class'  => AccessControl::className(),
                'rules' =>  [
                    [
                        'actions' => ['index','rcpt10','group','incpttype'],
                        'allow' => true,
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
    
    public function actionRcpt10($dt1 = null, $dt2 = null, $page = null)
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
        v.vn,v.hn,v.vstdate as 'วันที่',
        SUM(IF(o.icode LIKE '15%',o.sum_price,0)) as 'ค่ายา',
        SUM(IF(o.income = '07',o.sum_price,0)) as 'ค่าlab',
        SUM(IF(o.income = '08',o.sum_price,0)) as 'ค่าxray',
        SUM(IF(o.icode = '3903966',o.sum_price,0)) as 'ค่าekg',
        SUM(IF(o.income = '05',o.sum_price,0)) as 'ค่าเวชภัณฑ์มิใช่ยา',
        SUM(IF(o.income = '12' AND o.icode NOT IN('3903984','3903762'),o.sum_price,0)) as 'ค่าพยาบาล',
        SUM(IF(o.income = '13',o.sum_price,0)) as 'ค่าฟัน',
        SUM(IF(o.income = '15',o.sum_price,0)) as 'ค่ากายภาพ',
        SUM(IF(o.icode = '3903762',o.sum_price,0)) as 'ค่าบริการทางการแพทย์',
        SUM(IF(o.icode = '3903984',o.sum_price,0)) as 'ค่าใบรับรองแพทย์',
        SUM(IF(o.icode = '3901765',o.sum_price,0)) as 'ค่าห้องพิเศษ',
        SUM(IF(o.icode IN ('3903961','3903996','3903997','3903995','3903998','3903999','3904000'),o.sum_price,0)) as 'ค่าไตเทียม',
        SUM(o.sum_price) as 'รวม' 
        FROM vn_stat as v 
        LEFT OUTER JOIN opitemrece as o ON(v.vn=o.vn) 
        LEFT OUTER JOIN pttype as t ON(v.pttype=t.pttype) 
        LEFT OUTER JOIN rcpt_print as r ON(o.finance_number=r.finance_number) 
        WHERE 
        DATE_FORMAT(r.bill_date_time,'%Y-%m-%d') BETWEEN '{$dt1}' AND '{$dt2}' AND o.pttype='10' 
        GROUP BY v.vn ORDER BY v.vstdate";
        
        $data = $this->getRawdata($sql);
        
        return $this->render('rcpt10', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2]);
    }
}
