<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InspectionsFixture
 */
class InspectionsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'client_id' => 1,
                'inspector_id' => 1,
                'inspection_type' => 'Lorem ipsum dolor sit amet',
                'scheduled_date' => '2025-10-16 05:09:40',
                'actual_date' => '2025-10-16 05:09:40',
                'status' => 'Lorem ipsum dolor sit amet',
                'remarks' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'risk_level' => 'Lorem ipsum dolor sit amet',
                'created_at' => '2025-10-16 05:09:40',
                'updated_at' => '2025-10-16 05:09:40',
            ],
        ];
        parent::init();
    }
}
