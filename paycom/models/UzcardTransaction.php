<?php

namespace frontend\modules\paycom\models;

use Yii;

/**
 * This is the model class for table "uzcard_transaction".
 *
 * @property int $id
 * @property int $amount
 * @property string $tran
 * @property string $create_time
 * @property string $data
 * @property int $is_done
 * @property int $state
 * @property int $reject_reason
 * @property string $create_time_remote
 * @property string $transaction_time
 * @property string $cancel_time
 * @property int $order_id
 *
 * @property Order $order
 */
class UzcardTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uzcard_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'create_time', 'is_done', 'state', 'reject_reason', 'create_time_remote', 'transaction_time', 'cancel_time', 'order_id'], 'integer'],
            [['data'], 'string'],
            [['order_id'], 'required'],
            [['tran'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'amount' => 'Amount',
            'tran' => 'Tran',
            'create_time' => 'Create Time',
            'data' => 'Data',
            'is_done' => 'Is Done',
            'state' => 'State',
            'reject_reason' => 'Reject Reason',
            'create_time_remote' => 'Create Time Remote',
            'transaction_time' => 'Transaction Time',
            'cancel_time' => 'Cancel Time',
            'order_id' => 'Order ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
