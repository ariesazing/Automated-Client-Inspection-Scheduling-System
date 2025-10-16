<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * InspectionResult Entity
 *
 * @property int $id
 * @property int $inspection_id
 * @property string|null $result
 * @property string|null $findings
 * @property string|null $recommendations
 * @property int|null $encoded_by
 * @property \Cake\I18n\FrozenTime|null $created_at
 *
 * @property \App\Model\Entity\Inspection $inspection
 */
class InspectionResult extends Entity
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
        'result' => true,
        'findings' => true,
        'recommendations' => true,
        'encoded_by' => true,
        'created_at' => true,
        'inspection' => true,
    ];
}
