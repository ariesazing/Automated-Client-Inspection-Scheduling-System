<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InspectionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InspectionsTable Test Case
 */
class InspectionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\InspectionsTable
     */
    protected $Inspections;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Inspections',
        'app.Clients',
        'app.Inspectors',
        'app.InspectionResults',
        'app.SchedulingLogs',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Inspections') ? [] : ['className' => InspectionsTable::class];
        $this->Inspections = $this->getTableLocator()->get('Inspections', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Inspections);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\InspectionsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\InspectionsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
