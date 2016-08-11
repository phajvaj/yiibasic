<?php
namespace app\controllers;

#use Yii;
#use yii\filters\AccessControl;
use yii\web\Controller;
#use yii\filters\VerbFilter;

class MainController extends Controller{
    
    protected $grander = array(
        '1' => 'ชาย',
        '2' => 'หญิง'
    );
    
    protected $incGroup = array(
        '1' => ['เบิกได้', "v.pttype IN('22','24') OR v.pttype LIKE 'L%'"],
        '2' => ['ชำระเงินเอง', "v.pttype IN('10','20','21','27','45','36')"],
        '3' => ['อนุเคราะห์', "v.pttype IN('42','64','14')"],
        '4' => ['UC นอกเขต(พลทหาร)', "v.pttype = '91'"],
        '5' => ['UC นอกใน(พลทหาร)', "v.pttype = '90'"],
    );
    
    public function getRawdata($sql)
    {        
        try{
            $qc = \Yii::$app->db->createCommand($sql)->queryAll();
        }catch(\yii\db\Exception $e){
            #throw new \yii\web\ConflicHttpException("กรุณาตรวจสอบคำสั่ง SQL => <per>{$sql}</per>");
            return null;
        }
        //นำข้อมูลไปใส่ใน Provider
        $data = new \yii\data\ArrayDataProvider([            
            'allModels' => $qc,
            'pagination' => FALSE,
        ]);
        return $data;
    }
    
    public function array2d_search($array = null, $attr = null, $val = null, $strict = FALSE) {
      // Error is input array is not an array
      if (!is_array($array)) return FALSE;
      // Loop the array
      foreach ($array as $key => $inner) {
        // Error if inner item is not an array (you may want to remove this line)
        if (!is_array($inner)) return FALSE;
        // Skip entries where search key is not present
        if (!isset($inner[$attr])) continue;
          
        if ($strict) {
          // Strict typing
          if ($inner[$attr] === $val) return $key;
        } else {
          // Loose typing
          if ($inner[$attr] == $val) return $key;
        }
      }
      // We didn't find it
      return NULL;
    }
}