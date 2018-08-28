<?php

use common\modules\settings\models\MailSettings;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var MailSettings $model
 */

$this->title = Yii::t('settings', 'Почта');
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>
<div class="page-update">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'settings', 'enctype' => 'multipart/form-data']]) ?>

        <?= $form->errorSummary($model) ?>

        <div class="panel panel-default">
            <div class="panel-heading"><?= Yii::t('settings', 'Почта') ?></div>
            <div class="panel-body">
                <?= $form->field($model, 'smtp_host') ?>

                <?= $form->field($model, 'smtp_username') ?>

                <?= $form->field($model, 'smtp_password') ?>

                <?= $form->field($model, 'smtp_port') ?>

                <?= $form->field($model, 'smtp_encryption') ?>

                <?= $form->field($model, 'smtp_usefiletransport')->checkbox() ?>
            </div>
        </div>

        <?php echo Html::submitButton(Yii::t('settings', 'Сохранить'), ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end() ?>
</div>