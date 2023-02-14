<?php

namespace ar;

use Yii;

/**
 * This is the model class for table "utm_umarov".
 *
 * @property int $id
 * @property int $invoice_id
 * @property string $source
 * @property string $medium
 * @property string $campaign
 * @property string $param
 * @property string $term
 * @property string $content
 * @property string $created_at
 */
class ArUtmUmarov extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'utm_umarov';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id'], 'integer'],
            [['created_at'], 'safe'],
            [['source', 'medium', 'campaign', 'param', 'term', 'content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
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
