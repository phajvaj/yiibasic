<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ScreenController extends MainController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class'  => AccessControl::className(),
                'rules' =>  [
                    [
                        'actions' => ['bpsreport21','bmistate','bmivisit'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ]
            ]
        ];
    }
    public function actionBpsreport21($dt1 = null, $dt2 = null)
    {        
        $dt1 = empty($dt1)? date('Y-m-d') : $dt1;
        $dt2 = empty($dt2)? date('Y-m-d') : $dt2;
            
        if (Yii::$app->request->isPost) {
            if(!empty($_POST['dt1'])){
                $dt1 = date('Y-m-d', strtotime($_POST['dt1']));
                $dt2 = date('Y-m-d', strtotime($_POST['dt2']));
            }            
        }
        
        $sql="
        SELECT '11' as ord,
        'จำนวนครั้งของผู้ป่วยนอก UC/เดือน' as cname,COUNT(v.vn) as cc 
        FROM vn_stat as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.vstdate BETWEEN '{$dt1}' AND '{$dt2}'
        AND p.hipdata_code='UCS'
        UNION
        SELECT '12' as ord,
        'จำนวนครั้งของผู้ป่วยนอกประกันสังคม/เดือน' as cname,COUNT(v.vn) as cc 
        FROM vn_stat as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.vstdate BETWEEN '{$dt1}' AND '{$dt2}'
        AND p.hipdata_code='SSS'
        UNION
        SELECT '13' as ord,
        'จำนวนครั้งของผู้ป่วยนอกข้าราชการ/เดือน' as cname,COUNT(v.vn) as cc 
        FROM vn_stat as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.vstdate BETWEEN '{$dt1}' AND '{$dt2}'
        AND p.hipdata_code='OFC'
        UNION
        SELECT '14' as ord,
        'จำนวนครั้งของผู้ป่วยนอก อปท./เดือน' as cname,COUNT(v.vn) as cc 
        FROM vn_stat as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.vstdate BETWEEN '{$dt1}' AND '{$dt2}'
        AND p.hipdata_code='LGO'
        UNION
        SELECT '15' as ord,
        'จำนวนครั้งของผู้ป่วยนอกทั้งหมด/เดือน' as cname,COUNT(v.vn) as cc 
        FROM vn_stat as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.vstdate BETWEEN '{$dt1}' AND '{$dt2}'
        UNION
        SELECT '16' as ord,
        'จำนวนครั้งของผู้ป่วยนอก (HN) ที่มารับบริการ/ไตรมาส' as cname,COUNT(DISTINCT v.hn) as cc 
        FROM vn_stat as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.vstdate BETWEEN DATE_SUB('{$dt1}',INTERVAL 2 MONTH) AND '{$dt2}'
        UNION
        SELECT '21' as ord,
        'จำนวนครั้งของผู้ป่วยใน UC/เดือน' as cname,COUNT(v.an) as cc 
        FROM an_stat as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.dchdate BETWEEN '{$dt1}' AND '{$dt2}'
        AND p.hipdata_code='UCS'
        UNION
        SELECT '22' as ord,
        'จำนวนครั้งของผู้ป่วยในประกันสังคม/เดือน' as cname,COUNT(v.an) as cc 
        FROM an_stat as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.dchdate BETWEEN '{$dt1}' AND '{$dt2}'
        AND p.hipdata_code='SSS'
        UNION
        SELECT '23' as ord,
        'จำนวนครั้งของผู้ป่วยในข้าราชการ/เดือน' as cname,COUNT(v.an) as cc 
        FROM an_stat as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.dchdate BETWEEN '{$dt1}' AND '{$dt2}'
        AND p.hipdata_code='OFC'
        UNION
        SELECT '24' as ord,
        'จำนวนครั้งของผู้ป่วยใน อปท./เดือน' as cname,COUNT(v.an) as cc 
        FROM an_stat as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.dchdate BETWEEN '{$dt1}' AND '{$dt2}'
        AND p.hipdata_code='LGO'
        UNION
        SELECT '25' as ord,
        'จำนวนครั้งของผู้ป่วยในทั้งหมด/เดือน' as cname,COUNT(v.an) as cc 
        FROM an_stat as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.dchdate BETWEEN '{$dt1}' AND '{$dt2}'
        UNION
        SELECT '31' as ord,
        'จำนวนวันนอนทั้งหมด/เดือน' as cname,SUM(v.admdate) as cc 
        FROM an_stat as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.dchdate BETWEEN '{$dt1}' AND '{$dt2}'
        UNION
        SELECT '32' as ord,
        'จำนวนผู้ป่วยที่เป็นโรคเบาหวาน - ความดัน/ไตรมาส' as cname,COUNT(v.vn) as cc 
        FROM vn_stat as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.vstdate BETWEEN '{$dt1}' AND '{$dt2}'
        AND (v.main_pdx BETWEEN 'E10' AND 'E14' OR v.main_pdx BETWEEN 'I10' AND 'I15')

        UNION
        SELECT '41' as ord,
        'จำนวน SumAdjRW ของผู้ป่วยใน UC/เดือน' as cname,ROUND(SUM(v.adjrw),3) as cc 
        FROM ipt as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.dchdate BETWEEN '{$dt1}' AND '{$dt2}'
        AND p.hipdata_code='UCS'
        UNION
        SELECT '42' as ord,
        'จำนวน SumAdjRW ของผู้ป่วยในประกันสังคม/เดือน' as cname,ROUND(SUM(v.adjrw),3) as cc 
        FROM ipt as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.dchdate BETWEEN '{$dt1}' AND '{$dt2}'
        AND p.hipdata_code='SSS'
        UNION
        SELECT '43' as ord,
        'จำนวน SumAdjRW ของผู้ป่วยในข้าราชการ/เดือน' as cname,ROUND(SUM(v.adjrw),3) as cc 
        FROM ipt as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.dchdate BETWEEN '{$dt1}' AND '{$dt2}'
        AND p.hipdata_code='OFC'
        UNION
        SELECT '44' as ord,
        'จำนวน SumAdjRW ของผู้ป่วยใน อปท./เดือน' as cname,ROUND(SUM(v.adjrw),3) as cc 
        FROM ipt as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.dchdate BETWEEN '{$dt1}' AND '{$dt2}'
        AND p.hipdata_code='LGO'
        UNION
        SELECT '45' as ord,
        'จำนวน SumAdjRW ของผู้ป่วยในทั้งหมด/เดือน' as cname,ROUND(SUM(v.adjrw),3) as cc 
        FROM ipt as v
        LEFT JOIN pttype as p ON(v.pttype=p.pttype) 
        WHERE 
        v.dchdate BETWEEN '{$dt1}' AND '{$dt2}';";
        
        $data = $this->getRawdata($sql);
        
        return $this->render('bpsreport21', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2]);
    }
    
    public function actionBmistate($dt1 = null, $dt2 = null)
    {          
        $dt1 = empty($dt1)? date('Y-m-d') : $dt1;
        $dt2 = empty($dt2)? date('Y-m-d') : $dt2;        
        
        if (Yii::$app->request->isPost) {
            if(!empty($_POST['dt1'])){
                $dt1 = date('Y-m-d', strtotime($_POST['dt1']));
                $dt2 = date('Y-m-d', strtotime($_POST['dt2']));
            }            
        }

        $sql = "SELECT /*cache*/ 
        CASE
        WHEN s.bmi BETWEEN '0' AND '18.5' THEN 'น้ำหนักน้อยกว่ามาตรฐาน'
        WHEN s.bmi BETWEEN '18.6' AND '22.9' THEN 'ปกติ'
        WHEN s.bmi BETWEEN '23' AND '24.9' THEN 'อ้วนระดับ 1'
        WHEN s.bmi BETWEEN '25' AND '29.9' THEN 'อ้วนระดับ 2'
        ELSE 'อ้วนระดับ 3'
        END as state,
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
        FROM opdscreen as s
        LEFT OUTER JOIN vn_stat as v ON(s.vn=v.vn)
        LEFT OUTER JOIN patient_regiment as r ON(s.hn=r.hn)
        WHERE s.vstdate BETWEEN '{$dt1}' AND '{$dt2}' GROUP BY state";
        $data = $this->getRawdata($sql);
        
        return $this->render('bmistate', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2]);
    }
    
    public function actionBmivisit($dt1 = null, $dt2 = null, $page = null, $state = null)
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
        
        $this->params = ['dt1' => $dt1, 'dt2' => $dt2, 'state' => $state];
        
        $bmi = array(
            'น้ำหนักน้อยกว่ามาตรฐาน' => ['min' => 0, 'max' => 18.5],
            'ปกติ' => ['min' => 18.6, 'max' => 22.9],
            'อ้วนระดับ 1' => ['min' => 23, 'max' => 24.9],
            'อ้วนระดับ 2' => ['min' => 25, 'max' => 29.9],
            'อ้วนระดับ 3' => ['min' => 30, 'max' => 99],
        );
        
        $sql = "SELECT /*cache*/
        v.hn,CONCAT(p.pname,p.fname,SPACE(2),p.lname) as patient,v.age_y,v.sex,s.bw,
        s.height,s.bmi,CONCAT(ROUND(s.bps),'/',ROUND(s.bpd)) as bps,
        s.fbs,v.pdx,s.cc,v.vstdate
        FROM vn_stat as v
        LEFT OUTER JOIN opdscreen as s ON(v.vn=s.vn)
        LEFT OUTER JOIN patient as p ON(v.hn=p.hn)
        WHERE
        s.bmi BETWEEN '{$bmi[$state]['min']}' AND '{$bmi[$state]['max']}'
        AND
        v.vstdate BETWEEN '{$dt1}' AND '{$dt2}'";
        $data = $this->getRawdata($sql);
        
        return $this->render('bmivisit', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2, 'state' => $state, 'sex' => $this->grander]);
    }
}
