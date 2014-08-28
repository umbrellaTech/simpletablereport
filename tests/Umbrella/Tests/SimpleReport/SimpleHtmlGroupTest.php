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
namespace Umbrella\Tests\SimpleReport;

use Umbrella\SimpleReport\Api\FieldSet;
use Umbrella\SimpleReport\Api\FieldType;
use Umbrella\SimpleReport\ArrayDatasource;
use Umbrella\SimpleReport\BaseTemplate;
use Umbrella\SimpleReport\Renderer\GroupedHtmlRenderer;

class SimpleHtmlGroupTest extends SimpleTest
{

    /**
     * 
     * @return array
     */
    public function getData()
    {
        return array
            (
            array('id' => 1, 'nome' => 'Kelson', 'qtde' => 1),
            array('id' => 1, 'nome' => 'KelsonCM', 'qtde' => 2),
            array('id' => 1, 'nome' => '', 'qtde' => 5),
            array('id' => 4, 'nome' => null, 'qtde' => 4),
            array('id' => 5, 'nome' => 'null', 'nome2' => '#error', 'qtde' => 2),
            array('id' => 6, 'nome2' => '#error', 'qtde' => 3),
        );
    }

    public function getFieldset()
    {
        $fieldSet = new FieldSet();
        return $fieldSet
                ->addField('id', '#', FieldType::INTEGER)
                ->addField('nome', 'Nome', FieldType::STRING)
                ->addField('qtde', 'Quantidade', FieldType::INTEGER)
        ;
    }

    /**
     * 
     */
    public function testArrayDatasource_Initial()
    {
        $fieldSet = $this->getFieldset();
        $datasource = new ArrayDatasource($this->getData());
        $template = new BaseTemplate($fieldSet);
        $template->addParam('grouped', 1);
        $renderer = new GroupedHtmlRenderer($datasource, $template);

        $renderer->render();
    }
}
