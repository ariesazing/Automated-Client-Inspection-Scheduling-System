<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SchedulingLogsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SchedulingLogsTable Test Case
 */
class SchedulingLogsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SchedulingLogsTable
     */
    protected $SchedulingLogs;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.SchedulingLogs',
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
        $config = $this->getTableLocator()->exists('SchedulingLogs') ? [] : ['className' => SchedulingLogsTable::class];
        $this->SchedulingLogs = $this->getTableLocator()->get('SchedulingLogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SchedulingLogs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SchedulingLogsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SchedulingLogsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
