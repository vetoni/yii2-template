<?php

/* @var yii\web\View $this */
/* @var \common\modules\user\models\User $model */

$this->title = "Создание пользователя";
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>