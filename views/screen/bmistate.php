<?php
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use dosamigos\datepicker\DateRangePicker;

$this->title = 'รายงานระยะค่าดัชนีมวลกาย '.Yii::$app->thaiformatter->asDate(date('Y-m-d'), 'short');

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],    
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-left'],
        'options' => ['style' => 'width:90px;'],
        'attribute' => 'state',
        'header' => 'ระยะ BMI',
        'pageSummary' => 'รวม',
        'format'=>'raw',
        'value'=> function($model)use($dt1, $dt2){
            return Html::a(Html::encode($model['state']),
                           [
                               'screen/bmivisit',
                               'state' => $model['state'],
                               'dt1' => $dt1,
                               'dt2' => $dt2,
                           ],
                           ['target' => '_blank']
                          );
        }
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'sm',
        'header' => 'ชาย',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'sf',
        'header' => 'หญิง',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'ag1',
        'header' => '0-5',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'ag2',
        'header' => '6-15',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'ag3',
        'header' => '16-35',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'ag4',
        'header' => '36-55',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'ag5',
        'header' => '56+',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'r1',
        'header' => 'ก.',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'r2',
        'header' => 'ข.',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'r3',
        'header' => 'ค.',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:70px;'],
        'attribute' => 'cc',
        'header' => 'รวม',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-success'],
    ],
];

$not="<strong>ระยะ BMI</strong>
<pre>
น้ำหนักน้อยกว่ามาตรฐาน  = < 18.5
ปกติ                 = 18.5-22.9
อ้วนระดับ 1           = 23-24.9
อ้วนระดับ 2           = 25-29.9
อ้วนระดับ 3           = มากกว่าหรือเท่ากับ 30
</pre>";
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
                'type' => 'primary', 'heading' => $this->title,
                'after' => $not,
            ],
            'columns' => $gridColumns,
            'showPageSummary' => true,
            'beforeHeader'=>[
                [
                    'columns'=>[
                        ['content'=>'รายการ', 'options'=>['colspan'=>2, 'class'=>'text-center warning']],                        
                        ['content'=>'เพศ', 'options'=>['colspan'=>2, 'class'=>'text-center warning']],
                        ['content'=>'ช่วงอายุ', 'options'=>['colspan'=>5, 'class'=>'text-center warning']],
                        ['content'=>'ประเภทบุคคล', 'options'=>['colspan'=>4, 'class'=>'text-center warning']],
                    ],                    
                ]
            ],
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