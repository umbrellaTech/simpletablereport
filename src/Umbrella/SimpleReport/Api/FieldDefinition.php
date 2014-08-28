<?php

/*
 * Copyright 2013 kelsoncm.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Umbrella\SimpleReport\Api;

use Exception;

/**
 *
 * @author kelsoncm
 */
class FieldDefinition
{

    private static $loadedFieldsTypes = array();
    private $fieldName;
    private $fieldType;
    private $fieldSize;
    private $fieldWidth;
    private $fieldOrder;
    private $fieldCaption;
    private $callback;

    function __construct($fieldName, $fieldCaption, $fieldType = FieldType::STRING, $fieldSize = null, $fieldWidth = null, $callback = null)
    {
        $this->fieldName = $fieldName;
        $this->fieldType = $fieldType;
        $this->fieldSize = $fieldSize;
        $this->fieldWidth = $fieldWidth;
        $this->fieldCaption = $fieldCaption;
        $this->callback = $callback;
    }

    public function getFieldName()
    {
        return $this->fieldName;
    }

    public function getFieldType()
    {
        return $this->fieldType;
    }

    public function getFieldTypeInstance($strategy = '')
    {
        $key = "{$this->fieldType}, {$strategy}";
        if (!isset(FieldDefinition::$loadedFieldsTypes[$key])) {
            FieldDefinition::$loadedFieldsTypes[$key] = $this->createFieldType($this->fieldType, $strategy);
        }
        return FieldDefinition::$loadedFieldsTypes[$key];
    }

    public function getFieldSize()
    {
        return $this->fieldSize;
    }

    public function getFieldWidth()
    {
        return $this->fieldWidth;
    }

    public function getFieldOrder()
    {
        return $this->fieldOrder;
    }

    public function getFieldCaption()
    {
        return $this->fieldCaption;
    }

    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function setFieldType($fieldType)
    {
        $this->fieldType = $fieldType;
    }

    public function setFieldSize($fieldSize)
    {
        $this->fieldSize = $fieldSize;
    }

    public function setFieldWidth($fieldWidth)
    {
        $this->fieldWidth = $fieldWidth;
    }

    public function setFieldOrder($fieldOrder)
    {
        $this->fieldOrder = $fieldOrder;
    }

    public function setFieldCaption($fieldCaption)
    {
        $this->fieldCaption = $fieldCaption;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    public function executeCallback($value, IDatasource $datasource)
    {
        $callback = $this->callback;

        if (!$callback instanceof \Closure) {
            throw new \InvalidArgumentException('The callback must be a callable');
        }

        return $callback($value, $datasource->current());
    }

    protected function createFieldType($fieldTypeName, $strategy)
    {
        $classnameBase = ucfirst(strtolower($fieldTypeName)) . 'Type';
        $classnameConcrete = ucfirst(strtolower($strategy)) . $classnameBase;
        if (class_exists($classnameConcrete, true)) {
            return new $classnameConcrete(array());
        } elseif (class_exists($classnameBase, true)) {
            return new $classnameBase(array());
        } else {
            throw new Exception("Field class don't exists for field type '{$fieldTypeName}'.");
        }
    }

}
