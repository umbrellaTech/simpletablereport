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
namespace simre;

/**
 * Description of AbstractDatasourceIterator
 *
 * @author kelsoncm
 */
class SimpleArrayDatasourceIterator extends \ArrayIterator implements \simre\IDatasourceIterator {
    
    private $fieldSet;
    
    function __construct(FieldSet $fieldSet, $rows) {
        parent::__construct($rows);
        $this->fieldSet = $fieldSet;
    }
    
    public function getFieldValue($fieldName) {
        if (!$this->valid()) {
            throw new OutOfBoundsException();
        }
        if (!$this->fieldExists($fieldName)) {
            return false;
        }
        $current = $this->current();
        return isset($current[$fieldName]) ? $current[$fieldName] : '';
    }
    public function fieldExists($fieldName) {
        return $this->fieldSet->fieldExists($fieldName);
    }
}
