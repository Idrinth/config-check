<?php
namespace De\Idrinth\ConfigCheck\Data;

class Config
{
    public function isEnabled($type) {
        return true;
    }
    public function getBlacklist($type) {
        return array('vendor');
    }
}