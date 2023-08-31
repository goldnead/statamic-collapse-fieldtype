<?php

namespace Goldnead\CollapseFieldtype\Fieldtypes;

use Statamic\Fields\Fields;
use Statamic\Fields\Fieldtype;

class Collapse extends Fieldtype
{
    public $icon = 'section';

    protected $defaultValue = [];

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

        return collect($data)->map(function ($group, $i) {
            return $this->fields()->addValues($group)->preProcess()->values()->all();
        })->all();
    }

    public function fields()
    {
        return new Fields($this->config('fields'), $this->field()->parent(), $this->field());
    }

    public function preload()
    {
        $defaults = [
            'defaults' => $this->fields()->all()->map(function ($field) {
                return $field->fieldtype()->preProcess($field->defaultValue());
            })->all(),
        ];

        return $defaults;
    }

    /**
     * Process the data before it gets saved.
     *
     * @param  mixed  $data
     * @return array|mixed
     */
    public function process($data)
    {
        $defaults = $this->fields()->all()->map(function ($field) {
          return $field->fieldtype()->process($field->defaultValue());
        });

        return collect($defaults)->merge($data)->all();
    }
}
