<?php

/*
 * Copyright 2013 kelsoncm <falecom@kelsoncm.com>.
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

namespace Umbrella\SimpleReport;

use Exception;

/**
 * Description of BaseConfiguration
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class BaseConfiguration
{

    private $option;
    private $loadedFields = array();

    function __construct($option)
    {
        $this->option = new \Easy\Collections\Dictionry($option);
    }

    public function getOption($name)
    {
        return $this->option->get($name);
    }

    public function getOptions()
    {
        return $this->option;
    }

    public function getFieldTypeInstance($fieldTypeName, $rendererPrefix)
    {
        $key = "$fieldTypeName, $rendererPrefix";
        if (!isset($this->loadedFields[$key])) {
            $this->loadedFields[$key] = $this->createFieldTypeInstance($fieldTypeName, $rendererPrefix);
        }
        return $this->loadedFields[$key];
    }

    protected function createFieldTypeInstance($fieldTypeName, $rendererPrefix)
    {
        $classnameBase = ucfirst(strtolower($fieldTypeName)) . 'Type';
        $classnameConcrete = "Umbrella\SimpleReport\Fields\\" . ucfirst(strtolower($rendererPrefix)) . $classnameBase;
        if (class_exists($classnameConcrete)) {
            return new $classnameConcrete($this->option);
        } elseif (class_exists($classnameBase)) {
            return new $classnameBase($this->option);
        } else {
            throw new Exception("Field class don't exists for field type '{$fieldTypeName}'.");
        }
    }

}
