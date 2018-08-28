<?php

namespace common\modules\settings\components;

use Yii;

/**
 * Class SettingsAction
 * @package backend\components
 */
class SettingsAction extends \pheme\settings\SettingsAction
{
    /**
     * @return string
     */
    public function run()
    {
        /* @var $model \yii\db\ActiveRecord */
        $model = new $this->modelClass();
        if ($this->scenario) {
            $model->setScenario($this->scenario);
        }
        foreach ($model->attributes() as $key) {
            $value = Yii::$app->settings->get($key, $model->formName());
            if (is_object($value)) {
                $model->{$key} = $value->scalar;
            } else {
                $model->{$key} = $value;
            }
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($model->toArray() as $key => $value) {
                Yii::$app->settings->set($key, $value, $model->formName());
            }
            Yii::$app->getSession()->setFlash('success', Yii::t('settings', 'Данные сохранены'));
        }

        return $this->controller->render($this->viewName, ['model' => $model]);
    }
}
