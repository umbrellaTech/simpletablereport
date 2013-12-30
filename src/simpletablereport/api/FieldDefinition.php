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

/**
 *
 * @author kelsoncm
 */
class FieldDefinition {
    private $fieldName;
    private $fieldType;
    private $fieldSize;
    private $fieldWidth;
    private $fieldOrder;
    private $fieldCaption;
            
    function __construct($fieldName, $fieldCaption, $fieldType=FieldType::STRING, $fieldSize=null, $fieldWidth=null) {
        $this->fieldName = $fieldName;
        $this->fieldType = $fieldType;
        $this->fieldSize = $fieldSize;
        $this->fieldWidth = $fieldWidth;
        $this->fieldCaption = $fieldCaption;
    }
    
    public function getFieldName() {
        return $this->fieldName;
    }

    public function getFieldType() {
        return $this->fieldType;
    }

    public function getFieldSize() {
        return $this->fieldSize;
    }

    public function getFieldWidth() {
        return $this->fieldWidth;
    }

    public function getFieldOrder() {
        return $this->fieldOrder;
    }

    public function getFieldCaption() {
        return $this->fieldCaption;
    }

    public function setFieldName($fieldName) {
        $this->fieldName = $fieldName;
    }

    public function setFieldType($fieldType) {
        $this->fieldType = $fieldType;
    }

    public function setFieldSize($fieldSize) {
        $this->fieldSize = $fieldSize;
    }

    public function setFieldWidth($fieldWidth) {
        $this->fieldWidth = $fieldWidth;
    }

    public function setFieldOrder($fieldOrder) {
        $this->fieldOrder = $fieldOrder;
    }

    public function setFieldCaption($fieldCaption) {
        $this->fieldCaption = $fieldCaption;
    }

}
