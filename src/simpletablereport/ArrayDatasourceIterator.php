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
 * Description of AbstractDatasourceIterator
 *
 * @author kelsoncm
 */
class ArrayDatasourceIterator extends ArrayIterator implements IDatasourceIterator {

    public function getFieldValue(FieldDefinition $fieldDefinition) {
        if (!$this->valid()) {
            throw new OutOfBoundsException();
        }
        $current = $this->current();
        return isset($current[$fieldDefinition->getFieldName()]) ? $current[$fieldDefinition->getFieldName()] : '';
    }

    public function getRowCount() {
        return $this->count();
    }

}
