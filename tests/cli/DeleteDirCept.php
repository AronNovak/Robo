<?php
$I = new CliGuy($scenario);
$container = Robo\Config::getContainer();
$I->getContainer()->addServiceProvider(\Robo\Task\FileSystem\ServiceProvider::class);

$I->wantTo('delete dir with DeleteDirTask');
$I->amInPath(codecept_data_dir());
$I->seeFileFound('robo.txt', 'sandbox');
$I->taskDeleteDir(['sandbox/box'])
    ->run();
$I->dontSeeFileFound('box', 'sandbox');
$I->dontSeeFileFound('robo.txt', 'sandbox');
