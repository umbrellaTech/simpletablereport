<?php

namespace Umbrella\SimpleReport\Form;

class FormRenderer
{

    protected $element = array();
    protected $decorators;

    public function setLabel($name, array $attr)
    {
        $label = new \Umbrella\TagBuilder\TagBuilder("label");
        $label->mergeAttributes($attr);
        $label->setInnerHtml($name);
        $this->element[] = $label->toString(\Umbrella\TagBuilder\TagRenderMode::NORMAL);
    }

    public function setInput($name, array $attr)
    {
        $input = new \Umbrella\TagBuilder\TagBuilder("input");
        $input->mergeAttribute('type', 'text');
        $input->mergeAttributes($attr);
        $input->setInnerHtml($name);
        $this->element[] = $input->toString(\Umbrella\TagBuilder\TagRenderMode::SELF_CLOSING);
    }

    public function setSelect(array $values, array $attr)
    {
        $select = new \Umbrella\TagBuilder\TagBuilder("select");
        $select->mergeAttributes($attr);

        $options = "";
        foreach ($values as $key => $value) {
            $option = new \Umbrella\TagBuilder\TagBuilder("option");
            $option->setInnerHtml($key);
            $option->mergeAttribute('value', $value);
            $options = $option->toString(\Umbrella\TagBuilder\TagRenderMode::NORMAL);
        }
        $select->setInnerHtml($options);
        $this->element[] = $select->toString(\Umbrella\TagBuilder\TagRenderMode::NORMAL);
    }

    public function setElement($element)
    {
        $this->element[] = $element;
    }

    public function setDecorator($element)
    {
        $tag = new \Umbrella\TagBuilder\TagBuilder($element);
        $tag->setInnerHtml($this->render());
        $this->decorators[] = $tag->toString(\Umbrella\TagBuilder\TagRenderMode::NORMAL);
        $this->element = array();
        return $this;
    }

    public function render()
    {
        $html = "";
        $elements = $this->decorators ? $this->decorators + $this->element : $this->element;
        foreach ($elements as $element) {
            $html .= $element;
        }
        return $html;
    }

}
