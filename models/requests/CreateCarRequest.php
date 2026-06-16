<?php

namespace app\models\requests;

use yii\base\Model;

final class CreateCarRequest extends Model
{
    public $title = null;
    public $description = null;
    public $price = null;
    public $photo_url = null;
    public $contacts = null;
    public $options = null;

    private bool $optionsProvided = false;

    public function rules(): array
    {
        return [
            [["title", "description", "price", "photo_url", "contacts"], "required"],
            [["title", "photo_url", "contacts"], "string", "max" => 255],
            [["description"], "string"],
            [["price"], "number", "min" => 0],
            [["options"], "validateOptions"],
        ];
    }

    public function loadData(array $data): void
    {
        $this->title = $data["title"] ?? null;
        $this->description = $data["description"] ?? null;
        $this->price = $data["price"] ?? null;
        $this->photo_url = $data["photo_url"] ?? null;
        $this->contacts = $data["contacts"] ?? null;
        $this->optionsProvided = array_key_exists("options", $data);
        $this->options = $this->optionsProvided ? $data["options"] : null;
    }

    public function validateOptions(string $attribute): void
    {
        if (!$this->optionsProvided || $this->options === null) {
            return;
        }

        if (!is_array($this->options)) {
            $this->addError($attribute, "Options must be an object or null.");
            return;
        }

        foreach (["brand", "model", "year", "body", "mileage"] as $field) {
            $missingValue = !array_key_exists($field, $this->options)
                || $this->options[$field] === ""
                || $this->options[$field] === null;

            if ($missingValue) {
                $this->addError($attribute, "Option field '{$field}' is required.");
            }
        }

        if (array_key_exists("year", $this->options) && filter_var($this->options["year"], FILTER_VALIDATE_INT) === false) {
            $this->addError($attribute, "Option field 'year' must be an integer.");
        }

        if (array_key_exists("mileage", $this->options) && filter_var($this->options["mileage"], FILTER_VALIDATE_INT) === false) {
            $this->addError($attribute, "Option field 'mileage' must be an integer.");
        }
    }

    public function hasOptions(): bool
    {
        return $this->optionsProvided && is_array($this->options);
    }

    public function getOptionData(): ?array
    {
        return $this->hasOptions() ? $this->options : null;
    }
}
