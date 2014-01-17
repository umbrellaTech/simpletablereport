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

/**
 * Description of SimpleTest
 *
 * @author kelsoncm
 */
require_once 'SimpleTest.php';

class SimpleXlsxTest extends SimpleTest {
    
    private function getTableXML_1Col($title) {
       return "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n"
               . "<table xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\" id=\"1\" name=\"Tabela1\" displayName=\"Tabela1\" ref=\"A1:A8\" totalsRowShown=\"0\">"
               . "<autoFilter ref=\"A1:A8\"/>"
               . "<tableColumns count=\"1\">"
               . "<tableColumn id=\"1\" name=\"{$title}\"/>"
               . "</tableColumns>"
               . "<tableStyleInfo name=\"TableStyleLight1\" showFirstColumn=\"0\" showLastColumn=\"0\" showRowStripes=\"1\" showColumnStripes=\"0\"/>"
               . "</table>";
    }
    
    private function getSheetXML_1Col($sheetData) {
        return "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n"
                . "<worksheet xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\" xmlns:r=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships\" xmlns:mc=\"http://schemas.openxmlformats.org/markup-compatibility/2006\" mc:Ignorable=\"x14ac\" xmlns:x14ac=\"http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac\">"
                . "<dimension ref=\"A1:A8\"/>"
                . "<sheetViews><sheetView tabSelected=\"1\" workbookViewId=\"0\"><selection activeCell=\"A2\" sqref=\"A2\"/></sheetView></sheetViews>"
                . "<sheetFormatPr defaultRowHeight=\"15\" x14ac:dyDescent=\"0.25\"/>"
                . "<cols><col min=\"1\" max=\"1\" width=\"11.42578125\"/></cols>"
                . "<sheetData>{$sheetData}</sheetData>"
                . "<pageMargins left=\"0.511811024\" right=\"0.511811024\" top=\"0.78740157499999996\" bottom=\"0.78740157499999996\" header=\"0.31496062000000002\" footer=\"0.31496062000000002\"/>"
                . "<tableParts count=\"1\"><tablePart r:id=\"rId1\"/></tableParts>"
                . "</worksheet>";
    }
    
    private function getSharedXML_1Col($title, $count=0) {
        return "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n"
                . "<sst xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\" count=\"{$count}\" uniqueCount=\"{$count}\">"
                . "<si><t>{$title}</t></si>"
                . "</sst>";
    }
    
    /**
     *  @expectedException InvalidArgumentException
     *  @expectedExceptionMessage Passed variable is not an array or object, using empty array instead
     */
    public function testArrayDatasource_null() {
        $datasource = new ArrayDatasource(null);
        $template = new BaseTemplate(new FieldSet());
        $renderer = new XlsxRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     *  
     * @expectedException RuntimeException
     * @expectedExceptionMessage Empty FieldSet not allowed
     */
    public function testArrayDatasource_emptyFieldSet() {
        $datasource = new ArrayDatasource(array());
        $template = new BaseTemplate(new FieldSet());
        $renderer = new XlsxRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     *  
     * @expectedException RuntimeException
     * @expectedExceptionMessage Empty DataSource not allowed
     */
    public function testArrayDatasource_emptyArray() {
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::STRING);
                
        $datasource = new ArrayDatasource(array());
        $template = new BaseTemplate($fieldSet);
        $renderer = new XlsxRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    public function testArrayDatasource_TypeString() {
        $fieldSet = new FieldSet();
        $title = 'H1-String';
        $fieldSet->addField('i', 'H1-String', FieldType::STRING);
        
        $model = $this->arrayArray('i', array('ddois', 3, null, '', ' ', 'B6-String6', 'B7-Cacar',));// 'B5-\'"!@#$%¨&*()_+=-{`[´}^]~:><;.,|\ºª',));
        
        $datasource = new ArrayDatasource($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new XlsxRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);

        $this->assertEquals($this->getTableXML_1Col($title), $renderer->getTableString());
        $this->assertEquals(
                $this->getSheetXML_1Col("<row r=\"1\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A1\" t=\"s\"><v>0</v></c></row><row r=\"2\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A2\" t=\"s\"><v>1</v></c></row><row r=\"3\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A3\" t=\"s\"><v>2</v></c></row><row r=\"7\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A7\" t=\"s\"><v>3</v></c></row><row r=\"8\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A8\" t=\"s\"><v>4</v></c></row>")
                , $renderer->getSheetString());
        $this->assertEquals($this->getSharedXML_1Col("{$title}</t></si><si><t>ddois</t></si><si><t>3</t></si><si><t>B6-String6</t></si><si><t>B7-Cacar", 4), $renderer->getSharedStringsString());
    }
    
    public function testArrayDatasource_TypeInteger() {
        $title = 'H1-Integer';
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', $title, FieldType::INTEGER);
        
        $model = $this->arrayArray('i', array((3/3)+1, 3, null, '', ' ', 6.6, '7',));
        
        $datasource = new ArrayDatasource($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new XlsxRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
        
        $this->assertEquals($this->getTableXML_1Col($title), $renderer->getTableString());
        $this->assertEquals(
                $this->getSheetXML_1Col("<row r=\"1\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A1\" t=\"s\"><v>0</v></c></row><row r=\"2\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A2\"><v>2</v></c></row><row r=\"3\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A3\"><v>3</v></c></row><row r=\"7\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A7\"><v>6</v></c></row><row r=\"8\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A8\"><v>7</v></c></row>")
                , $renderer->getSheetString());
        $this->assertEquals($this->getSharedXML_1Col($title, 0), $renderer->getSharedStringsString());
    }
   
    public function testArrayDatasource_TypeFloat() {
        $title = 'H1-Float';
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', $title, FieldType::FLOAT);
        
        $model = $this->arrayArray('i', array((3/3)+1, 3, null, '', ' ', 6.7, '7.8',));
        
        $datasource = new ArrayDatasource($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new XlsxRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
        
        $this->assertEquals($this->getTableXML_1Col($title), $renderer->getTableString());
        $this->assertEquals(
                $this->getSheetXML_1Col("<row r=\"1\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A1\" t=\"s\"><v>0</v></c></row><row r=\"2\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A2\"><v>2</v></c></row><row r=\"3\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A3\"><v>3</v></c></row><row r=\"7\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A7\"><v>6.7</v></c></row><row r=\"8\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A8\"><v>7.8</v></c></row>")
                , $renderer->getSheetString());
        $this->assertEquals($this->getSharedXML_1Col($title, 0), $renderer->getSharedStringsString());
    }
   
    public function testArrayDatasource_TypeDecimal() {
        $title = 'H1-Decimal';
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', $title, FieldType::DECIMAL);
        
        $model = $this->arrayArray('i', array((3/3)+1, 3, null, '', ' ', 6.7, '7.8',));
        
        $datasource = new ArrayDatasource($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new XlsxRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
        $this->assertEquals($this->getTableXML_1Col($title), $renderer->getTableString());
        $this->assertEquals(
                $this->getSheetXML_1Col("<row r=\"1\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A1\" t=\"s\"><v>0</v></c></row><row r=\"2\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A2\" s=\"1\"><v>2.00</v></c></row><row r=\"3\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A3\" s=\"1\"><v>3.00</v></c></row><row r=\"7\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A7\" s=\"1\"><v>6.70</v></c></row><row r=\"8\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A8\" s=\"1\"><v>7.80</v></c></row>")
                , $renderer->getSheetString());
        $this->assertEquals($this->getSharedXML_1Col($title, 0), $renderer->getSharedStringsString());
    }
   
    public function testArrayDatasource_TypeMoney() {
        $title = 'H1-Money';
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', $title, FieldType::MONEY);
        
        $model = $this->arrayArray('i', array((3/3)+1, 3, null, '', ' ', 6.7, '7.8',));
        
        $datasource = new ArrayDatasource($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new XlsxRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
        $this->assertEquals($this->getTableXML_1Col($title), $renderer->getTableString());
        $this->assertEquals(
                $this->getSheetXML_1Col("<row r=\"1\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A1\" t=\"s\"><v>0</v></c></row><row r=\"2\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A2\" s=\"2\"><v>2.00</v></c></row><row r=\"3\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A3\" s=\"2\"><v>3.00</v></c></row><row r=\"7\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A7\" s=\"2\"><v>6.70</v></c></row><row r=\"8\" spans=\"1:1\" x14ac:dyDescent=\"0.25\"><c r=\"A8\" s=\"2\"><v>7.80</v></c></row>")
                , $renderer->getSheetString());
        $this->assertEquals($this->getSharedXML_1Col($title, 0), $renderer->getSharedStringsString());
    }
}
