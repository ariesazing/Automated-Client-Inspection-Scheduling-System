<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InspectionResultsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InspectionResultsTable Test Case
 */
class InspectionResultsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\InspectionResultsTable
     */
    protected $InspectionResults;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.InspectionResults',
        'app.Inspections',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('InspectionResults') ? [] : ['className' => InspectionResultsTable::class];
        $this->InspectionResults = $this->getTableLocator()->get('InspectionResults', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->InspectionResults);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\InspectionResultsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\InspectionResultsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
