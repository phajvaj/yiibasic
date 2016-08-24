<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class DrugController extends MainController
{
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
    
    public function actionRxopd($dt1 = null, $dt2 = null, $page = null)
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
        
        /*$sql = "SELECT cache DATE_FORMAT(o.rxdate,'%Y-%m') as Months,
                COUNT(DISTINCT o.vn) as rxopd,
                SUM(o.sum_price) as sump,
                COUNT(o.icode) as items
                FROM opitemrece as o
                LEFT OUTER JOIN rx_doctor as r ON(r.vn = o.vn)
                WHERE 
                o.rxdate BETWEEN '{$dt1}' AND '{$dt2}' 
                AND (o.an IS NULL OR o.an = '') AND o.icode LIKE '1%'
                GROUP BY Months";*/
        $sql = "SELECT /*cache*/ DATE_FORMAT(o.rxdate,'%Y-%m') as Months,
        COUNT(DISTINCT o.vn) as cvn,
        COUNT(DISTINCT IF(o.icode LIKE '1%',o.vn,NULL)) as rxopd,
        COUNT(DISTINCT IF((SELECT IFNULL((SELECT COUNT(icode) FROM opitemrece WHERE vn=o.vn AND icode LIKE '1%' GROUP BY vn),0)) = 0,o.vn,NULL)) as nrx,
        SUM(o.icode LIKE '1%') as items,
        SUM(o.sum_price) as sump
        FROM opitemrece as o
        LEFT OUTER JOIN rx_doctor as r ON(r.vn = o.vn)
        WHERE 
        o.rxdate BETWEEN '{$dt1}' AND '{$dt2}' 
        GROUP BY Months";
        
        $data = $this->getRawdata($sql);
        
        return $this->render('rxopd', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2]);
    }
    
    public function actionRxipd($dt1 = null, $dt2 = null, $page = null)
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
        
        $sql = "SELECT /*cache*/ DATE_FORMAT(a.dchdate,'%Y-%m') as Months,
                COUNT(DISTINCT o.an) as can,
                COUNT(DISTINCT o.order_no) as rxipd,
                COUNT(o.icode) as items,
                SUM(a.admdate) as admdate,
                SUM(o.sum_price) as sump
                FROM opitemrece as o
                LEFT OUTER JOIN an_stat as a ON(o.an=a.an)                
                WHERE 
                a.dchdate BETWEEN '{$dt1}' AND '{$dt2}' 
                AND o.icode LIKE '1%' AND (o.an IS NOT NULL AND o.an <> '')
                GROUP BY Months";
        
        $data = $this->getRawdata($sql);
        
        return $this->render('rxipd', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2]);
    }
    
    public function actionDue($dt1 = null, $dt2 = null, $page = null)
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
        o.hn,CONCAT(p.pname,p.fname,SPACE(2),p.lname) as ptname,o.icode,CONCAT(d.`name`,' ',d.strength,' x 1',d.units) as drname,o.qty,e.doctor_reason,o.vstdate
        FROM opitemrece as o
        LEFT OUTER JOIN ovst_presc_ned as n ON(o.vn=n.vn AND o.icode=n.icode)
        LEFT OUTER JOIN drugitems as d ON(o.icode=d.icode)
        LEFT OUTER JOIN patient as p ON(o.hn=p.hn)
        LEFT OUTER JOIN drugitems_ned_reason_list as e ON(n.presc_reason=e.claim_control)
        WHERE
        o.icode IN('1590003','1510901','1560016','1500670','1500605')
        AND
        o.vstdate BETWEEN '{$dt1}' AND '{$dt2}'
        ORDER BY vstdate DESC";
        
        $data = $this->getRawdata($sql);        
        
        return $this->render('due', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2]);
    }
    
    public function actionAllergy($dt1 = null, $dt2 = null, $page = null)
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
        o.hn,CONCAT(p.pname,p.fname,SPACE(2),p.lname) as ptname,
        o.agent,o.symptom,asr.seiousness_name,u.`name` as reporter,o.report_date
        FROM opd_allergy as o
        LEFT OUTER JOIN patient as p ON(p.hn = o.hn)
        LEFT OUTER JOIN allergy_seriousness as asr ON(asr.seriousness_id = o.seriousness_id)
        LEFT OUTER JOIN allergy_result as ars ON(ars.allergy_result_id = o.allergy_result_id)
        LEFT OUTER JOIN allergy_relation as ar ON(ar.allergy_relation_id = o.allergy_relation_id)
        LEFT OUTER JOIN opduser as u ON(o.reporter=u.loginname)
        WHERE o.report_date BETWEEN '{$dt1}' AND '{$dt2}'";
        
        $data = $this->getRawdata($sql);        
        
        return $this->render('allergy', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2]);
    }
}
