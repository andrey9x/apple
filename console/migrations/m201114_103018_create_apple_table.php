<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apple}}`.
 */
class m201114_103018_create_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple_color}}', [
            'id' => $this->primaryKey()->unsigned(),
            'color' => $this->string()->notNull(),
        ]);

        $this->createTable('{{%apple_state}}', [
            'id' => $this->primaryKey()->unsigned(),
            'state' => $this->string()->notNull(),
        ]);

        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey()->unsigned(),
            'color_id' => $this->integer()->unsigned()->notNull(),
            'state_id' => $this->integer()->unsigned()->notNull(),
            'size' => $this->decimal(3, 2)->notNull(),
            'appeared_at' => $this->timestamp()->notNull(),
            'fall_at' => $this->timestamp(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => 'timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->addForeignKey('fk_apple__color_id', '{{%apple}}', 'color_id', '{{%apple_color}}', 'id');
        $this->addForeignKey('fk_apple__state_id', '{{%apple}}', 'state_id', '{{%apple_state}}', 'id');

        $this->fillTableAppleState();
        $this->fillTableAppleColor();
    }

    private function fillTableAppleState()
    {
        $this->batchInsert('{{%apple_state}}', ['state'], [
            ['state' => 'висит на дереве'],
            ['state' => 'упало/лежит на земле'],
            ['state' => 'гнилое яблоко'],
        ]);
    }

    private function fillTableAppleColor()
    {
        $this->batchInsert('{{%apple_color}}', ['color'], [
            ['color' => 'green'],
            ['color' => 'red'],
            ['color' => 'yellow'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_apple__state_id', '{{%apple}}');
        $this->dropForeignKey('fk_apple__color_id', '{{%apple}}');

        $this->dropTable('{{%apple}}');
        $this->dropTable('{{%apple_state}}');
        $this->dropTable('{{%apple_color}}');
    }
}
