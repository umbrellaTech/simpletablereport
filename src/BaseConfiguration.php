<?php

namespace Umbrella\SimpleReport;

use Easy\Collections\Dictionary;
use Exception;

/**
 * Description of BaseConfiguration
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class BaseConfiguration
{
    private $options;
    private $loadedFields = array();

    function __construct($option)
    {
        $this->options = new Dictionary($option);
    }

    public function getOption($name)
    {
        return $this->options[$name];
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getFieldTypeInstance($fieldTypeName, $rendererPrefix)
    {
        $key = "$fieldTypeName, $rendererPrefix";
        if (!isset($this->loadedFields[$key])) {
            $this->loadedFields[$key] = $this->createFieldTypeInstance($fieldTypeName, $rendererPrefix);
        }
        return $this->loadedFields[$key];
    }

    protected function createFieldTypeInstance($fieldTypeName, $rendererPrefix = null)
    {
        $classnameBase = ucfirst(strtolower($fieldTypeName)) . 'Type';
        $prefix = $rendererPrefix ? ucfirst(strtolower($rendererPrefix)) . '\\' : '';

        $classnameConcrete = "Umbrella\SimpleReport\Fields\\" . $prefix . $classnameBase;

        if (class_exists($classnameConcrete)) {
            return new $classnameConcrete($this->options);
        } elseif (class_exists($classnameBase)) {
            return new $classnameBase($this->options);
        } else {
            throw new Exception("Field class don't exists for field type '{$fieldTypeName}'.");
        }
    }
}
