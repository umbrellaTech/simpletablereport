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
    
    public function render(IDatasourceIterator $datasource, ITemplate $template) {
        $configurationLoader = ConfigurationLoader::getInstance();
        $this->configuration = $configurationLoader->getConfiguration();
        $this->iterateRows($datasource, $template);
    }

    protected function iterateRows(IDatasourceIterator $datasource, ITemplate $template) {
        $datasource->rewind();
        while ($datasource->valid()) {
            $this->iterateFields($datasource, $template);
            $datasource->next();
        }
    }
    
    protected function getValue(IDatasourceIterator $datasource, FieldDefinition $fieldDescription, $rendererPrefix) {
        $fieldTypeName = $fieldDescription->getFieldType();
        $fieldTypeInstance = $this->configuration->getFieldTypeInstance($fieldTypeName, $rendererPrefix);
        $unformattedFieldValue = $datasource->getFieldValue($fieldDescription);
        return $fieldTypeInstance->render($unformattedFieldValue);
    }

    abstract protected function iterateFields(IDatasourceIterator $datasource, ITemplate $template);

}
