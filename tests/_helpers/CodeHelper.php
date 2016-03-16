<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Robo\Config;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class CodeHelper extends \Codeception\Module
{
    protected static $testPrinter;
    protected static $capturedOutput;
    protected static $container;

    public function _before(\Codeception\TestCase $test)
    {
        static::$capturedOutput = '';
        static::$testPrinter = new BufferedOutput(OutputInterface::VERBOSITY_DEBUG);
        Config::setOutput(static::$testPrinter);

        static::$container = new \Robo\Container\RoboContainer();
        \Robo\Runner::configureContainer(static::$container, null, static::$testPrinter);
        Config::setContainer(static::$container);
    }

    public function _after(\Codeception\TestCase $test)
    {
        \AspectMock\Test::clean();
        $consoleOutput = new ConsoleOutput();
        Config::setOutput($consoleOutput);
        Config::setService('logger', new \Consolidation\Log\Logger($consoleOutput));
    }

    public function accumulate()
    {
        static::$capturedOutput .= static::$testPrinter->fetch();
        return static::$capturedOutput;
    }

    public function seeInOutput($value)
    {
        $output = $this->accumulate();
        $this->assertContains($value, $output);
    }

    public function seeOutputEquals($value)
    {
        $output = $this->accumulate();
        $this->assertEquals($value, $output);
    }
}
