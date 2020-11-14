<?php

namespace backend\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "apple_color".
 *
 * @property int $id
 * @property string|null $color
 *
 * @property Apple[] $apples
 */
class AppleColor extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'apple_color';
    }

    public function rules(): array
    {
        return [
            [['color'], 'required'],
            [['color'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'color' => 'Цвет',
        ];
    }

    public function getApples(): ActiveQuery
    {
        return $this->hasMany(Apple::class, ['color_id' => 'id']);
    }
}
