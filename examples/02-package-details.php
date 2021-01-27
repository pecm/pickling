<?php
declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

$pecl = Pickling\Factory::createPecl();

$package = $pecl->with('amqp');

$rlist = $package->getReleaseList();

echo 'Package ',
  $package->getName(),
  ' has ',
  count($rlist),
  ' releases',
  PHP_EOL;
echo 'Latest: ', $package->getLatestVersion(), PHP_EOL;
echo '4th release: ', $rlist[4]->getNumber() , ' (', $rlist[4]->getStability(), ')', PHP_EOL;

$pinfo = $package->getInfo();

echo 'License: ', $pinfo->getLicense(), PHP_EOL;
echo 'Summary: ', $pinfo->getSummary(), PHP_EOL;
