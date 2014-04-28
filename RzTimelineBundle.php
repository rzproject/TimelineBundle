<?php

namespace Rz\TimelineBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class RzTimelineBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'SonataTimelineBundle';
    }
}
