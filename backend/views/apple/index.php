<?php

use backend\models\Apple;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Яблоки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apple-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Сгенерировать 10-20 яблок', ['generate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin([
        'enablePushState' => false,
        'enableReplaceState' => false,
        'timeout' => 5000,
    ]); ?>

    <?php if (Yii::$app->session->hasFlash('danger')): ?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <?= Yii::$app->session->get('danger') ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <?= Yii::$app->session->get('success') ?>
        </div>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            [
                'attribute' => 'color_id',
                'format' => 'raw',
                'value' => static function(Apple $apple) {
                    return $apple->color->color;
                },
            ],
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'value' => static function(Apple $apple) {
                    return $apple->state->state;
                },
            ],
            [
                'attribute' => 'size',
                'format' => 'raw',
                'header' => 'Сколько съели',
                'value' => static function(Apple $apple) {
                    return ((1 - $apple->size) * 100) . '%';
                },
            ],
            'appeared_at',
            'fall_at',
            [
                'class' => ActionColumn::class,
                'template' => '{fall-to-ground} {eat-25} {eat-50} {eat-100}',
                'buttons' => [
                    'fall-to-ground' => static function(string $url, Apple $apple, $key) {
                        return Html::a('упасть',
                            $url,
                            [
                                'class' => 'btn btn-xs btn-default fall',
                                'id' => $apple->id,
                                'data-id' => $apple->id,
                                'data-pjax' => 1
                            ]
                        );
                    },
                    'eat-25' => static function (string $url, Apple $apple, $key) {
                        return Html::a('сьесть 25%',
                            Url::to(['eat', 'id' => $apple->id, 'eatSize' => 25]),
                            [
                                'class' => 'btn btn-xs btn-default eat-25',
                                'id' => $apple->id,
                                'data-id' => $apple->id,
                                'data-pjax' => 1
                            ]
                        );
                    },
                    'eat-50' => static function (string $url, Apple $apple, $key) {
                        return Html::a('сьесть 50%',
                            Url::to(['eat', 'id' => $apple->id, 'eatSize' => 50]),
                            [
                                'class' => 'btn btn-xs btn-default eat-50',
                                'id' => $apple->id,
                                'eat' => 50,
                                'data-id' => $apple->id,
                                'data-pjax' => 1
                            ]
                        );
                    },
                    'eat-100' => static function (string $url, Apple $apple, $key) {
                        return Html::a('сьесть 100%',
                            Url::to(['eat', 'id' => $apple->id, 'eatSize' => 100]),
                            [
                                'class' => 'btn btn-xs btn-default eat-100',
                                'id' => $apple->id,
                                'data-id' => $apple->id,
                                'data-pjax' => 1
                            ]
                        );
                    },
                ],
            ],
            //'created_at',
            //'updated_at',
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
