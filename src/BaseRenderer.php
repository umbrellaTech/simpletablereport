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

use Easy\Collections\Dictionary;
use Umbrella\SimpleReport\Api\FieldDefinition;
use Umbrella\SimpleReport\Api\DatasourceInterface;
use Umbrella\SimpleReport\Api\FieldSet;
use Umbrella\SimpleReport\Api\RendererInterface;

/**
 * Description of BaseRenderer
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 * @author ayrtonricardo <ayrton_jampa15@hotmail.com>
 */
abstract class BaseRenderer implements RendererInterface
{
    protected $configuration;

    /**
     * @var DatasourceInterface
     */
    protected $datasource;

    /**
     * @var FieldSet
     */
    protected $fieldset;

    /**
     * @var Dictionary
     */
    protected $options;
    
    protected $stringBuffer;

    public function __construct(DatasourceInterface $datasource, FieldSet $fieldset, Dictionary $options = null)
    {
        $this->datasource = $datasource;
        $this->fieldset = $fieldset;
        $this->options = new Dictionary();
        if ($options) {
            $this->options->concat($options);
        }

        $configurationLoader = ConfigurationLoader::getInstance();
        $this->configuration = $configurationLoader->getConfiguration();
    }
    
    public function getStringBuffer()
    {
        return $this->stringBuffer;
    }

    protected function write($string)
    {
        $this->stringBuffer .= $string;
    }

    protected function getOption($optionName)
    {
        return $this->configuration->getOption("simpletablereport.{$optionName}");
    }

    public function getOptions()
    {
        return $this->configuration->getOptions();
    }

    protected function getValue(DatasourceInterface $datasource, FieldDefinition $fieldDescription,
                                $rendererPrefix = null)
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
