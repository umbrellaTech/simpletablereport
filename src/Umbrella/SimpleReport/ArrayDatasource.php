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

namespace Umbrella\SimpleReport;

use ArrayIterator;
use OutOfBoundsException;
use Umbrella\SimpleReport\Api\FieldDefinition;
use Umbrella\SimpleReport\Api\IDatasource;

/**
 * Description of AbstractDatasource
 *
 * @author kelsoncm
 * @author Ayrton Ricardo<ayrton_jampa15@hotmail.com>
 */
class ArrayDatasource extends ArrayIterator implements IDatasource
{

    /**
     * Variable for to store if will count the values of columns
     * @var bool
     */
    private $enableColumnCount = false;

    /**
     * Store the id of fields to be counted
     * @var array
     */
    private $columnFieldCounts;

    /**
     * Return the value of current field
     *
     * @param FieldDefinition $fieldDefinition
     * @return null
     * @throws \OutOfBoundsException
     */
    public function getFieldValue(FieldDefinition $fieldDefinition)
    {
        if (!$this->valid()) {
            throw new OutOfBoundsException();
        }
        $current = $this->current();
        return isset($current[$fieldDefinition->getFieldName()]) ? $current[$fieldDefinition->getFieldName()] : null;
    }

    /**
     * @link http://php.net/manual/en/arrayiterator.count.php
     * @return int
     */
    public function getRowCount()
    {
        return $this->count();
    }

    /**
     * Return the fields that will be counted
     * @return array
     */
    public function getColumnFieldCounts()
    {
        return $this->columnFieldCounts;
    }

    /**
     * Set the fields that will be counted
     * @param $fieldsCounts
     * @return $this
     */
    public function setColumnFieldCounts(array $fieldsCounts)
    {
        $this->columnFieldCounts = $fieldsCounts;
        return $this;
    }

    /**
     * Enable/Disable the count of fields
     * @param bool $enable
     * @return $this
     */
    public function columnCount($enable = false)
    {
        $this->enableColumnCount = $enable;
        return $this;
    }

    /**
     * Return if column count is enabled
     * @return bool
     */
    public function getEnabledColumnCount()
    {
        return $this->enableColumnCount;
    }

    /**
     * Append one row on final of datasource with the count of defined columns.
     */
    public function appendColumnCount()
    {
        if (!$this->getEnabledColumnCount()) {
            return $this;
        }

        $columns = $this->getColumnFieldCounts();
        $count = 0;
        $arrayAppend = array();
        for ($this->rewind(); $this->valid(); $this->next()) {
            foreach($this->current() as $keyIdentification => $valueField) {
                if (isset($columns[$keyIdentification]) || in_array($keyIdentification, $columns)) {
                    if (is_numeric($valueField)) {
                        $count += $valueField;
                        $arrayAppend[$keyIdentification] = $count;
                        $count = 0;
                    } elseif (isset($columns[$keyIdentification]) && is_array($columns[$keyIdentification])) {
                        $label = isset($columns[$keyIdentification]['label'])? $columns[$keyIdentification]['label'] : $valueField;
                        $prefix = isset($columns[$keyIdentification]['prefix'])? $columns[$keyIdentification]['prefix'] : '';
                        $suffix = isset($columns[$keyIdentification]['suffix'])? $columns[$keyIdentification]['suffix'] : '';
                        $arrayAppend[$keyIdentification] = sprintf('%s %s %s', $prefix, $label, $suffix);
                    }
                }
            }
        }

        $this->append($arrayAppend);
        return $this;
    }

}
