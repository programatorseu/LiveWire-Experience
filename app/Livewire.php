<?php

namespace App;

use Illuminate\Support\Facades\Blade;
use ReflectionClass;
use ReflectionProperty;

class Livewire
{

    public function fromSnapshot($snapshot)
    {
        $class = $snapshot['class'];
        $data = $snapshot['data'];
        $component = new $class;
        $this->setProperties($component, $data);
        return $component;
    }
    public function callMethod($component, $method)
    {
        $component->$method();
    }
    public function setProperties($component, $properties)
    {
        foreach ($properties as $key => $value) {
            $component->{$key} = $value;
        }
    }

    public function toSnapshot($component)
    {
        $html =  Blade::render(
            $component->render(),
            $this->getProperties($component)
        );
        $snapshot = [
            'class' => get_class($component),
            'data' => $this->getProperties($component),
        ];
        return [$html, $snapshot];
    }

    function initialRender($class)
    {
        $component = new $class;
        [$html, $snapshot] = $this->toSnapshot($component);
        $snapshotAttr = htmlentities(json_encode($snapshot));
        return <<<HTML
                <div wire:snapshot="{$snapshotAttr}">
                    {$html}
                </div>
        HTML;
    }
    function getProperties($component)
    {
        $properties = [];
        $reflectedProperties = (new ReflectionClass($component))->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($reflectedProperties as $property) {
            $properties[$property->getName()] = $property->getValue($component);
        }
        return $properties;
    }
}
