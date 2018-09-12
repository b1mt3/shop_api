<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Returnrequest as BaseReturnrequest;

/**
 * This is the model class for table "returnrequest".
 *
 * @property int $id
 * @property string $comment Комментарий
 * @property string $status Статус
 * @property int $order_id Заказ
 * @property int $is_new Новый
 * @property string $create_time Дата подачи заявки
 * @property string $update_time Дата её рассмотрения
 *
 * @property ReturnTicket[] $returnTickets
 * @property Order $order
 */
class Returnrequest extends BaseReturnrequest
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReturnTickets()
    {
        return $this->hasMany(ReturnTicket::className(), ['returnrequest_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
