<?php

use \Umbrella\SimpleReport\Api\FieldSet;
use \Umbrella\SimpleReport\Api\FieldType;
use \Umbrella\SimpleReport\ArrayDatasource;
use \Umbrella\SimpleReport\BaseTemplate;
use \Umbrella\SimpleReport\Renderer\HtmlRenderer;

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

class SimpleHtmlTest extends SimpleTest
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
            array('id' => 6, 'nome2' => '#error'),
        );
    }

    public function getDataCount()
    {
        return array(
            array('nome' => 'Ayrton', 'salario' => 1522, 'pagamento' => 1560),
            array('nome' => 'Ayrton2', 'salario' => 15222, 'pagamento' => 154560),
            array('nome' => 'Ayrton3', 'salario' => 15322, 'pagamento' => 154560),
            array('nome' => 'Ayrton4', 'salario' => 15422, 'pagamento' => 154560),
            array('nome' => null, 'salario' => 0, 'pagamento' => 156340),
            array('nome' => 'Ayrton6', 'salario' => 15522, 'pagamento' => 152360),
            array('nome' => '', 'salario' => 15722, 'pagamento' => null)
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
     * Create the fieldset for test of columnCount
     * @return $this
     */
    public function getFieldsetCount()
    {
        $fieldSet = new FieldSet();

        //array('nome' => 'Ayrton', 'salario' => 1522, 'pagamento' => 1560),
        return $fieldSet
            ->addField('nome', 'Nome', FieldType::STRING)
            ->addField('salario', 'Salário', FieldType::INTEGER)
            ->addField('pagamento', 'Razao', FieldType::INTEGER);
    }

    /**
     *
     */
    public function testArrayDatasourceInitial()
    {
        $fieldSet = $this->getFieldset();
        $datasource = new ArrayDatasource($this->getData());
        $template = new BaseTemplate($fieldSet);
        $renderer = new HtmlRenderer($datasource, $template);

        $renderer->render();
    }

    /**
     * Test the implementation of the column count in the ArrayDataSource
     */
    public function testArrayDataSourceColumnCount()
    {
        $fieldSet = $this->getFieldsetCount();
        $datasource = new ArrayDatasource($this->getDataCount());
        $datasource->ColumnCount(true)
            ->setColumnFieldCounts(array(
                'nome' => array('label' => 'Nada', 'prefix' => 'strong', 'suffix' => ' - 1'),
                'salario',
                'pagamento' => ''
            ))
            ->appendColumnCount();
        $template = new BaseTemplate($fieldSet);
        $renderer = new HtmlRenderer($datasource, $template);

        $renderer->render();
    }

}
