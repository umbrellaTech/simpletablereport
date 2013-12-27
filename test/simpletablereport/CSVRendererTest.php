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
class CSVRendererTest extends PHPUnit_Framework_TestCase {
    
    public function testTest() {
        $model = array(
                    array('id'=>1,'nome'=>'Kelson'),
                    array('id'=>2,'nome'=>'KelsonCM'),
                    array('id'=>3,'nome'=>''),
                    array('id'=>4,'nome'=>null),
                    array('id'=>5,'nome'=>'null', 'nome2'=>'#error'),
                    array('id'=>6,'nome2'=>'#error'),
                 );

        $fieldSet = new FieldSet();
        $fieldSet->addField('id','#');
        $fieldSet->addField('nome','Nome');

        $datasource = new ArrayDatasourceIterator($model);

        $template = new BaseTemplate($fieldSet);
        
        $renderer = new CSVRenderer();
        $renderer->render($datasource, $template);
    }
    
}
