<?php

/*
 * Copyright 2014 kelsoncm <falecom@kelsoncm.com>.
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
 * Description of HTMLRenderer
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class HTMLRenderer extends BaseRenderer {
    
    public function getOption($optionName) {
        return parent::getOption("htmlrenderer.{$optionName}");
    }

    protected function iterateRows(IDatasourceIterator $datasource, ITemplate $template) {
        echo $this->getOption('documentbody.start');
        echo $this->getOption('table.start');
        $this->renderTable($datasource, $template);
        echo $this->getOption('table.end');
        echo $this->getOption('documentbody.end');
    }
    
    protected function renderTable(IDatasourceIterator $datasource, ITemplate $template) {
        $this->renderHeader($datasource, $template);
        $datasource->rewind();
        while ($datasource->valid()) {
            $this->iterateFields($datasource, $template);
            $datasource->next();
        }
        $this->renderFooter($datasource, $template);
    }
    
    protected function renderHeader(IDatasourceIterator $datasource, ITemplate $template) {
        echo "{$this->getOption('table.head.start')}{$this->getOption('table.head.row.start')}";
        foreach ($template->getFields() as $value) {
            echo $this->getOption('table.head.data.start');
            echo $value->getFieldCaption();
            echo $this->getOption('table.head.data.end');
        }
        echo "{$this->getOption('table.head.row.end')}{$this->getOption('table.head.end')}";
    }
    
    protected function renderFooter(IDatasourceIterator $datasource, ITemplate $template) {
        echo "{$this->getOption('table.footer.start')}{$this->getOption('table.footer.row.start')}";
        foreach ($template as $value) {
            echo $this->getOption('table.footer.data.start');
            echo '';
            echo $this->getOption('table.footer.data.end');
        }
        echo "{$this->getOption('table.footer.row.end')}{$this->getOption('table.footer.end')}";
    }
        
    protected function iterateFields(IDatasourceIterator $datasource, ITemplate $template) {
        echo "{$this->getOption('table.body.start')}{$this->getOption('table.body.row.start')}";
        $i = 0;
        foreach ($template->getFields() as $fieldDescription) {
            $data = 'data' . (++$i % 2 ? '1' : '2'); 
            echo $this->getOption("table.body.{$data}.start");
            echo $this->getValue($datasource, $fieldDescription, 'HTML');
            echo $this->getOption("table.body.{$data}.end");
        }
        echo "{$this->getOption('table.body.row.end')}{$this->getOption('table.body.end')}";
    }

}
