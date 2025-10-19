<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Inspection Entity
 *
 * @property int $id
 * @property int $client_id
 * @property int $inspector_id
 * @property string|null $inspection_type
 * @property \Cake\I18n\FrozenTime $scheduled_date
 * @property \Cake\I18n\FrozenTime|null $actual_date
 * @property string|null $status
 * @property string|null $remarks
 * @property string|null $risk_level
 * @property \Cake\I18n\FrozenTime|null $created_at
 * @property \Cake\I18n\FrozenTime|null $updated_at
 *
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\Inspector $inspector
 * @property \App\Model\Entity\SchedulingLog[] $scheduling_logs
 */
class Inspection extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'client_id' => true,
        'inspector_id' => true,
        'scheduled_date' => true,
        'actual_date' => true,
        'status' => true,
        'remarks' => true,
        'risk_level' => true,
        'created_at' => true,
        'updated_at' => true,
        'client' => true,
        'inspector' => true,
        'scheduling_logs' => true,
    ];
}
