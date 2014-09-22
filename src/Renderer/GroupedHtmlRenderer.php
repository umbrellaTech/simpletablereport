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
 * Description of HTMLRenderer
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class GroupedHtmlRenderer extends HtmlRenderer
{
    protected $groupedSums = array();

    /*
     * Mudança: O Cabeçalho geral da tabela não deve mostrar os campos agrupados, 
     * ou seja, deve ser suprimido a coluna agrupada, e seus valores serão headers
     * de suas respectivas linhas.
     */

    protected function renderTableHeader()
    {
        $depth = $this->template->getParam('grouped');
        $show = null;
        if (is_array($depth)) {
            $show = isset($depth['showHeader']) ? $depth['showHeader'] : false;
            $depth = isset($depth['depth']) ? $depth['depth'] : null;
        }

        $this->doWriteTableHeaderStart();
        $this->doWriteTableHeaderRowStart();
        $fields = $this->template->getFields();

        $count = count($fields);
        for ($i = 0; $i < $count; $i++) {
            $this->doWriteTableHeaderDataStart();
            if (($i == 0) || ($i > $depth) || ($show)) {
                echo $fields[$i]->getFieldCaption();
            }
            $this->doWriteTableHeaderDataEnd();
        }
        $this->doWriteTableHeaderRowEnd();
        $this->doWriteTableHeaderEnd();
    }
    /*
     * Mudança: Deve incluir um head na primeira coluna para cada inicio de grupo
     */

    protected function renderTableBodyRows()
    {
        $this->setSumGroupedValue();
        $fields = $this->template->getFields();

        $depth = $this->template->getParam('grouped');

        if (is_array($depth)) {
            $depth = isset($depth['depth']) ? $depth['depth'] : null;
        }

        $lastValue = null;
        for ($this->datasource->rewind(); $this->datasource->valid(); $this->datasource->next()) {
            if ($depth) {
                $value = $this->getValue($this->datasource, $fields[0], '');
                if (($value != $lastValue)) {
                    $this->renderGroupHeadRow();
                }
                $lastValue = $value;
            }

            $this->doWriteTableBodyRowStart();
            $this->renderTableBodyFields();
            $this->doWriteTableBodyRowEnd();
        }
    }

    public function renderGroupHeadRow()
    {
        $this->doWriteTableBodyGroupedRowStart();
        $this->renderGroupHeadFields();
        $this->doWriteTableBodyRowEnd();
    }

    protected function renderGroupHeadFields()
    {
        $depth = $this->template->getParam('grouped');

        if (is_array($depth)) {
            $depth = isset($depth['depth']) ? $depth['depth'] : null;
        }

        $fields = $this->template->getFields();
        $value = $this->getValue($this->datasource, $fields[$depth - 1], '');

        $count = count($fields);
        for ($i = 0; $i < $count - 1; $i++) {
            $this->doWriteTableBodyDataStart();
            if ($i == $depth - 1) {
                echo $value;
            }
            $this->doWriteTableBodyDataEnd();
        }

        //Coluna Somatoria
        $this->doWriteTableBodyDataStart();
        echo $this->groupedSums[$value];
        $this->doWriteTableBodyDataEnd();
    }
    /*
     * Mudança: só deve iterar os valores a partir da coluna que não servir de head
     */

    protected function renderTableBodyFields()
    {
        $depth = $this->template->getParam('grouped');

        if (is_array($depth)) {
            $depth = isset($depth['depth']) ? $depth['depth'] : null;
        }

        $fields = $this->template->getFields();
        $count = count($fields);
        for ($i = 0; $i < $count; $i++) {
            $this->doWriteTableBodyDataStart();
            if ($i >= $depth) {
                echo $this->getValue($this->datasource, $fields[$i], '');
            }
            $this->doWriteTableBodyDataEnd();
        }
    }

    protected function renderTableFooter()
    {
        $this->doWriteTableFooterStart();
        $this->doWriteTableFooterRowStart();
        $columnCountEnabled = $this->datasource->getEnabledColumnCount();
        $countColumn = ($columnCountEnabled) ? $this->datasource->getColumnFieldCounts() : $this->template->getFields();
        $columnPrint = '';

        $count = count($countColumn);
        for ($i = 0; $i < $count; $i++) {
            $this->doWriteTableFooterDataStart();
            if ($columnCountEnabled) {
                $columnPrint = $this->getColumnCountTotal($countColumn[$i]);
            } else {
                $columnPrint = '&nbsp;';
            }
            echo $columnPrint;
            $this->doWriteTableFooterDataEnd();
        }
        $this->doWriteTableFooterRowEnd();
        $this->doWriteTableFooterEnd();
    }

    protected function setSumGroupedValue()
    {
        $fields = $this->template->getFields();
        $value = null;
        for ($this->datasource->rewind(); $this->datasource->valid(); $this->datasource->next()) {
            $value = $this->getValue($this->datasource, $fields[0], '');
            if (!isset($this->groupedSums[$value])) {
                $this->groupedSums[$value] = 0;
            }
            $this->groupedSums[$value] += $this->getValue($this->datasource, $fields[count($fields) - 1], '');
        }
    }

    protected function doWriteTableBodyGroupedDataStart()
    {
        echo $this->getOption('table.body.grouped.data.start');
    }

    protected function doWriteTableBodyGroupedRowStart()
    {
        echo $this->getOption('table.body.grouped.row.start');
    }
}
