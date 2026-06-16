<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property float $price
 * @property string $photo_url
 * @property string $contacts
 * @property string $created_at
 * @property CarOptionRecord|null $option
 */
final class CarRecord extends ActiveRecord
{
    public static function tableName(): string
    {
        return "{{%car}}";
    }

    public function rules(): array
    {
        return [
            [["title", "description", "price", "photo_url", "contacts"], "required"],
            [["description"], "string"],
            [["price"], "number", "min" => 0],
            [["title", "photo_url", "contacts"], "string", "max" => 255],
        ];
    }

    public function getOption(): ActiveQuery
    {
        return $this->hasOne(CarOptionRecord::class, ["car_id" => "id"]);
    }
}
