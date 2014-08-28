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

/**
 * Description of FieldType
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
abstract class FieldType {
    
    const STRING = 'STRING';
    
    const DATE = 'DATE';
    const TIME = 'TIME';
    const TIMESTAMP = 'TIMESTAMP';
    
    const INTEGER = 'INTEGER';
    const FLOAT = 'FLOAT';
    const DECIMAL = 'DECIMAL';
    const MONEY = 'MONEY';
    
    protected $options;
    protected $typeprefix;
    
    function __construct($options=null) {
        if (empty($option)) {
            $options = ConfigurationLoader::getInstance()->getConfiguration()->getOptions();
        }
        $this->options = $options;
    }
    
    public function render($value){
        return $this->format($this->toPrimitiveType($value));
    }
    
    public function getOption($optionName) {
        if (isset($this->options["simpletablereport.{$this->typeprefix}.{$optionName}"])) {
            return $this->options["simpletablereport.{$this->typeprefix}.{$optionName}"];
        } elseif (isset($this->options["simpletablereport.{$optionName}"])) {
            return $this->options["simpletablereport.{$optionName}"];
        }
        return null;
    }

    public abstract function format($value);
    public abstract function toPrimitiveType($value);

}
