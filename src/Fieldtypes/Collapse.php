<?php

namespace Goldnead\CollapseFieldtype\Fieldtypes;

use Statamic\Fields\Fields;
use Statamic\Fields\Fieldtype;
use Statamic\Support\Arr;

class Collapse extends Fieldtype
{
    public $icon = 'section';

    protected $defaultValue = [];

    protected $defaultable = false;

    protected $categories = ['structured'];

    /**
     * The blank/default value.
     *
     * @return array
     */
    public function defaultValue()
    {
        return null;
    }

    protected function configFieldItems(): array
    {
        return [
            'fields' => [
                'display' => __('Fields'),
                'instructions' => __('statamic::fieldtypes.grid.config.fields'),
                'type' => 'fields',
            ],
        ];
    }

    /**
     * Pre-process the data before it gets sent to the publish page.
     *
     * @param  mixed  $data
     * @return array|mixed
     */
    public function preProcess($data)
    {
        return $data;
        dd($data);
    }

    public function fields()
    {
        return new Fields($this->config('fields'), $this->field()->parent(), $this->field());
    }

    /**
     * Preload default/existing data on the publish page.
     *
     * @return array|mixed
     */
    public function preload()
    {
        $existing = [];
        $this->fields()->items()->each(function ($row) use (&$existing) {
            $handle = $row['handle'];

            if (! $this->field->value()) {
                $existing[$handle] = null;
            } else {
                $existing[$handle] = $this->field->value()[$handle] ?? null;
            }
        });

        $defaults = $this->fields()->all()->map(function ($field) {
            $preload = $field->fieldtype()->preload();

            return is_array($preload) ? $preload['defaults'] : $preload;
        })->all();

        return [
            'defaults' => $defaults,
            'existing' => $existing,
        ];
    }

    public function rules(): array
    {
        return ['array'];
    }

    // public function extraRules(): array
    // {
    //     $rules = $this
    //         ->fields()
    //         ->addValues($this->field->value() ?? [])
    //         ->validator()
    //         ->rules();

    //     return collect($rules)->mapWithKeys(function ($rules, $handle) {
    //         return [$this->field->handle().'.'.$handle => $rules];
    //     })->all();
    // }

    /**
     * Process the data before it gets saved.
     *
     * @param  mixed  $data
     * @return array|mixed
     */
    public function process($data)
    {
        // $fields = $this->fields()->addValues($data)->process()->values()->all();

        // return Arr::removeNullValues($fields);

        return $data;
    }

    public function preProcessValidatable($value)
    {
        $processed = $this->fields()
            ->addValues($value)
            ->preProcessValidatables()
            ->values()
            ->all();

        return $processed;
    }
}
