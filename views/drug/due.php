<?php
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use dosamigos\datepicker\DateRangePicker;

$this->title = 'รายงานผู้ป่วย DUE';

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'attribute' => 'hn',
        'header' => 'HN',        
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-left'],
        'attribute' => 'ptname',
        'header' => 'ชื่อ - นามสกุล',        
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],        
        'attribute' => 'vstdate',
        'header' => 'วันที่บริการ',        
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        
        'attribute' => 'icode',
        'header' => 'รหัสยา',        
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'attribute' => 'drname',
        'header' => 'ชื่อยา',        
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-right'],        
        'attribute' => 'qty',
        'header' => 'จำนวน',
        'format'=>['decimal', 0],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-left'],        
        'attribute' => 'doctor_reason',
        'header' => 'วิธีการใช้ยา',        
    ],
];
?>
<div class="site-index">
    <div class="body-content">
        <div class='well'>
            <div class="row">
                <?php $form = ActiveForm::begin(['id' => 'rpt-form', 'enableClientValidation' => false]); ?>
                <div class="col-lg-2">
                    <strong>วันที่บริการ</strong>
                </div>
                <div class="col-lg-6">            
                <?= DateRangePicker::widget([
                    'language' => 'th',
                    'name' => 'dt1',
                    'value' => date('d-m-Y', strtotime($dt1)),
                    'nameTo' => 'dt2',
                    'valueTo' => date('d-m-Y', strtotime($dt2)),                                        
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy'
                    ],
                    'size' => 'lg',                    
                ]);?>
                </div>
                <div class="col-lg-2">                    
                    <button class='btn btn-danger'>ประมวลผล</button>            
                </div>
                <?php ActiveForm::end(); ?>
            </div>
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