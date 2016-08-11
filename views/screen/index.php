<?php
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use dosamigos\datepicker\DateRangePicker;

$this->title = 'เวชระเบียน';

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],    
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-left'],
        'options' => ['style' => 'width:90px;'],
        'attribute' => 'regiment_name',
        'header' => 'ประเภทบุคคล'
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'sm',
        'header' => $sex['1']
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:50px;'],
        'attribute' => 'sf',
        'header' => $sex['2']
    ],
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
        'options' => ['style' => 'width:70px;'],
        'attribute' => 'chn',
        'header' => 'รวม'
    ],
];
?>
<div class="site-index">
    <div class="body-content">
        <div class='well'>
            <form>
                <?= DateRangePicker::widget([
                    'language' => 'th',
                    'name' => 'date_from',
                    'value' => date('d-m-Y'),
                    'nameTo' => 'name_to',
                    'valueTo' => date('d-m-Y'),
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-M-yyyy'
                    ],
                    'size' => 'lg',
                ]);?>
                <button class='btn btn-danger'>ประมวลผล</button>
            </form>            
        </div>
        <?php yii\widgets\Pjax::begin(); ?>        
        <?php
        /*echo GridView::widget([
            'dataProvider' => $data,
            'responsive' => true,
            'hover' => true,
            'floatHeader' => true,
            'panel' => [
                'before' => 'ประมวลผลล่าสุด '.date('d/m/').(date('Y')+543),
            ],
            'export' => [
                'showConfirmAlert' => false,
                'target' => GridView::TARGET_BLANK
            ],
            'columns' => $gridColumns,
        ]);*/
        ?>        
        <?php yii\widgets\Pjax::end(); ?>
    </div>
</div>