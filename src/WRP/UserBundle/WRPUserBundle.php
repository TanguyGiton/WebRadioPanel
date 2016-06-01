<?php

namespace WRP\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WRPUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}