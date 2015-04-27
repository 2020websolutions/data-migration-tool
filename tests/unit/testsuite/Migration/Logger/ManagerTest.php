<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Migration\Logger;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var Logger|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $logger;

    /**
     * @var ConsoleHandler|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $consoleHandler;

    protected function setUp()
    {
        $this->logger = $this->getMock('Migration\Logger\Logger', [], [], '', false);
        $this->consoleHandler = $this->getMock('Migration\Logger\ConsoleHandler', [], [], '', false);
        $this->manager = new Manager($this->logger, $this->consoleHandler);
    }

    /**
     * @return array
     */
    public function dataProviderProcessSuccess()
    {
        return [
            ['logLevel' => 'info', 'logLevelCode' => 200],
            ['logLevel' => 'debug', 'logLevelCode' => 100],
            ['logLevel' => 'ERROR', 'logLevelCode' => 400],
            ['logLevel' => 'InFo', 'logLevelCode' => 200],
            ['logLevel' => 'Debug', 'logLevelCode' => 100],
        ];
    }

    /**
     * @param string $logLevel
     * @param int $logLevelCode
     * @dataProvider dataProviderProcessSuccess
     */
    public function testProcessSuccess($logLevel, $logLevelCode)
    {
        $this->consoleHandler->expects($this->once())->method('setLevel')->with($logLevelCode);
        $this->logger->expects($this->once())->method('pushHandler')->with($this->consoleHandler);
        $this->assertSame($this->manager, $this->manager->process($logLevel));
        $this->assertSame(Manager::getLogLevel(), strtolower($logLevel));
    }

    /**
     * @return array
     */
    public function dataProviderProcessInvalidLevel()
    {
        return [
            ['logLevel' => 'invalid'],
            ['logLevel' => 200]
        ];
    }

    /**
     * @param string $logLevel
     * @dataProvider dataProviderProcessInvalidLevel
     */
    public function testProcessInvalidLevel($logLevel)
    {
        $this->consoleHandler->expects($this->once())->method('setLevel')->with(200);
        $this->logger->expects($this->once())->method('error');
        $this->logger->expects($this->once())->method('pushHandler')->with($this->consoleHandler);
        $this->assertSame($this->manager, $this->manager->process($logLevel));
    }
}
