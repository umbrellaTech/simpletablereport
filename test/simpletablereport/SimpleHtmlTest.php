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

class SimpleHtmlTest extends SimpleTest {
    
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
    }
    
    public function getFieldset() {
        $fieldSet = new FieldSet();
        return $fieldSet
                ->addField('id','#', FieldType::INTEGER)
                ->addField('nome','Nome', FieldType::STRING)
                ->addField('razao', 'Razao', FieldType::FLOAT)
                ->addField('salario', 'Salario', FieldType::DECIMAL)
                ->addField('nascimento', 'Nascimento', FieldType::DATE)
                ->addField('almoco', 'Almoco', FieldType::TIME)
                ->addField('casamento', 'Casamento', FieldType::TIMESTAMP);
    }
    
    public function testArrayDatasource_Initial() {
        $fieldSet = $this->getFieldset();
        $datasource = new ArrayDatasource($this->getData());
        $template = new BaseTemplate($fieldSet);
        $renderer = new HtmlRenderer($datasource, $template);
        
        $renderer->render();
        $this->assertEquals('<!DOCTYPE html><html><head><title></title><meta charset="UTF-8"></head><body><table><thead><tr><th>#</th><th>Nome</th><th>Razao</th><th>Salario</th><th>Nascimento</th><th>Almoco</th><th>Casamento</th></tr></thead><tbody><tr><td>1</td><td>Kelson</td><td></td><td></td><td></td><td></td><td></td></tr><tr><td>2</td><td>KelsonCM</td><td></td><td></td><td></td><td></td><td></td></tr><tr><td>3</td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td>4</td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td>5</td><td>null</td><td></td><td></td><td></td><td></td><td></td></tr><tr><td>6</td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody><tfoot><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tfoot></table></body></html>', $renderer->getStringBuffer());
    }
    
}
