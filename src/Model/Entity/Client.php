<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Client Entity
 *
 * @property int $id
 * @property string $owner_name
 * @property string $establishment_name
 * @property string $address
 * @property string $type
 * @property string|null $risk_level
 * @property string|null $status
 * @property \Cake\I18n\FrozenTime|null $created_at
 *
 * @property \App\Model\Entity\Inspection[] $inspections
 */
class Client extends Entity
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
        'owner_name' => true,
        'establishment_name' => true,
        'address' => true,
        'type' => true,
        'risk_level' => true,
        'status' => true,
        'created_at' => true,
        'inspections' => true,
    ];
}
