<?php
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use dosamigos\datepicker\DateRangePicker;

$this->title = 'รายงานผู้มารับบริการ(รายเดือน)';

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:90px;'],
        'attribute' => 'mm',
        'header' => 'เดือน/ปี',
        'pageSummary' => 'รวม',
        'value' => function($model) {
            return Yii::$app->thaiformatter->asDate($model['mm'], 'php:m/').(Yii::$app->thaiformatter->asDate($model['mm'], 'php:Y')+543);
        }
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'ccv',
        'header' => 'ครั้ง',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'vhn',
        'header' => 'คน',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'cca',
        'header' => 'ครั้ง',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'ahn',
        'header' => 'คน',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-warning'],
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        #'attribute' => '',
        'header' => 'ครั้ง',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-success'],
        'value' => function($model) {
            return $model['ccv'] + $model['cca'];
        }
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        #'attribute' => '',
        'header' => 'คน',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-success'],
        'value' => function($model) {
            return $model['vhn'] + $model['ahn'];
        }
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
                'before' => 'ประมวลผลล่าสุด '.Yii::$app->thaiformatter->asDate(time(), 'medium'),
                'type' => 'primary', 'heading' => $this->title
            ],
            'columns' => $gridColumns,
            'showPageSummary' => true,            
            'beforeHeader'=>[
                [
                    'columns'=>[
                        ['content'=>'รายการ', 'options'=>['colspan'=>2, 'class'=>'text-center warning']],                        
                        ['content'=>'ผู้ป่วยนอก', 'options'=>['colspan'=>2, 'class'=>'text-center warning']],
                        ['content'=>'ผู้ป่วยใน', 'options'=>['colspan'=>2, 'class'=>'text-center warning']],
                        ['content'=>'รวม', 'options'=>['colspan'=>2, 'class'=>'text-center warning']],
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