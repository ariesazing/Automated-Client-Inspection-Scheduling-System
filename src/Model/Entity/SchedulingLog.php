<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SchedulingLog Entity
 *
 * @property int $id
 * @property int $inspection_id
 * @property \Cake\I18n\FrozenDate $old_date
 * @property \Cake\I18n\FrozenDate $new_date
 * @property string|null $reason
 * @property int $updated_by
 * @property \Cake\I18n\FrozenTime|null $created_at
 *
 * @property \App\Model\Entity\Inspection $inspection
 */
class SchedulingLog extends Entity
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
        'inspection_id' => true,
        'old_date' => true,
        'new_date' => true,
        'reason' => true,
        'updated_by' => true,
        'created_at' => true,
        'inspection' => true,
    ];
}
