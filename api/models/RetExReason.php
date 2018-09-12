<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\RetExReason as BaseRetExReason;

/**
 * This is the model class for table "ret_ex_reason".
 *
 * @property int $id
 * @property string $reason_ru  Текст причины возврата
 * @property string $reason_uz Текст причина на узбекском
 * @property string $description_ru Описание причины на русском
 * @property string $description_uz Описание причины на узбекском
 * @property int $position
 *
 * @property ReturnTicket[] $returnTickets
 */
class RetExReason extends BaseRetExReason
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReturnTickets()
    {
        return $this->hasMany(ReturnTicket::className(), ['ret_ex_reason_id' => 'id']);
    }
}
