<?php

declare(strict_types=1);

use yii\db\Migration;

class m230131_154112_create_utm_umarov_table extends Migration
{
    private const TABLE = 'utm_umarov';

    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $tableOptions = 'CHARACTER SET utf8mb4 ENGINE=InnoDB';
        
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(),
                'invoice_id' => $this->integer()->notNull()->defaultValue(0),
                'source' => $this->string(),
                'medium' => $this->string(),
                'campaign' => $this->string(),
                'param' => $this->string(),
                'term' => $this->string(),
                'content' => $this->string(),
                'created_at' => $this->dateTime(),
            ],
            $tableOptions
        );

        $this->createIndex('index_invoice', self::TABLE, 'invoice_id');

        $this->addForeignKey(
            'fk-invoice_umarov-id',
            self::TABLE,
            'invoice_id',
            'invoice_umarov',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable(self::TABLE);
    }
}