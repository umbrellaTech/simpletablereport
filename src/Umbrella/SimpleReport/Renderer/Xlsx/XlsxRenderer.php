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

use RuntimeException;
use Umbrella\SimpleReport\BaseRenderer;
use Umbrella\SimpleReport\ConfigurationLoader;
use Umbrella\SimpleReport\Renderer\Xlsx\Helper\XlsxSharedStringsHelper;
use Umbrella\SimpleReport\Renderer\Xlsx\Helper\XlsxSheetHelper;
use Umbrella\SimpleReport\Renderer\Xlsx\Helper\XlsxTableHelper;
use ZipArchive;

/**
 * Description of XlsxRenderer
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class XlsxRenderer extends BaseRenderer
{

    protected $tableString = '';
    protected $sheetString = '';
    protected $sharedStringsString = '';
    protected $outputFileName = '';
    protected $currentRow;

    public function getTableString()
    {
        return $this->tableString;
    }

    public function getSheetString()
    {
        return $this->sheetString;
    }

    public function getOutputFileName()
    {
        return $this->outputFileName;
    }

    public function getSharedStringsString()
    {
        return $this->sharedStringsString;
    }

    public function render()
    {
        $this->validate();

        $xlsxTableHelper = new XlsxTableHelper($this->datasource, $this->template);
        $this->tableString = $xlsxTableHelper->renderTable();

        $xlsxSheetHelper = new XlsxSheetHelper($this->datasource, $this->template);
        $this->sheetString = $xlsxSheetHelper->renderSheet();

        $xlsxSharedStringsHelper = new XlsxSharedStringsHelper($this->datasource, $this->template);
        $this->sharedStringsString = $xlsxSharedStringsHelper->renderSharedStrings();

        $this->output();
    }

    protected function validate()
    {
        $fields = $this->template->getFields();
        if (empty($fields) || (!empty($fields) && $fields->count() == 0)) {
            throw new RuntimeException("Empty FieldSet not allowed.");
        }

        if ($this->datasource->getRowCount() == 0) {
            throw new RuntimeException("Empty DataSource not allowed.");
        }
    }

    protected function output()
    {
        $this->copySampleFile();
        $this->zipContent();
    }

    protected function copySampleFile()
    {
        $rootDir = ConfigurationLoader::getInstance()->getRootDir();
        $output = "{$rootDir}/test/out.xlsx";

        $sampleTmp = ConfigurationLoader::getInstance()->getConfiguration()->getOption('simpletablereport.xlsxrenderer.sample');
        $sample = "{$rootDir}/{$sampleTmp}";
        if (!copy($sample, $output)) {
            throw new RuntimeException("failed to copy $sample...");
        }
    }

    protected function zipContent()
    {
        $rootDir = ConfigurationLoader::getInstance()->getRootDir();
        $output = "{$rootDir}/test/out.xlsx";

        $zip = new ZipArchive();
        if ($zip->open($output, ZipArchive::CREATE) !== TRUE) {
            throw new RuntimeException("cannot open <$output>");
        }

        $zip->addFromString("xl/tables/table1.xml", $this->tableString);
        $zip->addFromString("xl/worksheets/sheet1.xml", $this->sheetString);
        $zip->addFromString("xl/sharedStrings.xml", $this->sharedStringsString);
        $zip->close();
    }

}
