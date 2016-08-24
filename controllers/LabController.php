<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class LabController extends MainController
{
    public function actionInurine($dt1 = null, $dt2 = null, $page = null)
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
        
        $sql = "SELECT o.lab_items_code,i.lab_items_name,
        COUNT(DISTINCT v.hn, IF(v.sex='1',v.sex,NULL)) as sm,
        COUNT(DISTINCT v.hn, IF(v.sex='2',v.sex,NULL)) as sf,
        COUNT(DISTINCT v.hn, IF(v.age_y BETWEEN '0' AND '5',v.age_y,NULL)) as ag1,
        COUNT(DISTINCT v.hn, IF(v.age_y BETWEEN '6' AND '15',v.age_y,NULL)) as ag2,
        COUNT(DISTINCT v.hn, IF(v.age_y BETWEEN '16' AND '35',v.age_y,NULL)) as ag3,
        COUNT(DISTINCT v.hn, IF(v.age_y BETWEEN '36' AND '55',v.age_y,NULL)) as ag4,
        COUNT(DISTINCT v.hn, IF(v.age_y BETWEEN '56' AND '120',v.age_y,NULL)) as ag5,
        COUNT(DISTINCT s.hn, IF(r.regiment_type BETWEEN '1' AND '5',r.regiment_type,NULL)) as r1,
        COUNT(DISTINCT s.hn, IF(r.regiment_type BETWEEN '6' AND '10',r.regiment_type,NULL)) as r2,
        COUNT(DISTINCT s.hn, IF(r.regiment_type BETWEEN '11' AND '19',r.regiment_type,NULL)) as r3,
        COUNT(DISTINCT s.hn) as cc
        FROM lab_head as s
        LEFT OUTER JOIN lab_order as o ON(s.lab_order_number=o.lab_order_number)
        LEFT OUTER JOIN lab_items as i ON(o.lab_items_code=i.lab_items_code)
        LEFT OUTER JOIN vn_stat as v ON(s.vn=v.vn)
        LEFT OUTER JOIN patient_regiment as r ON(s.hn=r.hn)
        WHERE
        s.order_date BETWEEN '{$dt1}' AND '{$dt2}'
        AND o.lab_items_code IN('480','68','69','478')
        AND o.lab_order_result='Positive' AND o.confirm='Y' AND s.department='OPD'
        GROUP BY o.lab_items_code";
        $data = $this->getRawdata($sql);
        
        return $this->render('inurine', ['dt1' => $dt1, 'dt2' => $dt2, 'data' => $data]);
    }        
}
