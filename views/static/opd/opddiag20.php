<?php
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use dosamigos\datepicker\DateRangePicker;
use dosamigos\highcharts\HighCharts;

$this->title = 'สถิติการเจ็บป่วย 20 อันดับ '.date('d/m/').(date('Y')+543);

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],    
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:90px;'],
        'attribute' => 'pdx',
        'header' => 'ICD10',        
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-left'],
        'options' => ['style' => 'width:90px;'],
        'attribute' => 'name',
        'header' => 'ชื่อการวินิจฉัย',
        'pageSummary' => 'รวม',
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'cvn',
        'header' => 'Visit',
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
        'attribute' => 'chn',
        'header' => 'รวม',
        'format'=>['decimal', 0],
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
        'pageSummaryOptions'=>['class'=>'text-center text-success'],
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
        <!-- LINE CHART -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Bar Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="line-chart" style="height: 300px;">
              <?= HighCharts::widget([ 
                    'clientOptions' => [ 
                        'chart' => [ 'type' => 'column' ], 
                        'title' => [ 'text' => $this->title ], 
                        'xAxis' => [ 'categories' => $chname ], 
                        'yAxis' => [ 'title' => [ 'text' => 'Fruit eaten' ] ], 
                        /*'series' => [ 
                            ['name' => 'ก.', 'data' => [1, 0, 4]], 
                            ['name' => 'ข.', 'data' => [4, 7, 8]],
                            ['name' => 'ค.', 'data' => [5, 7, 3]] 
                        ]*/
                        'series' => $series
                    ] 
                ]);?>  
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
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
            'showPageSummary' => true,
            'beforeHeader'=>[
                [
                    'columns'=>[
                        ['content'=>'รายการ', 'options'=>['colspan'=>3, 'class'=>'text-center warning']], 
                        ['content'=>'จำนวน/ครั้ง', 'options'=>['colspan'=>1, 'class'=>'text-center warning']], 
                        ['content'=>'ประเภทบุคคล/คน', 'options'=>['colspan'=>4, 'class'=>'text-center warning']],                        
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