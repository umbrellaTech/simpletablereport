<?php

/*
 * Copyright 2013 kelsoncm <falecom@kelsoncm.com>.
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
 * Description of BaseRenderer
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
abstract class BaseRenderer implements IRenderer {

    protected $configuration;
    protected $datasource;
    protected $template;
    
    function __construct($datasource, $template) {
        $this->datasource = $datasource;
        $this->template = $template;
        $configurationLoader = ConfigurationLoader::getInstance();
        $this->configuration = $configurationLoader->getConfiguration();
    }
    
    protected function getOption($optionName) {
        return $this->configuration->getOption("simpletablereport.{$optionName}");
    }
    
    protected function getValue(IDatasource $datasource, FieldDefinition $fieldDescription, $rendererPrefix) {
        $fieldTypeInstance = $fieldDescription->getFieldTypeInstance($rendererPrefix);
        $unformattedFieldValue = $datasource->getFieldValue($fieldDescription);
        return $fieldTypeInstance->render($unformattedFieldValue);
    }

}
