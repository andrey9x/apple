<?php

namespace backend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "apple".
 *
 * @property int $id
 * @property int $color_id
 * @property int $state_id
 * @property float $size
 * @property string $appeared_at
 * @property string|null $fall_at
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property AppleColor $color
 * @property AppleState $state
 */
class Apple extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'apple';
    }

    public function rules(): array
    {
        return [
            [['color_id', 'state_id', 'size', 'appeared_at'], 'required'],
            [['color_id', 'state_id'], 'integer'],
            [['size'], 'number'],
            [['appeared_at', 'fall_at', 'created_at', 'updated_at'], 'safe'],
            [['color_id'], 'exist', 'skipOnError' => true, 'targetClass' => AppleColor::class, 'targetAttribute' => ['color_id' => 'id']],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => AppleState::class, 'targetAttribute' => ['state_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'color_id' => 'Цвет',
            'state_id' => 'Состояние',
            'size' => 'Цельность',
            'appeared_at' => 'Дата появления',
            'fall_at' => 'Дата падения',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function getColor(): ActiveQuery
    {
        return $this->hasOne(AppleColor::class, ['id' => 'color_id']);
    }

    public function getState(): ActiveQuery
    {
        return $this->hasOne(AppleState::class, ['id' => 'state_id']);
    }

    /**
     * Упасть на землю
     */
    public function fallToGround()
    {
        if (in_array($this->state_id, [AppleState::ID_LIES_ON_THE_GROUND, AppleState::ID_ROTTEN_APPLE])) {
            throw new \Exception('Яблоко уже на земле');
        }
        if ($this->state_id == AppleState::ID_HANGING_ON_A_TREE) {
            $this->state_id = AppleState::ID_LIES_ON_THE_GROUND;
            $this->fall_at = date('Y-m-d H:i:s');
        }
    }

    /**
     * Съесть
     *
     * @param int $eatenSize 0-100%
     */
    public function eat(int $eatenSize)
    {
        if ($this->state_id == AppleState::ID_HANGING_ON_A_TREE) {
            throw new \Exception('Съесть нельзя, яблоко на дереве');
        }
        if ($this->state_id == AppleState::ID_ROTTEN_APPLE) {
            throw new \Exception('Съесть нельзя, яблоко сгнило');
        }
        if ($this->canEat()) {
            $this->size -= min($this->size, $eatenSize / 100);
        }
    }

    /**
     * @return bool Съедено полностью?
     */
    public function isEaten(): bool
    {
        return empty($this->size);
    }

    /**
     * @return bool Можно съесть?
     */
    public function canEat(): bool
    {
        return $this->state_id == AppleState::ID_LIES_ON_THE_GROUND;
    }
}
