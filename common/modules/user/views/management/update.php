<?php

/* @var yii\web\View $this */
/* @var \common\modules\user\models\User $model */

$this->title = "Обновление пользователя: \"{$model->username}\"";
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->username;
?>

<div class="page-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>