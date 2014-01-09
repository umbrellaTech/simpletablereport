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
 * Description of XLSXBaseHelper
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class XlsxBaseHelper {

    protected $datasource;
    protected $template;
    
    function __construct($datasource, $template) {
        $this->datasource = $datasource;
        $this->template = $template;
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
    
    protected function getTableRange() {
        $colCount = $this->template->getFields()->count();
        $colLetter = $this->getColumnLetters($colCount-1);
        $rowCount = $this->datasource->getRowCount();
        return "A1:{$colLetter}{$rowCount}";
    }
    
}
