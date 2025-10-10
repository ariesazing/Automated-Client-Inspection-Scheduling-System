<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * Options helper
 */
class OptionsHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];

    public function roles()
    {
        $array =[
            'admin'=>'Administrator',
            'inspector'=>'Inspector'
        ];

        return $array;
    }
     public function status()
    {
        $array =[
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];
        return $array;
    }
}
