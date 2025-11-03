<?php

declare(strict_types=1);

namespace ar;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "utm_umarov".
 *
 * @property int $id
 * @property int $invoice_id
 * @property string|null $source
 * @property string|null $medium
 * @property string|null $campaign
 * @property string|null $param
 * @property string|null $term
 * @property string|null $content
 * @property string|null $created_at
 */
class ArUtmUmarov extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'utm_umarov';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['invoice_id'], 'integer'],
            [['created_at'], 'safe'],
            [
                ['source', 'medium', 'campaign', 'param', 'term', 'content'],
                'string',
                'max' => 255,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'source' => 'Source',
            'medium' => 'Medium',
            'campaign' => 'Campaign',
            'param' => 'Param',
            'term' => 'Term',
            'content' => 'Content',
            'created_at' => 'Created At',
        ];
    }
}