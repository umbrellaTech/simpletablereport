<?php

/*
 * Copyright 2013 kelsocm.
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
 * Description of CSVRenderer
 *
 * @author kelsocm
 */
class CSVRenderer implements IRenderer {
    
    public function render(IDatasourceIterator $datasource, ITemplate $template) {
        $this->iterateRows($datasource, $template);
    }
    
    protected function iterateRows(IDatasourceIterator $datasource, ITemplate $template) {
        $datasource->rewind();
        while ($datasource->valid()) {
            $this->iterateFields($datasource, $template);
            $datasource->next();
        }
    }
    
    protected function iterateFields(IDatasourceIterator $datasource, ITemplate $template) {
        $row = array();
        foreach ($template->getFields() as $fieldDescription) {
            $row[] = $datasource->getFieldValue($fieldDescription);
        }
        echo implode(',', $row) . "\n";
    }
}