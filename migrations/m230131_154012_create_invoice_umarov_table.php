<?php

declare(strict_types=1);

use yii\db\Migration;

class m230131_154012_create_invoice_umarov_table extends Migration
{
    private const TABLE = 'invoice_umarov';

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
                'name' => $this->string(),
                'surname' => $this->string(),
                'email' => $this->string(),
                'type_id' => $this->tinyInteger()->notNull()->defaultValue(0),
                'money' => $this->decimal(15, 2)->notNull()->defaultValue(0),
                'status' => $this->tinyInteger()->notNull()->defaultValue(0),
                'created_at' => $this->dateTime(),
                'is_deleted' => $this->tinyInteger()->notNull()->defaultValue(0),
                'payment_id' => $this->string(),
                'paid_at' => $this->dateTime(),
                'status_yookassa' => $this->string(),
                'yookassa_cancellation_details' => $this->string(),
                'token_payment' => $this->string(32),
            ],
            $tableOptions
        );

        $this->createIndex('type_indx', self::TABLE, ['type_id']);
        $this->createIndex('payment_id', self::TABLE, ['payment_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable(self::TABLE);
    }
}