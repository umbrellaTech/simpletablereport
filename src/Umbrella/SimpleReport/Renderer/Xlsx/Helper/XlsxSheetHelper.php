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

namespace Umbrella\SimpleReport\Renderer\Xlsx\Helper;

/**
 * Description of XLSXSheetHelper
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class XlsxSheetHelper extends XlsxBaseHelper
{

    protected $sheetString = '';

    public function renderSheet()
    {
        $this->currentRow = 0;
        $this->doWriteSheetBegin();
        $this->doWriteSheetColumns();
        $this->doWriteSheetData();
        $this->doWriteSheetPageMargins();
        $this->doWriteSheetEnd();
        return $this->sheetString;
    }

    protected function doWriteSheetBegin()
    {
        $tableRange = $this->getTableRange();
        $this->sheetString = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
        $this->sheetString .= "<worksheet xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\" xmlns:r=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships\" xmlns:mc=\"http://schemas.openxmlformats.org/markup-compatibility/2006\" mc:Ignorable=\"x14ac\" xmlns:x14ac=\"http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac\">";
        $this->sheetString .= "<dimension ref=\"{$tableRange}\"/>";
        $this->sheetString .= "<sheetViews>";
        $this->sheetString .= "<sheetView tabSelected=\"1\" workbookViewId=\"0\">";
        $this->sheetString .= "<selection activeCell=\"A2\" sqref=\"A2\"/>";
        $this->sheetString .= "</sheetView>";
        $this->sheetString .= "</sheetViews>";
        $this->sheetString .= "<sheetFormatPr defaultRowHeight=\"15\" x14ac:dyDescent=\"0.25\"/>";
    }

    protected function doWriteSheetColumns()
    {
        $this->sheetString .= "<cols>";
        $this->sheetString .= "<col min=\"1\" max=\"1\" width=\"11.42578125\" bestFit=\"1\" customWidth=\"1\"/>";
        $this->sheetString .= "</cols>";
    }

    protected function doWriteSheetData()
    {
        $this->sheetString .= "<sheetData>";
        $this->doWriteSheetDataHeader();
        $this->doWriteSheetDataBody();
        $this->sheetString .= "</sheetData>";
    }

    protected function doWriteSheetDataHeader()
    {
        $this->currentRow++;
        $this->sheetString .= "<row r='{$this->currentRow}' spans='1:1' x14ac:dyDescent='0.25'>";
        foreach ($this->template->getFields() as $key => $fieldDescription) {
            $stringId = XlsxSharedStringsHelper::putIfNotExists($fieldDescription->getFieldCaption());
            $colLetter = $this->getColumnLetters($key);
            $this->sheetString .= "<c r='{$colLetter}{$this->currentRow}' t='s'><v>{$stringId}</v></c>";
        }
        $this->sheetString .= "</row>";
    }

    protected function doWriteSheetDataBody()
    {
        for ($this->datasource->rewind(); $this->datasource->valid(); $this->datasource->next()) {
            $this->currentRow++;
            $cols = $this->doWriteSheetDataBodyCols();
            if ($cols) {
                $this->sheetString .= "<row r=\"{$this->currentRow}\" spans=\"1:1\" x14ac:dyDescent=\"0.25\">";
                $this->sheetString .= $cols;
                $this->sheetString .= "</row>";
            }
        }
    }

    protected function doWriteSheetDataBodyCols()
    {
        $return = "";
        foreach ($this->template->getFields() as $key => $fieldDescription) {
            $value = $this->datasource->getFieldValue($fieldDescription);
            if (!empty($value)) {
                $stringId = XlsxSharedStringsHelper::putIfNotExists($value);
                $colLetter = $this->getColumnLetters($key);
                $cellAddress = $colLetter . $this->currentRow;
                $return .= "<c r=\"$cellAddress\" t=\"s\"><v>{$stringId}</v></c>";
            }
        }
        return $return;
    }

    protected function doWriteSheetPageMargins()
    {
        $this->sheetString .= "<pageMargins left=\"0.511811024\" right=\"0.511811024\" top=\"0.78740157499999996\" bottom=\"0.78740157499999996\" header=\"0.31496062000000002\" footer=\"0.31496062000000002\"/>";
        //$this->sheetString .= "<pageSetup paperSize=\"9\" orientation=\"portrait\" horizontalDpi=\"0\" verticalDpi=\"0\" r:id=\"rId1\"/>";
    }

    protected function doWriteSheetEnd()
    {
        $this->sheetString .= "<tableParts count=\"1\">";
        $this->sheetString .= "<tablePart r:id=\"rId2\"/>";
        $this->sheetString .= "</tableParts>";
        $this->sheetString .= "</worksheet>";
    }

}
