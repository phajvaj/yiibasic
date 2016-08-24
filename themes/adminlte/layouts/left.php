<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=Yii::$app->user->id?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    //OPD Menu
                    ['label' => 'OPD', 'options' => ['class' => 'header']],
                    //['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
                    //['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']],
                    //['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'สถิติ',
                        'icon' => 'fa fa-dashboard',
                        'url' => '#',
                        'items' => [
                            ['label' => 'สถิติการเจ็บป่วย 20 อันดับ', 'icon' => 'fa fa-bar-chart', 'url' => ['/static/opddiag20']],
                            ['label' => 'รายงานสถิติการเจ็บป่วย 10 อันดับกลุ่มโรค', 'icon' => 'fa fa-bar-chart', 'url' => ['/static/grouptop10']],
                        ],
                    ],
                    [
                        'label' => 'เวชระเบียน',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'ประเภทบุคคล', 'icon' => 'fa fa-file-code-o', 'url' => ['/mrecord/family'],],
                            ['label' => 'รายงานผู้มารับบริการ', 'icon' => 'fa fa-file-code-o', 'url' => ['/mrecord/opvisit'],],                            
                            [
                                'label' => 'Level One',
                                'icon' => 'fa fa-circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'fa fa-circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'label' => 'คัดกรอง',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' =>
                            [
                                ['label' => 'รายงานผลการบริการ', 'icon' => 'fa fa-circle-o', 'url' => ['/screen/bpsreport21']],
                                ['label' => 'รายงานระยะค่าดัชนีมวลกาย', 'icon' => 'fa fa-circle-o', 'url' => ['/screen/bmistate']],
                            ],
                    ],
                    [
                        'label' => 'แล็บ',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' =>
                            [
                                ['label' => 'รายงานแล็บที่ตรวจหาสารเสพติด', 'icon' => 'fa fa-circle-o', 'url' => ['/lab/inurine']],
                            ],
                    ],
                    [
                        'label' => 'ห้องยา',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' =>
                        [
                            ['label' => 'การบริการวันนี้', 'icon' => 'fa fa-circle-o', 'url' => ['/drug/index']],
                            ['label' => 'รายงานผู้ป่วย DUE', 'icon' => 'fa fa-circle-o', 'url' => ['/drug/due']],
                            ['label' => 'จำนวนใบสั่งยาผู้ป่วยนอก(เดือน)', 'icon' => 'fa fa-circle-o', 'url' => ['/drug/rxopd']],
                            ['label' => 'จำนวนใบสั่งยาผู้ป่วยใน(เดือน)', 'icon' => 'fa fa-circle-o', 'url' => ['/drug/rxipd']],
                            ['label' => 'รายงานผู้ป่วยแพ้ยา', 'icon' => 'fa fa-circle-o', 'url' => ['/drug/allergy']],                            
                        ],
                    ],
                    [
                        'label' => 'จัดเก็บ',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' =>
                        [
                            ['label' => 'รายได้แยกตามกลุ่มสิทธิ', 'icon' => 'fa fa-circle-o', 'url' => ['/income/index']],
                            ['label' => 'รายได้แยกตามหมวดการรักษา', 'icon' => 'fa fa-circle-o', 'url' => ['/income/group']],
                        ]
                    ],
                    //IPD Menu
                    ['label' => 'IPD', 'options' => ['class' => 'header']],
                    [
                        'label' => 'สถิติ',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' =>
                            [
                                ['label' => 'test', 'icon' => 'fa fa-circle-o', 'url' => '#'],
                            ],
                    ],
                    [
                        'label' => 'Admit',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' =>
                            [
                                ['label' => 'test', 'icon' => 'fa fa-circle-o', 'url' => '#'],
                            ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
