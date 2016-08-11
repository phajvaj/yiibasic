<?php
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use dosamigos\datepicker\DateRangePicker;
use dosamigos\highcharts\HighCharts;

$this->title = 'รายงานผู้มารับบริการ';

$Header = [
        [
            'columns'=>[
                ['content'=>'รายการ', 'options'=>['colspan'=>2, 'class'=>'text-center warning']], 
                ['content'=>'เวร', 'options'=>['colspan'=>4, 'class'=>'text-center warning']],                
            ],
            'options'=>['class'=>'skip-export'] // remove this row from export
        ]
    ];

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-left'],
        'options' => ['style' => 'width:90px;'],
        'attribute' => 'vstdate',
        'header' => 'วันที่',
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'v1',
        'header' => 'เช้า'
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'v2',
        'header' => 'บ่าย'
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'v3',
        'header' => 'ดึก'
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:70px;'],
        'attribute' => 'cvn',
        'header' => 'รวม'
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
            'showPageSummary' => true,
        ]);
        ?>        
        <?php yii\widgets\Pjax::end(); ?>
        
        <!-- LINE CHART -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Line Chart</h3>

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
                        'chart' => [ 'type' => 'line' ], 
                        'title' => [ 'text' => 'จำนวนคนไข้ที่มารับบริการในวัน' ], 
                        'xAxis' => [ 'categories' => [ '01/08/59', '02/08/59', '03/08/59' ] ], 
                        'yAxis' => [ 'title' => [ 'text' => 'Fruit eaten' ] ], 
                        'series' => [ 
                            ['name' => 'เช้า', 'data' => [1, 0, 4]], 
                            ['name' => 'บ่าย', 'data' => [4, 7, 8]],
                            ['name' => 'ดึก', 'data' => [5, 7, 3]] 
                        ] 
                    ] 
                ]);?>  
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
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