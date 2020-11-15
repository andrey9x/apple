<?php

namespace backend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "apple_state".
 *
 * @property int $id
 * @property string|null $state
 *
 * @property Apple[] $apples
 */
class AppleState extends ActiveRecord
{
    public const ID_HANGING_ON_A_TREE = 1;
    public const ID_LIES_ON_THE_GROUND = 2;
    public const ID_ROTTEN_APPLE = 3;

    public static function tableName(): string
    {
        return 'apple_state';
    }

    public function rules(): array
    {
        return [
            [['state'], 'required'],
            [['state'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'state' => 'Состояние',
        ];
    }

    public function getApples(): ActiveQuery
    {
        return $this->hasMany(Apple::class, ['state_id' => 'id']);
    }
}
