<?php

namespace De\Idrinth\ConfigCheck\Data;

class Config
{

    /**
     * @param string $type
     * @return boolean
     */
    public function isEnabled($type)
    {
        return true;
    }

    /**
     * @param string $type
     * @return string[]
     */
    public function getBlacklist($type)
    {
        return array('vendor');
    }
}
