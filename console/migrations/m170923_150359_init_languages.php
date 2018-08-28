<?php

use yii\db\Migration;

class m170923_150359_init_languages extends Migration
{
    protected $languages = [
        ['ru', 'ru', 'ru', 'Русский', 'Russian', 1, 0],
        ['uk', 'uk', 'ua', 'Українська', 'Ukrainian', 1, 1],
    ];

    public function safeUp()
    {
        $this->addColumn('{{%language}}', 'sort_order', $this->integer() . ' NOT NULL');
        $this->delete('{{%language}}', '1=1');
        $this->batchInsert('{{%language}}', [
            'language_id',
            'language',
            'country',
            'name',
            'name_ascii',
            'status',
            'sort_order'
        ], $this->languages);
    }

    public function safeDown()
    {
        $this->dropColumn('{{%language}}', 'sort_order');
    }
}
