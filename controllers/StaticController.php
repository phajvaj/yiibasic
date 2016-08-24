<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class StaticController extends MainController
{
    public function actionOpddiag20($dt1 = null, $dt2 = null, $page = null)
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
        v.pdx,i.name,COUNT(v.vn) as cvn,COUNT(DISTINCT v.hn) as chn,
        COUNT(DISTINCT v.hn, IF(r.regiment_type BETWEEN '1' AND '5',r.regiment_type,NULL)) as r1,
        COUNT(DISTINCT v.hn, IF(r.regiment_type BETWEEN '6' AND '10',r.regiment_type,NULL)) as r2,
        COUNT(DISTINCT v.hn, IF(r.regiment_type BETWEEN '11' AND '19',r.regiment_type,NULL)) as r3
        FROM vn_stat as v
        LEFT OUTER JOIN icd101 as i ON(v.pdx=i.`code`)
        LEFT OUTER JOIN patient_regiment as r ON(v.hn=r.hn)
        WHERE
        vstdate BETWEEN '{$dt1}' AND '{$dt2}' AND v.pdx<>'' AND v.pdx IS NOT NULL AND v.pdx NOT LIKE 'Z%'
        GROUP BY pdx
        ORDER BY cvn DESC
        LIMIT 20";
        
        $data = $this->getRawdata($sql);
        $chart = $this->charts;
        
        $cc = sizeof($chart);        
        for($i=0;$i<$cc;$i++){
            $chname[] = $chart[$i]['pdx'];
            $r1[] = (int) $chart[$i]['r1'];
            $r2[] = (int) $chart[$i]['r2'];
            $r3[] = (int) $chart[$i]['r3'];
        }
        $series[] = array(
            'name' => 'ก.', 'data' => $r1,            
        );
        $series[] = array(
            'name' => 'ข.', 'data' => $r2,            
        );
        $series[] = array(            
            'name' => 'ค.', 'data' => $r3,
        );
        return $this->render('opd/opddiag20', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2, 'chname' => $chname, 'series' => $series]);
    }
        
    public function actionGrouptop10($dt1 = null, $dt2 = null)
    {        
        $dt1 = empty($dt1)? date('Y-m-d') : $dt1;
        $dt2 = empty($dt2)? date('Y-m-d') : $dt2;
            
        if (Yii::$app->request->isPost) {
            if(!empty($_POST['dt1'])){
                $dt1 = date('Y-m-d', strtotime($_POST['dt1']));
                $dt2 = date('Y-m-d', strtotime($_POST['dt2']));
            }            
        }
        
        $sql="SELECT z.* FROM
        (SELECT
        CASE
        WHEN v.main_pdx REGEXP '^(A|B)' THEN 'โรคติดเชื้อและปรสิต'
        WHEN v.main_pdx BETWEEN 'C00' AND 'C97' OR v.main_pdx BETWEEN 'D00' AND 'D48' THEN 'เนื้องอก(รวมมะเร็ง)'
        WHEN v.main_pdx BETWEEN 'D50' AND 'D89' THEN 'โรคเลือดและอวัยวะเพศสร้างเลือดและความผิดปกติที่เกี่ยวกับคุ้มกัน'
        WHEN v.main_pdx LIKE 'E%' THEN 'โรคเกี่ยวกับต่อมไร้ท่อ โภชนาการ และเมตะบอลิสัม'
        WHEN v.main_pdx LIKE 'F%' THEN 'ภาวะแปรปรวนทางจิตและพฤติกรรม'
        WHEN v.main_pdx LIKE 'G%' THEN 'โรคระบบประสาท'
        WHEN v.main_pdx LIKE 'I%' THEN 'โรคระบบไหลเวียนเลือด'
        WHEN v.main_pdx LIKE 'J%' THEN 'โรคระบบหายใจ'
        WHEN v.main_pdx LIKE 'L%' THEN 'โรคผิวหนังและเนื้อเยื่อใต้ผิวหนัง'
        WHEN v.main_pdx LIKE 'M%' THEN 'โรคระบบกล้ามเนื้อ รวมโรคร่างและเนื้อยึดเสริม'
        WHEN v.main_pdx LIKE 'N%' THEN 'โรคระบบสืบพันธุ์ร่วมปัสสาวะ'
        WHEN v.main_pdx LIKE 'Q%' THEN 'รูปร่างผิดปกติแต่กำเนิดการพิการจนผิดรูปแต่กำเนิดและโครโมโทรมผิดปกติ'
        WHEN v.main_pdx LIKE 'R%' THEN 'อาการแสดงและสิ่งผิดปกติที่พบได้จากการตรวจทางคลินิกและทางห้องปฎิบัติการที่ไม่สามารถจำแนกโรคกลุ่มอื่นได้'
        WHEN v.main_pdx LIKE 'O%' AND v.main_pdx<>'O88' THEN 'ภาวะแทรกในการตั้งครภ์ การคลอด และระยะหลังคลอด'
        WHEN v.main_pdx BETWEEN 'H00' AND 'H59' THEN 'โรคตารวมส่วนประกอบของตา'
        WHEN v.main_pdx BETWEEN 'H60' AND 'H95' THEN 'โรคหูปละปุ่มกกหู'
        WHEN v.main_pdx BETWEEN 'K00' AND 'K93' THEN 'โรคระบบย่อยอาหาร รวมโรคในช่องปาก'
        WHEN v.main_pdx BETWEEN 'P00' AND 'P96' THEN 'ภาวะผิดปกติของทารกที่เกิดขึ้นในระยะปริกำเนิด(อายุครรภ์22สัปดาห์ขึ้นไปถึง7วันหลังคลอด)'
        WHEN v.main_pdx REGEXP '^(X4|X6|Y1)' OR v.main_pdx BETWEEN 'X85' AND 'X90' THEN 'การเป็นพิษและผลที่ตามมา'
        WHEN v.main_pdx BETWEEN 'V01' AND 'V99' OR v.main_pdx = 'Y85' THEN 'อุบัติเหตุจากการขนส่งและผลที่ตามมา'
        WHEN v.main_pdx REGEXP '^(W|X0|X1|X3|X5|X7|Y0)' OR v.main_pdx BETWEEN 'X80' AND 'X84'
         OR v.main_pdx BETWEEN 'X91' AND 'X99' OR v.main_pdx BETWEEN 'Y20' AND 'Y36'
         OR v.main_pdx BETWEEN 'Y40' AND 'Y84' OR v.main_pdx BETWEEN 'Y86' AND 'Y89' THEN 'สาเหตุจากภายนอกอื่นๆที่ทำให้ป่วยหรือตาย'
        END as cname,
        COUNT(DISTINCT v.hn, IF(r.regiment_type BETWEEN '1' AND '5',r.regiment_type,NULL)) as r1,
        COUNT(DISTINCT v.hn, IF(r.regiment_type BETWEEN '6' AND '10',r.regiment_type,NULL)) as r2,
        COUNT(DISTINCT v.hn, IF(r.regiment_type BETWEEN '11' AND '19',r.regiment_type,NULL)) as r3,
        COUNT(DISTINCT v.hn) as chn
        FROM vn_stat as v
        LEFT OUTER JOIN patient_regiment as r ON(v.hn=r.hn)
        WHERE vstdate BETWEEN '{$dt1}' AND '{$dt2}'
        GROUP BY cname) as z
        WHERE z.cname IS NOT NULL ORDER BY z.chn DESC LIMIT 10";
        
        $data = $this->getRawdata($sql);
        
        return $this->render('opd/grouptop10', ['data' => $data, 'dt1' => $dt1, 'dt2' => $dt2]);
    }
    
}
