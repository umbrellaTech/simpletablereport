<?php

/*
 * Copyright 2013-2014 kelsoncm <falecom@kelsoncm.com>, ayrtonricardo <ayrton_jampa15@hotmail.com>.
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

namespace Umbrella\SimpleReport;

use Umbrella\SimpleReport\Api\FieldDefinition;
use Umbrella\SimpleReport\Api\IDatasource;
use Umbrella\SimpleReport\Api\IRenderer;
use Umbrella\SimpleReport\Api\ITemplate;

/**
 * Description of BaseRenderer
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 * @author ayrtonricardo <ayrton_jampa15@hotmail.com>
 */
abstract class BaseRenderer implements IRenderer
{

    protected $configuration;

    /**
     * @var IDatasource 
     */
    protected $datasource;

    /**
     * @var ITemplate 
     */
    protected $template;

    public function __construct(IDatasource $datasource, ITemplate $template)
    {
        $this->datasource = $datasource;
        $this->template = $template;
        $configurationLoader = ConfigurationLoader::getInstance();
        $this->configuration = $configurationLoader->getConfiguration();
    }

    protected function getOption($optionName)
    {
        return $this->configuration->getOption("simpletablereport.{$optionName}");
    }

    public function getOptions()
    {
        return $this->configuration->getOptions();
    }

    protected function getValue(IDatasource $datasource, FieldDefinition $fieldDescription, $rendererPrefix)
    {
        $fieldTypeName = $fieldDescription->getFieldType();
        $fieldTypeInstance = $this->configuration->getFieldTypeInstance($fieldTypeName, $rendererPrefix);
        $unformattedFieldValue = $datasource->getFieldValue($fieldDescription);
        $formattedFieldValue = $fieldTypeInstance->render($unformattedFieldValue);
        if (null !== $fieldDescription->getCallback()) {
            $formattedFieldValue = $fieldDescription->executeCallBack($formattedFieldValue, $datasource);
        }
        return $formattedFieldValue;
    }

}
