<?php

/*
 * Copyright 2013 kelsocm.
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

use Umbrella\SimpleReport\Api\FieldSet;
use Umbrella\SimpleReport\Api\ITemplate;

/**
 * Description of SimpleTemplate
 *
 * @author kelsocm
 * @author Ítalo Lelis <italo@voxtecnologia.com.br>
 */
class BaseTemplate implements ITemplate, ISum
{
    private $fields;
    private $params;
    private $tags;
    private $path;
    private $colunmFieldCounts;
    private $enableColunmCount;

    public function __construct(FieldSet $fieldSet = null, array $params = array())
    {
        $this->fields = $fieldSet;
        $this->params = $params;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields(FieldSet $fieldSet)
    {
        $this->fields = $fieldSet;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function fieldExists($fieldName)
    {
        return $this->fields->fieldExists($fieldName);
    }

    public function addParam($param, $value)
    {
        $this->params[$param] = $value;
    }

    public function getParam($param)
    {
        return $this->params[$param];
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags(array $tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function getColumnFieldCounts()
    {
        return $this->colunmFieldCounts;
    }

    public function setColumnFieldCounts($fieldsCounts)
    {
        $this->colunmFieldCounts = $fieldsCounts;
        return $this;
    }

    public function enableColumnCount($enable)
    {
        $this->enableColunmCount = (is_array($enable))? true : false;
        return $this;
    }

    public function getEnabledColumnCount()
    {
        return $this->enableColunmCount;
    }
}
