<aside class="main-sidebar">

    <section class="sidebar">

        <?= backend\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [

                    [
                        'label' => Yii::t('backend', 'Каталог'),
                        'icon' => 'server',
                        'items' => [
                            [
                                'label' => Yii::t('backend', 'Категории'),
                                'url' => ['/catalog/category-management/index'],
                                'scope' => ['/catalog/category-management/index', '/catalog/category-management/update', '/catalog/category-management/create']
                            ],
                            [
                                'label' => Yii::t('backend', 'Товары'),
                                'url' => ['/catalog/product-management/index'],
                                'scope' => ['/catalog/product-management/index', '/catalog/product-management/update', '/catalog/product-management/create']
                            ],
                        ],
                    ],

                    [
                        'label' => Yii::t('backend', 'Пользователи'),
                        'icon' => 'user',
                        'items' => [
                            [
                                'label' => Yii::t('backend', 'Управление'),
                                'url' => ['/user/management/index'],
                                'scope' => ['/user/management/index', '/user/management/update', '/user/management/create']
                            ],
                        ],
                    ],

                    [
                        'label' => Yii::t('backend', 'Переводы'),
                        'icon' => 'language',
                        'items' => [
                            [
                                'label' => Yii::t('backend', 'Список языков'),
                                'url' => ['/translatemanager/language/list'],
                                'scope' => ['/translatemanager/language/list', '/translatemanager/language/view', '/translatemanager/language/update', '/translatemanager/language/translate']
                            ],
                            [
                                'label' => Yii::t('backend', 'Создать язык'),
                                'url' => ['/translatemanager/language/create'],
                                'scope' => ['/translatemanager/language/create']
                            ],
                            [
                                'label' => Yii::t('backend', 'Сканировать'),
                                'url' => ['/translatemanager/language/scan'],
                            ],
                            [
                                'label' => Yii::t('backend', 'Оптимизировать'),
                                'url' => ['/translatemanager/language/optimizer'],
                            ],
                        ]
                    ],
                    [
                        'label' => Yii::t('backend', 'Настройки'),
                        'icon' => 'cog',
                        'items' => [
                            ['label' => Yii::t('backend', 'Почта'), 'url' => ['/settings/mail/index']],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
