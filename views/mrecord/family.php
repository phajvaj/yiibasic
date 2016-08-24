<?php
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Family';

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],    
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-left'],
        'options' => ['style' => 'width:90px;'],
        'attribute' => 'regiment_name',
        'header' => 'ประเภทบุคคล',
        'pageSummary' => 'รวม',
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'sm',
        'header' => $sex['1'],
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
        'header' => $sex['2'],
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
    </div>
</div>