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
 * Description of CSVRendererTest
 *
 * @author kelsoncm
 */
class SimpleTest extends PHPUnit_Framework_TestCase {
    
    /**
     * 
     * @return array
     */
    public function getData() {
        return array
                (
                    array('id'=>1,'nome'=>'Kelson'), 
                    array('id'=>2,'nome'=>'KelsonCM'),
                    array('id'=>3,'nome'=>''),
                    array('id'=>4,'nome'=>null),
                    array('id'=>5,'nome'=>'null', 'nome2'=>'#error'),
                    array('id'=>6,'nome2'=>'#error'),
                );
//'razao'=>(10/3), 'salario'=>123.456, 'nascimento'=>new DateTime('01/01/2001'), 'almoco'=>new DateTime('01/01/2001 12:12:12'), 'casamento'=>new DateTime('01/01/2001 12:12:12');

    }
    
    public function getFieldset() {
        $fieldSet = new FieldSet();
        return $fieldSet
                ->addField('id','#', FieldType::INTEGER)
                ->addField('nome','Nome', FieldType::STRING)
                ->addField('razao', 'Razão', FieldType::FLOAT)
                ->addField('salario', 'Salário', FieldType::DECIMAL)
                ->addField('nascimento', 'Nascimento', FieldType::DATE)
                ->addField('almoco', 'Almoço', FieldType::TIME)
                ->addField('casamento', 'Casamento', FieldType::DATETIME);
    }
    
    private function arrayArray($name, $elems) {
        $result = array();
        foreach ($elems as $value) {
            $result[] = array($name=>$value);
        }
        return $result;
    }
    
    /**
     * 
     */
    public function testRenderCSV_DSArray_TypeInteger() {
        $this->expectOutputString("0\n1\n2\n3\n4\n\n\n\n5\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::INTEGER);
        
        $model = $this->arrayArray('i', array(0, 1, '2', 3.3, 4.0, null, '', ' ', 5));
        
        $datasource = new ArrayDatasourceIterator($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CSVRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testRenderCSV_DSArray_TypeFloat() {
        $this->expectOutputString("0\n1.1\n2.4\n4\n\n\n\n5.6\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::FLOAT);
        
        $model = $this->arrayArray('i', array(0, 1.1, '2.4', 4, null, '', ' ', 5.60));
        
        $datasource = new ArrayDatasourceIterator($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CSVRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testRenderCSV_DSArray_TypeDecimal() {
        $this->expectOutputString("0.000\n1.100\n2.400\n4.000\n5.678\n6.790\n\n\n\n5.600\n654321.000\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::DECIMAL);
        
        $model = $this->arrayArray('i', array(0, 1.1, '2.4', 4, 5.678, 6.789876, null, '', ' ', 5.60, 654321));
        
        $datasource = new ArrayDatasourceIterator($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CSVRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testRenderCSV_DSArray_TypeMoney() {
        $this->expectOutputString("R$0.0000\nR$1.1000\nR$2.4000\nR$4.0000\nR$5.6780\nR$6.7899\n\n\n\nR$5.6000\nR$654321.0000\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::MONEY);
        
        $model = $this->arrayArray('i', array(0, 1.1, '2.4', 4, 5.678, 6.789876, null, '', ' ', 5.60, 654321));
        
        $datasource = new ArrayDatasourceIterator($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CSVRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testRenderCSV_DSArray_TypeDate() {
        $today = new DateTime();
        $today->setTimestamp(time());

        $this->expectOutputString($today->format('d/m/Y') . "\n" . $today->format('d/m/Y') . "\n13/12/2011\n15/12/2013\n\n\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::DATE);
        
        $model = $this->arrayArray('i', array(time(), $today, '13/12/2011', array('year'=>2013, 'mon'=>12, 'mday'=>15), null, '', ));
        
        $datasource = new ArrayDatasourceIterator($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CSVRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testRenderCSV_DSArray_TypeTime() {
        $today = new DateTime();
        $today->setTimestamp(time());

        $this->expectOutputString($today->format('H:i:s') . "\n" . $today->format('H:i:s') . "\n23:59:00\n22:00:00\n\n\n23:59:35\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::TIME);
        
        $model = $this->arrayArray('i', array(time(), $today, '23:59', array('hours'=>22, 'minutes'=>00, 'seconds'=>00), null, '', '23:59:35', ));
 
        $datasource = new ArrayDatasourceIterator($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CSVRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testRenderCSV_DSArray_TypeTimestamp() {
        $today = new DateTime();
        $today->setTimestamp(time());

        $this->expectOutputString($today->format('d/m/Y H:i:s') . "\n" . $today->format('d/m/Y H:i:s') . "\n15/12/2013 23:59:00\n20/11/2011 22:00:00\n\n\n01/01/1991 23:59:35\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::TIMESTAMP);
        
        $model = $this->arrayArray('i', array(time(), $today, '15/12/2013 23:59', array('year'=>2011, 'mon'=>11, 'mday'=>20, 'hours'=>22, 'minutes'=>00, 'seconds'=>00), null, '', '01/01/1991 23:59:35', ));
 
        $datasource = new ArrayDatasourceIterator($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CSVRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testRenderCSV_DSArray_TypeString() {
        $this->expectOutputString("umbrella\nsimpletablereport\n\n\n\nkelsoncm\nline1 line2\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::STRING);
        
        $model = $this->arrayArray('i', array('umbrella', 'simpletablereport', null, '', ' ', 'kelsoncm',"line1\nline2",));
        
        $datasource = new ArrayDatasourceIterator($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CSVRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testRenderCSV_DSArray_emptyArray() {
        $this->expectOutputString("");
        
        $datasource = new ArrayDatasourceIterator(array());
        $template = new BaseTemplate(new FieldSet());
        $renderer = new CSVRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     *  @expectedException InvalidArgumentException
     *  @expectedExceptionMessage Passed variable is not an array or object, using empty array instead
     */
    public function testRenderCSV_DSArray_null() {
        $this->expectOutputString("");
        
        $datasource = new ArrayDatasourceIterator(null);
        $template = new BaseTemplate(new FieldSet());
        $renderer = new CSVRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     *  @expectedException InvalidArgumentException
     *  @expectedExceptionMessage Passed variable is not an array or object, using empty array instead
     */
    public function testRenderXLSX_DSArray_null() {
        $datasource = new ArrayDatasourceIterator(null);
        $template = new BaseTemplate(new FieldSet());
        $renderer = new XLSXRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     *  
     * @expectedException RuntimeException
     * @expectedExceptionMessage Empty FieldSet not allowed
     */
    public function testRenderXLSX_DSArray_emptyFieldSet() {
        $datasource = new ArrayDatasourceIterator(array());
        $template = new BaseTemplate(new FieldSet());
        $renderer = new XLSXRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     *  
     * @expectedException RuntimeException
     * @expectedExceptionMessage Empty DataSource not allowed
     */
    public function testRenderXLSX_DSArray_emptyArray() {
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::STRING);
                
        $datasource = new ArrayDatasourceIterator(array());
        $template = new BaseTemplate($fieldSet);
        $renderer = new XLSXRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     *  
     */
    public function testRenderXLSX_DSArray_TypeString() {
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', 'H1-String', FieldType::STRING);
        
        $model = $this->arrayArray('i', array('B1-String1', null, '', ' ', 'B3-String3', 'B4-Caçar', 'B5-\'"!@#$%¨&*()_+=-{`[´}^]~:><;.,|\ºª',));
        
        $datasource = new ArrayDatasourceIterator($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new XLSXRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
        
        $this->assertEquals(
                "<?xml version='1.0' encoding='UTF-8' standalone='yes'?><table xmlns='http://schemas.openxmlformats.org/spreadsheetml/2006/main' id='1' name='Tabela1' displayName='Tabela1' ref='A1:A6' totalsRowShown='0'><autoFilter ref='A1:A6'/><tableColumns count='1'><tableColumn id='1' name='H1-String'/></tableColumns><tableStyleInfo name='TableStyleLight1' showFirstColumn='0' showLastColumn='0' showRowStripes='1' showColumnStripes='0'/></table>"
                , $renderer->getTableString());
    }

    /*
    public function testExcel() {
        $loader = ConfigurationLoader::getInstance();
        $rootDir = $loader->getRootDir();
        
        $sample = $loader->getConfiguration()->getOption('simpletablereport.xlsxrenderer.sample');
        $sample = "{$rootDir}/{$sample}";
        $output = "{$rootDir}/test/out.xlsx";
        if (!copy($sample, $output)) {
            throw new RuntimeException("failed to copy $sample...");
        }

        $zip = new ZipArchive();
        if ($zip->open($output, ZipArchive::CREATE)!==TRUE) {
            throw new RuntimeException("cannot open <$output>");
        }

        $zip->addFromString("xl/tables/table1.xml", '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><table xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" id="1" name="Tabela1" displayName="Tabela1" ref="A1:H6" totalsRowShown="0"><autoFilter ref="A1:H6"/><tableColumns count="8"><tableColumn id="1" name="H1-String"/><tableColumn id="2" name="H2-Integer" dataDxfId="4"/><tableColumn id="3" name="H3-Float" dataDxfId="3"/><tableColumn id="4" name="H4-Money" dataDxfId="2"/><tableColumn id="5" name="H5-Decimal" dataDxfId="1"/><tableColumn id="6" name="H6-Date" dataDxfId="0"/><tableColumn id="7" name="H7-Time"/><tableColumn id="8" name="H8-Timestamp"/></tableColumns><tableStyleInfo name="TableStyleLight1" showFirstColumn="0" showLastColumn="0" showRowStripes="1" showColumnStripes="0"/></table>');
        $zip->addFromString("xl/worksheets/sheet1.xml", '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac"><dimension ref="A1:H6"/><sheetViews><sheetView tabSelected="1" workbookViewId="0"/></sheetViews><sheetFormatPr defaultRowHeight="15" x14ac:dyDescent="0.25"/><cols><col min="1" max="1" width="11.42578125" bestFit="1" customWidth="1"/><col min="2" max="2" width="12.7109375" bestFit="1" customWidth="1"/><col min="3" max="3" width="10.7109375" bestFit="1" customWidth="1"/><col min="4" max="4" width="16.28515625" bestFit="1" customWidth="1"/><col min="5" max="5" width="13.42578125" bestFit="1" customWidth="1"/><col min="6" max="7" width="10.7109375" bestFit="1" customWidth="1"/><col min="8" max="8" width="21" customWidth="1"/></cols><sheetData><row r="1" spans="1:8" x14ac:dyDescent="0.25"><c r="A1" t="s"><v>0</v></c><c r="B1" t="s"><v>1</v></c><c r="C1" t="s"><v>2</v></c><c r="D1" t="s"><v>3</v></c><c r="E1" t="s"><v>4</v></c><c r="F1" t="s"><v>5</v></c><c r="G1" t="s"><v>6</v></c><c r="H1" t="s"><v>7</v></c></row><row r="2" spans="1:8" x14ac:dyDescent="0.25"><c r="A2" t="s"><v>8</v></c><c r="B2" s="4"><v>1</v></c><c r="C2" s="2"><v>1</v></c><c r="D2" s="5"><v>1</v></c><c r="E2" s="3"><v>1</v></c><c r="F2" s="1"><v>36892</v></c><c r="G2" s="6"><v>4.2361111111111106E-2</v></c><c r="H2" s="8"><v>36892.042361111111</v></c></row><row r="3" spans="1:8" x14ac:dyDescent="0.25"><c r="B3" s="4"/><c r="C3" s="2"/><c r="D3" s="5"/><c r="E3" s="3"/><c r="F3" s="1"/></row><row r="4" spans="1:8" x14ac:dyDescent="0.25"><c r="A4" t="s"><v>9</v></c><c r="B4" s="4"><v>0</v></c><c r="C4" s="2"><v>0</v></c><c r="D4" s="5"><v>0</v></c><c r="E4" s="3"><v>0</v></c><c r="F4" s="1"><v>36893</v></c><c r="G4" s="7"><v>4.2372685185185187E-2</v></c><c r="H4" s="9"><v>36893.042372685188</v></c></row><row r="5" spans="1:8" x14ac:dyDescent="0.25"><c r="A5" t="s"><v>10</v></c><c r="B5" s="4"><v>123456</v></c><c r="C5" s="2"><v>123456.12300000001</v></c><c r="D5" s="5"><v>123456.12300000001</v></c><c r="E5" s="3"><v>123456.12300000001</v></c><c r="F5" s="1"><v>40890</v></c><c r="G5" s="6"><v>0.5083333333333333</v></c><c r="H5" s="8"><v>40890.508333333331</v></c></row><row r="6" spans="1:8" x14ac:dyDescent="0.25"><c r="A6" t="s"><v>11</v></c><c r="B6" s="4"><v>-123456</v></c><c r="C6" s="2"><v>-123456.789</v></c><c r="D6" s="5"><v>-123456.789</v></c><c r="E6" s="3"><v>-123456.789</v></c><c r="F6" s="1"><v>40890</v></c><c r="G6" s="7"><v>0.50918981481481485</v></c><c r="H6" s="9"><v>40890.50849537037</v></c></row></sheetData><pageMargins left="0.511811024" right="0.511811024" top="0.78740157499999996" bottom="0.78740157499999996" header="0.31496062000000002" footer="0.31496062000000002"/><pageSetup paperSize="9" orientation="portrait" horizontalDpi="0" verticalDpi="0" r:id="rId1"/><tableParts count="1"><tablePart r:id="rId2"/></tableParts></worksheet>');
        $zip->close();        
    }
    
    /**
     * 
     * /
    public function testHTMLRenderer_render() {
        $this->expectOutputString("<!DOCTYPE html><html><head><title></title><meta charset=\"UTF-8\"></head><body><table><thead><tr><th>#</th><th>Nome</th></tr></thead><tbody><tr><td>1</td><td>Kelson</td></tr><tr><td>2</td><td>KelsonCM</td></tr><tr><td>3</td><td></td></tr><tr><td>4</td><td></td></tr><tr><td>5</td><td>null</td></tr><tr><td>6</td><td></td></tr></tbody><tfoot><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tfoot></table></body></html>");

        $fieldSet = $this->getFieldset();
        $datasource = new ArrayDatasourceIterator($this->getData());
        $template = new BaseTemplate($fieldSet);
        $renderer = new HTMLRenderer($datasource, $template);
        
        $renderer->render();
    }

    /**/    
}
