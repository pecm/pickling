<?php
declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

$pecl = Pickling\Factory::createPecl();

$plist = $pecl->getPackageList();

echo 'Found ',
  count($plist),
  ' packages on channel ',
  $plist->getChannel(),
  PHP_EOL;

echo 'First 10 packages:', PHP_EOL;
$count = 1;
foreach ($plist as $package) {
  echo $count, ' - ', $package, PHP_EOL;

  $count++;
  if ($count > 10) {
    break;
  }
}

echo '28th package: ', $plist[28], PHP_EOL;
echo '40th package: ', $plist[40], PHP_EOL;
