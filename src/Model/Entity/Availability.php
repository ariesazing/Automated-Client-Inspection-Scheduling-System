<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Availability Entity
 *
 * @property int $id
 * @property int $inspector_id
 * @property \Cake\I18n\FrozenDate $available_date
 * @property bool|null $is_available
 * @property string $reason
 *
 * @property \App\Model\Entity\Inspector $inspector
 */
class Availability extends Entity
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
        'inspector_id' => true,
        'available_date' => true,
        'is_available' => true,
        'reason' => true,
        'inspector' => true,
    ];
}
