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
    
    public function render() {
        $this->renderDocumentStart();
        $this->renderTable();
        $this->renderDocumentEnd();
    }
    
    protected function renderTable() {
        $this->renderTableStart();
        $this->renderTableHeader();
        $this->renderTableBody();
        $this->renderTableFooter();
        $this->renderTableEnd();
    }
    
    protected function renderTableHeader() {
        $this->renderTableHeaderStart();
        $this->renderTableHeaderRowStart();
        foreach ($this->template->getFields() as $value) {
            $this->renderTableHeaderDataStart();
            echo $value->getFieldCaption();
            $this->renderTableHeaderDataEnd();
        }
        $this->renderTableHeaderRowEnd();
        $this->renderTableHeaderEnd();
    }
    
    protected function renderTableBody() {
        $this->renderTableBodyStart();
        $this->renderTableBodyRows();
        $this->renderTableBodyEnd();
    }
    
    protected function renderTableBodyRows() {
        $this->datasource->rewind();
        while ($this->datasource->valid()) {
            $this->renderTableBodyRowStart();
            $this->renderTableBodyFields();
            $this->renderTableBodyRowEnd();
            $this->datasource->next();
        }
    }
    
    protected function renderTableBodyFields() {
        foreach ($this->template->getFields() as $value) {
            $this->renderTableBodyDataStart();
            echo $value->getFieldCaption();
            $this->renderTableBodyDataEnd();
        }
    }
    
    protected function renderTableFooter() {
        $this->renderTableFooterStart();
        $this->renderTableFooterRowStart();
        foreach ($this->template->getFields() as $value) {
            $this->renderTableFooterDataStart();
            echo '&nbsp;';
            $this->renderTableFooterDataEnd();
        }
        $this->renderTableFooterRowEnd();
        $this->renderTableFooterEnd();
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

    protected function renderDocumentStart() {
        echo $this->getOption('documentbody.start');
    }
    
    protected function renderTableStart() {
        echo $this->getOption('table.start');
    }
    
    protected function renderTableHeaderStart() {
        echo $this->getOption('table.head.start');
    }
    
    protected function renderTableHeaderRowStart() {
        echo $this->getOption('table.head.row.start');
    }
    
    protected function renderTableHeaderDataStart() {
        echo $this->getOption('table.head.data.start');
    }
    
    protected function renderTableHeaderDataEnd() {
        echo $this->getOption('table.head.data.end');
    }
    
    protected function renderTableHeaderRowEnd() {
        echo $this->getOption('table.head.row.end');
    }
    
    protected function renderTableHeaderEnd() {
        echo $this->getOption('table.head.end');
    }
    
    protected function renderTableBodyStart() {
        echo $this->getOption('table.body.start');
    }
    
    protected function renderTableBodyRowStart() {
        echo $this->getOption('table.body.row.start');
    }
    
    protected function renderTableBodyDataStart() {
        echo $this->getOption('table.body.data.start');
    }
    
    protected function renderTableBodyDataEnd() {
        echo $this->getOption('table.body.data.end');
    }
    
    protected function renderTableBodyRowEnd() {
        echo $this->getOption('table.body.row.end');
    }
    
    protected function renderTableBodyEnd() {
        echo $this->getOption('table.body.end');
    }
    
    protected function renderTableFooterStart() {
        echo $this->getOption('table.footer.start');
    }
    
    protected function renderTableFooterRowStart() {
        echo $this->getOption('table.footer.row.start');
    }
    
    protected function renderTableFooterDataStart() {
        echo $this->getOption('table.footer.data.start');
    }
    
    protected function renderTableFooterDataEnd() {
        echo $this->getOption('table.footer.data.end');
    }
    
    protected function renderTableFooterRowEnd() {
        echo $this->getOption('table.footer.row.end');
    }
    
    protected function renderTableFooterEnd() {
        echo $this->getOption('table.footer.end');
    }
    
    protected function renderTableEnd() {
        echo $this->getOption('table.end');
    }
    
    protected function renderDocumentEnd() {
        echo $this->getOption('documentbody.end');
    }

}
