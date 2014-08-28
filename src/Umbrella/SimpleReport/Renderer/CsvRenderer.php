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
namespace Umbrella\SimpleReport\Renderer;

use Umbrella\SimpleReport\BaseRenderer;

/**
 * Description of CSVRenderer
 *
 * @author kelsoncm
 */
class CsvRenderer extends BaseRenderer
{
    private $stringBuffer;

    public function getStringBuffer()
    {
        return $this->stringBuffer;
    }

    public function render()
    {
        $this->stringBuffer = '';
        for ($this->datasource->rewind(); $this->datasource->valid(); $this->datasource->next()) {
            $this->renderRow();
        }
    }

    protected function write($string)
    {
        $this->stringBuffer .= $string;
    }

    protected function renderRow()
    {
        $row = array();
        foreach ($this->template->getFields() as $fieldDescription) {
            $row[] = $this->getValue($this->datasource, $fieldDescription, 'CSV');
        }
        $this->write(implode(',', $row) . "\n");
    }
}
