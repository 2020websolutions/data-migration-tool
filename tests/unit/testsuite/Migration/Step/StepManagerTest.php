<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Migration\Step;

/**
 * Class StepManagerTest
 */
class StepManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StepManager
     */
    protected $manager;

    /**
     * @var \Migration\Step\StepFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $factory;

    /**
     * @var \Migration\Logger\Logger|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $logger;

    public function setUp()
    {
        $this->factory = $this->getMockBuilder('\Migration\Step\StepFactory')->disableOriginalConstructor()
            ->setMethods(['getSteps'])
            ->getMock();
        $this->logger = $this->getMockBuilder('\Migration\Logger\Logger')->disableOriginalConstructor()
            ->setMethods(['info'])
            ->getMock();
        $this->manager = new StepManager($this->logger, $this->factory);
    }

    public function testRunSteps()
    {
        $step = $this->getMock('\Migration\Step\StepInterface', [], [], '', false);
        $step->expects($this->once())->method('run');
        $this->factory->expects($this->once())->method('getSteps')->will($this->returnValue([$step]));
        $this->logger->expects($this->at(0))->method('info')->with("Step 1 of 1");
        $this->logger->expects($this->at(1))->method('info')->with(PHP_EOL . "Migration completed");
        $this->assertSame($this->manager, $this->manager->runSteps());
    }
}
