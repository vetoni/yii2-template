<?php

use common\modules\user\models\UserSearch;
use kartik\date\DatePicker;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/**
 * @var UserSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('user', 'Пользователи');
?>

<div class="page-index">

    <div class="form-group">
        <?= Html::a(Yii::t('user', 'Создать'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?= GridView::widget([
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'username',
            'email',
            [
                'attribute' => 'roles',
                'filter' => ArrayHelper::map(\Yii::$app->getAuthManager()->getRoles(), 'name', 'name'),
                'value' => 'roleListAsString'
            ],
            [
                'attribute' => 'created_at',
                'format' => 'date',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',

                ])
            ],
            [
                'attribute' => 'status',
                'filter' => $searchModel->getStatusOptions(),
                'value' => 'statusText'
            ],
            [
               'class' => \yii\grid\ActionColumn::className(),
               'template' => '{update}{delete}'
            ]
    ]
    ]) ?>

</div>