<?php

namespace common\components;

use Yii;

/**
 * Trait FormActionTrait
 * @package common\components
 */
trait FormActionTrait
{
    /**
     * @param $model
     * @return mixed
     */
    protected function stayOrRedirect($model)
    {
        if (isset($_POST['apply'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Изменения сохранены'));
            if ($this->action->id == 'create') {
                $this->redirect(['update', 'id' => $model->id, 'return_url' => Yii::$app->request->get('return_url')]);
                Yii::$app->end();
            }
        } else {
            return $this->redirectToList();
        }
    }

    /**
     * @return mixed
     */
    protected function redirectToList()
    {
        return $this->redirect(Yii::$app->request->get('return_url') ?: ['index']);
    }
}