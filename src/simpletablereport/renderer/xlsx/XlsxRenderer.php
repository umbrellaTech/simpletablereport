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
 * Description of XlsxRenderer
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class XlsxRenderer extends BaseRenderer {
    
    protected $tableString = '';
    protected $sheetString = '';
    protected $sharedStringsString = '';
    protected $stylesString = '';
    protected $outputFileName = '';
    protected $currentRow;
    
    public function getTableString() {
        return $this->tableString;
    }

    public function getSheetString() {
        return $this->sheetString;
    }

    public function getOutputFileName() {
        return $this->outputFileName;
    }
    
    public function getSharedStringsString() {
        return $this->sharedStringsString;
    }
   
    
    public function render() {
        $this->validate();
        
        $xlsxTableHelper = new XlsxTableHelper($this->datasource, $this->template);
        $this->tableString = $xlsxTableHelper->renderTable();

        $xlsxSheetHelper = new XlsxSheetHelper($this->datasource, $this->template);
        $this->sheetString = $xlsxSheetHelper->renderSheet();
        
        $xlsxSharedStringsHelper = new XlsxSharedStringsHelper($this->datasource, $this->template);
        $this->sharedStringsString = $xlsxSharedStringsHelper->renderSharedStrings();
        
        $xlsxStyleHelper = new XlsxStyleHelper($this->datasource, $this->template);
        $this->styleString = $xlsxStyleHelper->renderStyle();

        $this->output();
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
    
    protected function output() {
        $this->buildOutputFileName();
        $this->copySampleFile();
        $this->zipContent();
    }
    
    protected function buildOutputFileName() {
        if ($this->outputFileName) {
            return;
        } 
        $rootDir = ConfigurationLoader::getInstance()->getRootDir();
        $concat = $this->tableString . $this->sheetString . $this->sharedStringsString;
        $this->outputFileName = "{$rootDir}/test/" . md5($concat) .  ".xlsx";
    }
    
    protected function copySampleFile() {
        $rootDir = ConfigurationLoader::getInstance()->getRootDir();
        $sampleTmp = ConfigurationLoader::getInstance()->getConfiguration()->getOption('simpletablereport.xlsxrenderer.sample');
        $sample = "{$rootDir}/{$sampleTmp}";
        if (!copy($sample, $this->outputFileName)) {
            throw new RuntimeException("Failed to copy '{$sample}'.");
        }
    }
    
    protected function zipContent() {
        $zip = new ZipArchive();
        if ($zip->open($this->outputFileName, ZipArchive::CREATE)!==TRUE) {
            throw new RuntimeException("Cannot open [{$this->outputFileName}].");
        }

        $zip->addFromString("xl/tables/table1.xml", $this->tableString);
        $zip->addFromString("xl/worksheets/sheet1.xml", $this->sheetString);
        $zip->addFromString("xl/sharedStrings.xml", $this->sharedStringsString);
        $zip->addFromString("xl/styles.xml", $this->styleString);
        $zip->close();        
    }
}
