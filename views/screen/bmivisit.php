<?php
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use dosamigos\datepicker\DateRangePicker;

$this->title = "รายงานค่าดัชนีมวลกายระยะ <strong>{$state}</strong> ช่วงวันที่ ".Yii::$app->thaiformatter->asDate($dt1, 'short').
    " ถึง ".Yii::$app->thaiformatter->asDate($dt2, 'short');
$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn', 'width' => '20px'],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'hn',
        'header' => 'HN',
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-left'],        
        'attribute' => 'patient',
        'header' => 'ชื่อ - นามสกุล',
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],        
        'attribute' => 'age_y',
        'header' => 'อายุ',
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],        
        'attribute' => 'sex',
        'header' => 'เพศ',
        'value'=> function($model)use($sex){
            return Html::encode($sex[$model['sex']]);
        }
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],        
        'attribute' => 'bw',
        'header' => 'น้ำหนัก/กก.',
        'format'=>['decimal', 2],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],        
        'attribute' => 'height',
        'header' => 'ส่วนสูง/ม.',
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],        
        'attribute' => 'bmi',
        'header' => 'BMI',
        'format'=>['decimal', 2],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],        
        'attribute' => 'bps',
        'header' => 'ความดัน',
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],        
        'attribute' => 'fbs',
        'header' => 'FBS',
        'format'=>['decimal', 2],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],        
        'attribute' => 'pdx',
        'header' => 'Diag',
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-left'],        
        'attribute' => 'cc',
        'header' => 'CC',
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],        
        'attribute' => 'vstdate',
        'header' => 'วันที่บริการ',
        'value' => function($model) {
            return Yii::$app->thaiformatter->asDate($model['vstdate'], 'short');
        }
    ],
];
?>
<div class="site-index">
    <div class="body-content">
        <div class="pull-left">    
            <a class="btn  btn-warning"
               href="<?= Url::to(['/screen/bmistate','dt1' => $dt1, 'dt2' => $dt2]) ?>">
                <i class="glyphicon glyphicon-chevron-left"> ย้อนกลับ</i>
            </a>

        </div>
        <?php yii\widgets\Pjax::begin(); ?>        
        <?php
        echo GridView::widget([
            'dataProvider' => $data,
            'responsive' => true,
            'hover' => true,
            'floatHeader' => true,
            'toolbar'=> [
                ['content'=>                    
                    ExportMenu::widget([
                        'dataProvider' => $data,    
                        'fontAwesome' => true,
                        'showConfirmAlert' => false,
                        'dropdownOptions' => [                            
                            'class' => 'btn btn-default'
                        ]
                    ])
                ],                
                '{toggleData}',
            ],
            'panel' => [
                'before' => 'ประมวลผลล่าสุด '.date('d/m/').(date('Y')+543),
                'type' => 'primary', 'heading' => $this->title
            ],
            'columns' => $gridColumns,            
        ]);
        ?>        
        <?php yii\widgets\Pjax::end(); ?>
    </div>
</div>
<?php
$script = <<< JS
$(function(){
    $('.kv-export-full-form').append('<input type="hidden" name="dt1" value="{$dt1}" />');
    $('.kv-export-full-form').append('<input type="hidden" name="dt2" value="{$dt2}" />');
});
JS;
$this->registerJs($script);
?>