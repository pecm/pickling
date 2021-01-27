<?php
declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

$pecl = Pickling\Factory::createPecl();

$release = $pecl->with('amqp')->at('latest');

$rinfo = $release->getInfo();

echo 'Package ',
  $release->getPackageName(),
  ' latest version (',
  $rinfo->getVersion(),
  '; ',
  $rinfo->getStability(),
  ') was released on ',
  $rinfo->getReleaseDate(),
  ' by ',
  $rinfo->getReleasingMaintainer(),
  PHP_EOL;
echo 'Download: ',
  $rinfo->getDownloadUri(),
  ' (',
  $rinfo->getReleaseSize(),
  ' bytes)',
  PHP_EOL;
echo 'Release notes: ', PHP_EOL;
echo $rinfo->getReleaseNotes(), PHP_EOL;
