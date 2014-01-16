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
 * Description of XLSXTableHelper
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class XlsxTableHelper extends XlsxBaseHelper {

    public function renderTable() {
        return $this->doWriteTableBegin()
                . $this->doWriteTableColumn()
                . $this->doWriteTableEnd();
    }
    
    protected function doWriteTableBegin() {
        $colCount = $this->template->getFields()->count();
        $tableRange = $this->getTableRange();
        return "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n" 
                . "<table xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\""
                . " id=\"1\" name=\"Tabela1\" displayName=\"Tabela1\""
                . " ref=\"{$tableRange}\" totalsRowShown=\"0\">"
                . "<autoFilter ref=\"{$tableRange}\"/><tableColumns count=\"{$colCount}\">";
    }

    protected function doWriteTableColumn() {
        $id = 1;
        $result = '';
        foreach ($this->template->getFields() as $field) {
            $value = $field->getFieldCaption();
            $result .= "<tableColumn id=\"{$id}\" name=\"{$value}\"/>";
            $id++;
        }
        return $result;
    }
    
    protected function doWriteTableEnd() {
        return "</tableColumns>"
                . "<tableStyleInfo name=\"TableStyleLight1\" showFirstColumn=\"0\" showLastColumn=\"0\" showRowStripes=\"1\" showColumnStripes=\"0\"/>"
                . "</table>";
    }
    
}
