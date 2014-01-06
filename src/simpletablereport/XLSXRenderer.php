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

/**
 * Description of XLSXRenderer
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class XLSXRenderer extends BaseRenderer {
    
    protected $tableString = '';
    protected $sheetString = '';
    protected $outputFileName = '';
    
    public function getTableString() {
        return $this->tableString;
    }

    public function getSheetString() {
        return $this->sheetString;
    }

    public function getOutputFileName() {
        return $this->outputFileName;
    }
    
    /**
     * 
     * @param int $columnIndex - Zero indexed
     * @return string - Column letters
     */
    protected function getColumnLetters($columnIndex) {
        //A=65, Z=90

        // First Letter, if exists
        $rest = ((int)(($columnIndex)/26.0));
        $result = ($rest>0) ? chr(65+$rest-1) : ''; 
        
        // Second Letter
        $rest = ($columnIndex-($rest*26));
        $result .= chr(65+$rest);
        
        return $result;
    }
    
    public function render() {
        $this->validate();
        $this->renderTable();
        $this->renderSheet();
    }
    
    protected function validate() {
        $fields = $this->template->getFields();
        if (empty($fields) || (!empty($fields) && $fields->count() == 0)) {
            throw new RuntimeException("Empty FieldSet not allowed.");
        }
        
        if ($this->datasource->getRowCount()==0) {
            throw new RuntimeException("Empty DataSource not allowed.");
        }
    }

    protected function renderTable() {
        $this->doWriteTableBegin();
        $this->doWriteTableColumn();
        $this->doWriteTableEnd();
    }
    
    protected function doWriteTableBegin() {
        $colCount = $this->template->getFields()->count();
        $colLetter = $this->getColumnLetters($colCount-1);
        $rowCount = "6";
        $tableRange ="A1:{$colLetter}{$rowCount}";
        $this->tableString = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>";
        $this->tableString .= "<table xmlns='http://schemas.openxmlformats.org/spreadsheetml/2006/main' id='1' name='Tabela1' displayName='Tabela1' ref='{$tableRange}' totalsRowShown='0'><autoFilter ref='{$tableRange}'/><tableColumns count='{$colCount}'>";
    }

    protected function doWriteTableColumn() {
        $id = 1;
        foreach ($this->template->getFields() as $field) {
            $this->tableString .= "<tableColumn id='{$id}' name='{$field->getFieldCaption()}'/>";
            $id++;
        }
    }
    
    protected function doWriteTableEnd() {
        $this->tableString .= "</tableColumns><tableStyleInfo name='TableStyleLight1' showFirstColumn='0' showLastColumn='0' showRowStripes='1' showColumnStripes='0'/></table>";
    }

    protected function renderSheet() {
        
    }

}
