<?php

use common\modules\file\models\File;
use yii\db\Migration;

/**
 * Class m171209_154811_add_file_table
 */
class m171209_154811_add_file_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(File::tableName(), [
            'id' => $this->primaryKey(),
            'model' => $this->string()->notNull(),
            'attribute' => $this->string()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'path' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'extension' => $this->string(16),
            'mime_type' => $this->string(45),
            'size' => $this->bigInteger(32),
            'image_width' => $this->integer(),
            'image_height' => $this->integer(),
            'sort' => $this->integer()->notNull(),
            'sort_group' => $this->string()->notNull(),
        ]);

        $this->createIndex('file_model_attribute_item_id', '{{%file}}', ['model', 'attribute', 'item_id']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable(File::tableName());
    }
}
