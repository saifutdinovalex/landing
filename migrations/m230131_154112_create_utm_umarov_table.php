<?php

use yii\db\Migration;

class m230131_154112_create_utm_umarov_table extends Migration
{
    const TABLE = 'utm_umarov';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = "CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB";
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'invoice_id' => $this->integer()->notNull()->defaultValue(0),
            'source' => $this->string(),
            'medium' => $this->string(),
            'campaign' => $this->string(),
            'param' => $this->string(),
            'term' => $this->string(),
            'content' => $this->string(),
            'created_at' => $this->dateTime(),
            
        ], $tableOptions);

        $this->createIndex('index_invoice', self::TABLE, 'invoice_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}