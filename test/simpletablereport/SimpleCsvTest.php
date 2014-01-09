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

class SimpleCsvTest extends SimpleTest {
    
    /**
     * 
     */
    public function testArrayDatasource_TypeInteger() {
        $this->expectOutputString("0\n1\n2\n3\n4\n\n\n\n5\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::INTEGER);
        
        $model = $this->arrayArray('i', array(0, 1, '2', 3.3, 4.0, null, '', ' ', 5));
        
        $datasource = new ArrayDatasource($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CsvRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testArrayDatasource_TypeFloat() {
        $this->expectOutputString("0\n1.1\n2.4\n4\n\n\n\n5.6\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::FLOAT);
        
        $model = $this->arrayArray('i', array(0, 1.1, '2.4', 4, null, '', ' ', 5.60));
        
        $datasource = new ArrayDatasource($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CsvRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testArrayDatasource_TypeDecimal() {
        $this->expectOutputString("0.000\n1.100\n2.400\n4.000\n5.678\n6.790\n\n\n\n5.600\n654321.000\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::DECIMAL);
        
        $model = $this->arrayArray('i', array(0, 1.1, '2.4', 4, 5.678, 6.789876, null, '', ' ', 5.60, 654321));
        
        $datasource = new ArrayDatasource($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CsvRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testArrayDatasource_TypeMoney() {
        $this->expectOutputString("R$0.0000\nR$1.1000\nR$2.4000\nR$4.0000\nR$5.6780\nR$6.7899\n\n\n\nR$5.6000\nR$654321.0000\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::MONEY);
        
        $model = $this->arrayArray('i', array(0, 1.1, '2.4', 4, 5.678, 6.789876, null, '', ' ', 5.60, 654321));
        
        $datasource = new ArrayDatasource($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CsvRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testArrayDatasource_TypeDate() {
        $today = new DateTime();
        $today->setTimestamp(time());

        $this->expectOutputString($today->format('d/m/Y') . "\n" . $today->format('d/m/Y') . "\n13/12/2011\n15/12/2013\n\n\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::DATE);
        
        $model = $this->arrayArray('i', array(time(), $today, '13/12/2011', array('year'=>2013, 'mon'=>12, 'mday'=>15), null, '', ));
        
        $datasource = new ArrayDatasource($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CsvRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testArrayDatasource_TypeTime() {
        $today = new DateTime();
        $today->setTimestamp(time());

        $this->expectOutputString($today->format('H:i:s') . "\n" . $today->format('H:i:s') . "\n23:59:00\n22:00:00\n\n\n23:59:35\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::TIME);
        
        $model = $this->arrayArray('i', array(time(), $today, '23:59', array('hours'=>22, 'minutes'=>00, 'seconds'=>00), null, '', '23:59:35', ));
 
        $datasource = new ArrayDatasource($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CsvRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testArrayDatasource_TypeTimestamp() {
        $today = new DateTime();
        $today->setTimestamp(time());

        $this->expectOutputString($today->format('d/m/Y H:i:s') . "\n" . $today->format('d/m/Y H:i:s') . "\n15/12/2013 23:59:00\n20/11/2011 22:00:00\n\n\n01/01/1991 23:59:35\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::TIMESTAMP);
        
        $model = $this->arrayArray('i', array(time(), $today, '15/12/2013 23:59', array('year'=>2011, 'mon'=>11, 'mday'=>20, 'hours'=>22, 'minutes'=>00, 'seconds'=>00), null, '', '01/01/1991 23:59:35', ));
 
        $datasource = new ArrayDatasource($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CsvRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testArrayDatasource_TypeString() {
        $this->expectOutputString("umbrella\nsimpletablereport\n\n\n\nkelsoncm\nline1 line2\n");
        
        $fieldSet = new FieldSet();
        $fieldSet->addField('i', '#', FieldType::STRING);
        
        $model = $this->arrayArray('i', array('umbrella', 'simpletablereport', null, '', ' ', 'kelsoncm',"line1\nline2",));
        
        $datasource = new ArrayDatasource($model);
        $template = new BaseTemplate($fieldSet);
        $renderer = new CsvRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testArrayDatasource_emptyArray() {
        $this->expectOutputString("");
        
        $datasource = new ArrayDatasource(array());
        $template = new BaseTemplate(new FieldSet());
        $renderer = new CsvRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
    
    /**
     *  @expectedException InvalidArgumentException
     *  @expectedExceptionMessage Passed variable is not an array or object, using empty array instead
     */
    public function testArrayDatasource_null() {
        $this->expectOutputString("");
        
        $datasource = new ArrayDatasource(null);
        $template = new BaseTemplate(new FieldSet());
        $renderer = new CsvRenderer($datasource, $template);
        
        $renderer->render($datasource, $template);
    }
}
