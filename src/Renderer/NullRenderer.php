<?php

namespace Umbrella\SimpleReport\Renderer;

use Umbrella\SimpleReport\BaseRenderer;

/**
 * Description of HTMLRenderer
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class NullRenderer extends BaseRenderer
{
    
    public function render()
    {
        return null;
    }

}
