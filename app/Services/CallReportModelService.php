<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CallReportModelService {
    // It's bad that MODEL_CLASS_MAP is here, but for the sake of simplicity, I will leave it like that
    public const array MODEL_CLASS_MAP = [
        'customer' => \App\Models\Customer::class,
        'agent' => \App\Models\Agent::class,
        'call' => \App\Models\Call::class,
    ];
    /**
     * @throws \Exception
     */
    public static function fieldExistsInModel(string $modelName, string $field): bool {
        if (!in_array($modelName, array_keys(self::MODEL_CLASS_MAP))) {
            throw new \Exception("Model {$modelName} not found");
        }
        $class = self::MODEL_CLASS_MAP[$modelName];
        $model = new $class;
        $modelFields = self::getModelFields($model);
        return in_array($field, $modelFields);
    }

    // Dynamically get the fields of a model
    public static function getModelFields(Model $model): array {
        $fillable = $model->getFillable();
        $genericFields = [
            $model->getKeyName(),
        ];
        return array_merge($fillable, $genericFields);
    }

    public static function getModelsFields(): array {
        $models = [];
        foreach (self::MODEL_CLASS_MAP as $modelClass) {
            $model = new $modelClass;
            $fields = self::getModelFields($model);
            $table = Str::singular($model->getTable());
            $models[$table] = $fields;
        }
        return $models;
    }
}
