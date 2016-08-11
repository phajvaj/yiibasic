<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class DrugController extends MainController
{
    public function actionIndex($dt1 = null, $dt2 = null)
    {        
        $dt1 = empty($dt1)? date('Y-m-d') : $dt1;
        $dt2 = empty($dt2)? date('Y-m-d') : $dt2;
        
        if (Yii::$app->request->isPost) {            
            $dt1 = date('Y-m-d', strtotime($_POST['dt1']));
            $dt2 = date('Y-m-d', strtotime($_POST['dt2']));            
        }
        $grand = $this->grander;
        $sql = "SELECT
                d.icode,CONCAT(d.`name`,' [',d.strength,' x 1',d.units,']') as drugname,COUNT(DISTINCT o.vn) as cvn,
                CONCAT(SUM(o.qty),' ',d.units) as qty,o.unitprice,SUM(o.sum_price) as sumprice
                FROM opitemrece as o
                INNER JOIN drugitems as d ON(o.icode=d.icode)
                WHERE o.vstdate BETWEEN '{$dt1}' AND '{$dt2}' GROUP BY o.icode ORDER BY qty DESC";
        $data = $this->getRawdata($sql);
        
        return $this->render('index', ['sex' => $grand, 'data' => $data, 'dt1' => $dt1, 'dt2' => $dt2]);
    }
    
    public function actionRxopd()
    {
        $dt1 = date('Y-m-d');
        $dt2 = date('Y-m-d');
        
        if (Yii::$app->request->isPost) {            
            $dt1 = date('Y-m-d', strtotime($_POST['dt1']));
            $dt2 = date('Y-m-d', strtotime($_POST['dt2']));            
        }
        
        $sql = "SELECT /*cache*/ DATE_FORMAT(o.rxdate,'%Y-%m') as Months,
                COUNT(DISTINCT o.vn) as rxopd,
                SUM(o.sum_price) as sump,
                COUNT(o.icode) as items
                FROM opitemrece as o
                LEFT OUTER JOIN rx_doctor as r ON(r.vn = o.vn)
                WHERE 
                o.rxdate BETWEEN '{$dt1}' AND '{$dt2}' 
                AND o.icode LIKE '1%'
                GROUP BY Months";
        
        $data = $this->getRawdata($sql);
        
        return $this->render('rxopd', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2]);
    }
    
    public function actionRxipd(){
        $dt1 = date('Y-m-d');
        $dt2 = date('Y-m-d');
        
        if (Yii::$app->request->isPost) {            
            $dt1 = date('Y-m-d', strtotime($_POST['dt1']));
            $dt2 = date('Y-m-d', strtotime($_POST['dt2']));            
        }
        
        $sql = "SELECT /*cache*/ DATE_FORMAT(o.rxdate,'%Y-%m') as Months,
                COUNT(DISTINCT o.an) as rxopd,
                SUM(o.sum_price) as sump,
                COUNT(o.icode) as items
                FROM opitemrece as o
                LEFT OUTER JOIN rx_doctor as r ON(r.vn = o.vn)
                WHERE 
                o.rxdate BETWEEN '{$dt1}' AND '{$dt2}' 
                AND o.icode LIKE '1%' AND o.an IS NOT NULL AND o.an <> ''
                GROUP BY Months";
        
        $data = $this->getRawdata($sql);
        
        return $this->render('rxipd', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2]);
    }
}
