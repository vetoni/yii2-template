<?php

namespace common\modules\user\controllers;

use common\modules\user\models\AdminLoginForm;
use common\modules\user\models\User;
use common\modules\user\models\UserSearch;
use common\modules\user\traits\ModuleTrait;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class ManagementController
 * @package common\modules\user\controllers
 */
class ManagementController extends Controller
{
    use ModuleTrait;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => $this->module->adminRoles,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'main-login';

        $model = new AdminLoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('/admin/login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('/management/index', compact('searchModel', 'dataProvider'));
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();
        $model->setScenario('create');
        $model->loadDefaultValues();
        return $this->form($model);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update');
        return $this->form($model);
    }

    /**
     * @param User $model
     * @return string|\yii\web\Response
     */
    protected function form($model)
    {
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        $viewName = $model->isNewRecord ? 'create' : 'update';
        return $this->render($viewName, compact('model'));
    }

    /**
     * @param $id
     * @return User
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException();
        }
    }
}