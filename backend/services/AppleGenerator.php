<?php


namespace backend\services;


use backend\models\Apple;
use backend\models\AppleColor;
use backend\models\AppleState;
use yii\helpers\ArrayHelper;

class AppleGenerator
{
    private ?array $colors = null;

    public function generateMany(): void
    {
        Apple::deleteAll();
        $appleCount = mt_rand(10, 20);
        for ($i = 0; $i < $appleCount; $i++) {
            $this->generateOne();
        }
    }

    public function generateOne(): void
    {
        $apple = new Apple();
        $apple->color_id = array_rand($this->getColors());

        $appearedAt = new \DateTime(date('Y-m-d'));
        $appearedAt->setTime(mt_rand(0, 24), mt_rand(0, 59), 0);
        $apple->appeared_at = $appearedAt->format('Y-m-d H:i:s');

        $stateIds = [
            AppleState::ID_HANGING_ON_A_TREE,
            AppleState::ID_LIES_ON_THE_GROUND
        ];
        $stateKey = array_rand($stateIds);
        $stateId = $stateIds[$stateKey];
        if ($stateId == AppleState::ID_LIES_ON_THE_GROUND) {
            $fallAt = new \DateTime(date('Y-m-d'));
            $fallAt->setTime(mt_rand($appearedAt->format('H'), 24), mt_rand($appearedAt->format('i'), 59), 0);
            $apple->fall_at = $fallAt->format('Y-m-d H:i:s');

            $interval = $fallAt->diff($appearedAt);
            if ($interval->format('%I') > 5) {
                $stateId = AppleState::ID_ROTTEN_APPLE;
            }
        }
        $apple->state_id = $stateId;
        $apple->size = 1;
        $apple->save();
    }

    private function getColors(): array
    {
        if (!$this->colors) {
            $this->colors = ArrayHelper::map(AppleColor::find()->asArray()->all(), 'id', 'color');
        }

        return $this->colors;
    }
}
