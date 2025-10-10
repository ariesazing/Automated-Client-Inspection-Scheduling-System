<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Inspector Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $specialization
 * @property string|null $status
 * @property \Cake\I18n\FrozenTime|null $created_at
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Availability[] $availability
 * @property \App\Model\Entity\Inspection[] $inspections
 */
class Inspector extends Entity
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
        'user_id' => true,
        'name' => true,
        'specialization' => true,
        'status' => true,
        'created_at' => true,
        'user' => true,
        'availability' => true,
        'inspections' => true,
    ];
}
