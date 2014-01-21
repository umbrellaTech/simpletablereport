<?php

use Umbrella\SimpleReport\Api\FieldSet;
use Umbrella\SimpleReport\Api\FieldType;
use Umbrella\SimpleReport\ArrayDatasource;
use Umbrella\SimpleReport\BaseTemplate;
use Umbrella\SimpleReport\Renderer\WkPdfRenderer;

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
 * @author Ítalo Lelis <italo@voxtecnologia.com.br>
 */
require_once 'SimpleTest.php';

class SimpleWkPdfTest extends SimpleTest
{

    /**
     * 
     * @return array
     */
    public function getData()
    {
        return array
            (
            array('id' => 1, 'nome' => 'Kelson'),
            array('id' => 2, 'nome' => 'KelsonCM'),
            array('id' => 3, 'nome' => ''),
            array('id' => 4, 'nome' => null),
            array('id' => 5, 'nome' => 'null', 'nome2' => '#error'),
            array('id' => 6, 'nome2' => '#error')
        );
    }

    public function getFieldset()
    {
        $fieldSet = new FieldSet();
        return $fieldSet
                        ->addField('id', '#', FieldType::INTEGER)
                        ->addField('nome', 'Nome', FieldType::STRING)
                        ->addField('razao', 'Razao', FieldType::FLOAT)
                        ->addField('salario', 'Salario', FieldType::DECIMAL)
                        ->addField('nascimento', 'Nascimento', FieldType::DATE)
                        ->addField('almoco', 'Almoco', FieldType::TIME)
                        ->addField('casamento', 'Casamento', FieldType::TIMESTAMP);
    }

    /**
     * 
     */
    public function testArrayDatasourceInitial()
    {
        $fieldSet = $this->getFieldset();
        $datasource = new ArrayDatasource($this->getData());

        $template = new BaseTemplate($fieldSet);
        $template->addParam('out', '/tmp/pdf/teste.pdf');
        $template->addParam('imageQuality', 100);
        $template->addParam('template', '/var/www/pdf/teste.html');

        $renderer = new WkPdfRenderer($datasource, $template);
        $renderer->render();
    }

}
