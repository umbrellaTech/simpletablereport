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
    }
    
    public function getFieldset() {
        $fieldSet = new FieldSet();
        return $fieldSet->addField('id','#')->addField('nome','Nome');
    }
    
    /**
     * 
     */
    public function testCSVRenderer_render() {
        $this->expectOutputString("1,Kelson\n2,KelsonCM\n3,\n4,\n5,null\n6,\n");
        
        $fieldSet = $this->getFieldset();
        $datasource = new ArrayDatasourceIterator($this->getData());
        $template = new BaseTemplate($fieldSet);
        $renderer = new CSVRenderer();
        
        $renderer->render($datasource, $template);
    }
    
    /**
     * 
     */
    public function testHTMLRenderer_render() {
        $this->expectOutputString("<!DOCTYPE html><html><head><title></title><meta charset=\"UTF-8\"></head><body><table><thead><tr><th>#</th><th>Nome</th></tr></thead><tbody><tr><td>1</td><td>Kelson</td></tr></tbody><tbody><tr><td>2</td><td>KelsonCM</td></tr></tbody><tbody><tr><td>3</td><td></td></tr></tbody><tbody><tr><td>4</td><td></td></tr></tbody><tbody><tr><td>5</td><td>null</td></tr></tbody><tbody><tr><td>6</td><td></td></tr></tbody><tfoot><tr></tr></tfoot></table></body></html>");

        $fieldSet = $this->getFieldset();
        $datasource = new ArrayDatasourceIterator($this->getData());
        $template = new BaseTemplate($fieldSet);
        $renderer = new HTMLRenderer();
        
        $renderer->render($datasource, $template);
    }
    
}
