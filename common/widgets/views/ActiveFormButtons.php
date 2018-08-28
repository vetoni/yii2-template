<?php

use yii\bootstrap\Html;

/**
 * @var boolean $isNewRecord
 */

$return_url = Yii::$app->request->get('return_url');

?>

<?php echo Html::submitButton($isNewRecord ? Yii::t('app', 'Создать') : Yii::t('app', 'Сохранить'),
    ['class' => 'btn btn-success']) ?>

<?php echo Html::submitButton($isNewRecord ? Yii::t('app', 'Создать и вернуться') : Yii::t('app', 'Сохранить и вернуться'),
    ['class' => 'btn btn-primary', 'name' => 'apply']) ?>

<?php echo Html::a(Yii::t('app', 'Отменить'), $return_url ?: ['index'], ['class' => 'btn btn-default']) ?>