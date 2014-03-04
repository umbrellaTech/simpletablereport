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

namespace Umbrella\SimpleReport\Renderer\Xlsx;

use Umbrella\SimpleReport\BaseRenderer;
use Umbrella\SimpleReport\Renderer\Xlsx\Helper\XlsxTableHelper;
use Umbrella\SimpleReport\Renderer\Xlsx\Helper\XlsxSheetHelper;
use Umbrella\SimpleReport\Renderer\Xlsx\Helper\XlsxStyleHelper;
use Umbrella\SimpleReport\Renderer\Xlsx\Helper\XlsxSharedStringsHelper;
use Umbrella\SimpleReport\ConfigurationLoader;

/**
 * Description of XlsxRenderer
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class XlsxRenderer extends BaseRenderer {

    /*
    protected $tableString = '';
    protected $sheetString = '';
    protected $sharedStringsString = '';
    protected $stylesString = '';
    */
    protected $outputFileName = '';
    protected $currentRow;
    
    protected $partsNames = array(
        'XlsxContentTypesHelper' => '[Content_Types].xml',
        'XlsxRelsHelper' => '_rels/.rels',
        'XlsxRelsWorkbookHelper' => 'xl/_rels/workbook.xml.rels',
        'XlsxRelsSheetHelper' => 'xl/worksheets/_rels/sheet1.xml.rels',
        'XlsxThemeHelper' => 'xl/theme/theme1.xml',
        'XlsxWorkbookHelper' => 'xl/workbook.xml',
        'XlsxDocPropsAppHelper' => 'docProps/app.xml',
        'XlsxDocPropsCoreHelper' => 'docProps/core.xml',
        
        'XlsxTableHelper' => 'xl/tables/table1.xml',
        'XlsxSheetHelper' => 'xl/worksheets/sheet1.xml',
        'XlsxSharedStringsHelper' => 'xl/sharedStrings.xml',
        'XlsxStyleHelper' => 'xl/styles.xml',
        );
    
    protected $parts = array();

    public function getOutputFileName() {
        return $this->outputFileName;
    }

    public function setOutputFileName($outputFileName) {
        return $this->outputFileName = $outputFileName;
    }
    
    public function getTableString() {
        return $this->parts['XlsxTableHelper'];
    }

    public function getSheetString() {
        return $this->parts['XlsxSheetHelper'];
    }
    
    public function getSharedStringsString() {
        return $this->parts['XlsxSharedStringsHelper'];
    }
    
    protected function validate() {
        $fields = $this->template->getFields();
        if (empty($fields) || (!empty($fields) && $fields->count() == 0)) {
            throw new \RuntimeException("Empty FieldSet not allowed.");
        }
        
        if ($this->datasource->getRowCount()==0) {
            throw new \RuntimeException("Empty DataSource not allowed.");
        }
    }
    
    public function render() {
        $this->validate();
        
        $this->outputFileName = $this->outputFileName ?: tempnam("", "xlsx");

        $zip = new \ZipArchive();
        
        if ($zip->open($this->outputFileName, \ZipArchive::CREATE)!==TRUE) {
            throw new \RuntimeException("Cannot open [{$this->outputFileName}].");
        }

        foreach ($this->partsNames as $partName => $fileName) {
            $partClassName = "Umbrella\\SimpleReport\\Renderer\\Xlsx\\Helper\\{$partName}";
            $partHelper = new $partClassName($this->datasource, $this->template);
            $partContent = $partHelper->render();
            $this->parts[$partName] = $partContent;
            $zip->addFromString($fileName, $partContent);
        }
        
        $zip->close();        
    }
    
}