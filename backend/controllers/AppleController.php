<?php

namespace backend\controllers;

use backend\models\Apple;
use backend\services\AppleGenerator;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AppleController implements the CRUD actions for Apple model.
 */
class AppleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Отобразить все яблоки
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Apple::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGenerate()
    {
        (new AppleGenerator())->generateMany();

        $this->redirect('index');
    }

    /**
     * Упасть на землю
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionFallToGround(int $id)
    {
        $apple = $this->findModel($id);
        try {
            $apple->fallToGround();
            $apple->save();
            \Yii::$app->session->setFlash('success', 'Яблоко упало на землю');
        } catch (\Exception $exception) {
            \Yii::$app->session->setFlash('danger', $exception->getMessage());
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Apple::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Сьесть часть яблока
     *
     * @param int $id ID яблока
     * @param int $eatSize размер в процентах откусывания яблока
     * @return string
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionEat(int $id, int $eatSize)
    {
        $apple = $this->findModel($id);
        try {
            $apple->eat($eatSize);
            if ($apple->isEaten()) {
                $apple->delete();
                \Yii::$app->session->setFlash('success', 'Съели яблоко полностью');
            } else {
                $apple->save();
                \Yii::$app->session->setFlash('success', 'Съели ' . $eatSize . '% яблока');
            }
        } catch (\Exception $exception) {
            \Yii::$app->session->setFlash('danger', $exception->getMessage());
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Apple::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Apple model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Apple the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = Apple::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница с товаром не найдена');
    }
}
