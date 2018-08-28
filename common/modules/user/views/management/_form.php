<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \common\modules\user\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model) ?>

    <?= Html::passwordInput('password_fake', '') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'roles')->widget(Select2::className(), [
        'data' => ArrayHelper::map(\Yii::$app->getAuthManager()->getRoles(), 'name', 'name'),
        'pluginOptions' => [
            'multiple' => true,
        ]
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusOptions(), ['class' => 'form-control']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('user', 'Создать') : Yii::t('user', 'Сохранить'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

<?php ActiveForm::end(); ?>