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
        $array = [
            'admin' => 'Administrator',
            'inspector' => 'Inspector'
        ];

        return $array;
    }
    public function specialization()
    {
        $array = [

            'general' => 'General',
            'electrical' => 'Electrical',
            'mechanical' => 'Mechanical',
            'structural' => 'Structural',
            'hazardous' => 'Hazardous'
        ];
        return $array;
    }
    public function inspector_status()
    {
        $array = [
            'available' => 'Available',
            'on_inspection' => 'On Inspection',
            'on_leave' => 'On Leave',
        ];
        return $array;
    }
    public function user_status()
    {
        $array = [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];
        return $array;
    }
    public function client_status()
    {
        $array = [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];
        return $array;
    }
    public function inspection_status()
    {
        $array = [
            'scheduled' => 'Scheduled',
            'completed' => 'Completed',
            'missed' => 'Missed',
            'ongoing' => 'Ongoing',
        ];
        return $array;
    }

     public function client_type()
    {
        $array = [
            'residential' => 'Residential',
            'commercial' => 'Commercial',
            'industrial' => 'Industrial',
            'assembly' => 'Assembly',
            'storage' => 'Storage',
            'miscellaneous' => 'Miscellaneous',
        ];
        return $array;
    }
    public function risk_level()
    {
        $array = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High'
        ];
        return $array;
    }

     public function inspector_availability()
    {
        $array = [
            true => 'Available',
            false => 'Unavailable'
        ];
        return $array;
    }
}
