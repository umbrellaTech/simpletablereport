<?php

/*
 * Copyright 2014 kelsoncm <falecom@kelsoncm.com>.
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

/**
 * @author Ítalo Lelis de Vietro <italolelis@gmail.com>
 */
class GroupHtmlRenderer extends HtmlRenderer
{

    protected function renderTableBodyRows()
    {
        for ($this->datasource->rewind(); $this->datasource->valid(); $this->datasource->next()) {
            $this->doWriteTableBodyRowStart();
            $this->renderTableBodyFields();
            $this->doWriteTableBodyRowEnd();
        }
    }

    protected function renderTableBodyFields()
    {
        foreach ($this->template->getFields() as $fieldDescription) {
            $this->doWriteTableBodyDataStart();

            echo $this->getValue($this->datasource, $fieldDescription, '');

            $this->doWriteTableBodyDataEnd();
        }
    }

    protected function renderTableBodyGroupedFields()
    {
        foreach ($this->template->getFields() as $fieldDescription) {
            $this->doWriteTableBodyDataStart();
            echo $this->getValue($this->datasource, $fieldDescription, '');
            $this->doWriteTableBodyDataEnd();
        }
    }

    protected function doWriteTableBodyRowStart()
    {
        echo $this->getOption('table.body.row.start');
    }

    protected function doWriteTableBodyDataStart()
    {
        echo $this->getOption('table.body.data.start');
    }

    protected function doWriteTableBodyDataEnd()
    {
        echo $this->getOption('table.body.data.end');
    }

    protected function doWriteTableBodyRowEnd()
    {
        echo $this->getOption('table.body.row.end');
    }
}
