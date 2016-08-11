<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'HOSxP Report By.phingosoft.com';

$gridColumns1 = [
    ['class' => 'kartik\grid\SerialColumn'],    
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-left'],
        'options' => ['style' => 'width:90px;'],
        'attribute' => 'ostname',
        'header' => 'จุดตรวจ'
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
        'attribute' => 'vsu',
        'header' => 'รวม'
    ],
];

$gridColumns2 = [
    ['class' => 'kartik\grid\SerialColumn'],    
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-left'],
        'options' => ['style' => 'width:90px;'],
        'attribute' => 'ostname',
        'header' => 'การมา'
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
        'attribute' => 'vsu',
        'header' => 'รวม'
    ],
];

$gridColumns3 = [
    ['class' => 'kartik\grid\SerialColumn'],    
    [
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-left'],
        'options' => ['style' => 'width:90px;'],
        'attribute' => 'levname',
        'header' => 'ระดับความรุนแรง'
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
        'attribute' => 'vsu',
        'header' => 'รวม'
    ],
];
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?=Yii::$app->name?> !</h1>

        <p class="lead">ยินดีต้อนรับ เข้าสู่ระบบรายงาน HOSxP ออนไลน์ ของ รพ.ค่ายสุริยพงษ์.</p>
        <p class="lead"><?php print_r($sex) ?></p>

        <p><a class="btn btn-lg btn-success" href="http://www.sp.mi.th" target="_blank">เว็บไซต์ รพ.ค่ายสุริยพงษ์</a></p>
    </div>
    
    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>จุดบริการ</h2>
                <p><?php
                echo GridView::widget([
                    'dataProvider' => $data1,
                    'responsive' => true,
                    'hover' => true,
                    'floatHeader' => true,
                    'columns' => $gridColumns1,
                ]);
                ?></p>
            </div>
            <div class="col-lg-4">
                <h2>การมาของผู้ป่วย</h2>

                <p><?php
                echo GridView::widget([
                    'dataProvider' => $data2,
                    'responsive' => true,
                    'hover' => true,
                    'floatHeader' => true,
                    'columns' => $gridColumns2,
                ]);
                ?></p>                
            </div>
            <div class="col-lg-4">
                <h2>ER(ระดับความรุนแรง)</h2>

                <p><?php
                echo GridView::widget([
                    'dataProvider' => $data3,
                    'responsive' => true,
                    'hover' => true,
                    'floatHeader' => true,
                    'columns' => $gridColumns3,
                ]);
                ?></p>                
            </div>
        </div>

    </div>
</div>
