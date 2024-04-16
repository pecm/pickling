<?php
declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

$pecl = Pickling\Factory::createPecl();

$clist = $pecl->getCategoryList();

echo 'Found ',
count($clist),
' categories on channel ',
$clist->getChannel(),
PHP_EOL;

echo 'First 10 categories:', PHP_EOL;
$count = 1;
foreach ($clist as $category) {
    echo $count, ' - ', $category, PHP_EOL;

    $count++;
    if ($count > 10) {
        break;
    }
}

echo '28th category: ', $clist[28], PHP_EOL;
echo '40th category: ', $clist[40], PHP_EOL;
