<?php

namespace Umbrella\SimpleReport\Renderer;

use Umbrella\SimpleReport\BaseRenderer;

/**
 * Description of CSVRenderer
 *
 * @author kelsoncm
 */
class CsvRenderer extends BaseRenderer
{

    /**
     * @author Ayrton Ricardo<ayrton@voxtecnologia.com.br>
     * @return mixed
     */
    public function render()
    {
        $this->stringBuffer = '';

        $this->renderHeader();
        for ($this->datasource->rewind(); $this->datasource->valid(); $this->datasource->next()) {
            $this->renderRow();
        }

        return $this->getStringBuffer();
    }

    /**
     * @author Ayrton Ricardo<ayrton@voxtecnologia.com.br>
     * @param $string
     */
    protected function write($string)
    {
        $this->stringBuffer .= $string;
    }

    /**
     * @author Ayrton Ricardo<ayrton@voxtecnologia.com.br>
     */
    protected function renderRow()
    {
        $row = array();
        foreach ($this->fieldset as $fieldDescription) {
            $row[] = $this->getValue($this->datasource, $fieldDescription);
        }
        $this->write(implode(';', $row) . "\n");
    }

    /**
     * @author Ayrton Ricardo<ayrton@voxtecnologia.com.br>
     */
    protected function renderHeader()
    {
        foreach ($this->fieldset as $value) {
            $this->write($value->getFieldCaption() . ';');
        }
        $this->write("\n");
    }
}
