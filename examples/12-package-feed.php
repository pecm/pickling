<?php
declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

$pecl = Pickling\Factory::createPecl();

$packageName = 'amqp';
try {
    $feed = $pecl->getPackageFeed($packageName);
} catch (RuntimeException $exception) {
    printf('No feed found for package %s.' . PHP_EOL, $packageName);
    exit(1);
}

echo 'Feed for channel (',
$feed->getChannel(),
')',
' title (',
$feed->getTitle(),
')',
' description (',
$feed->getDescription(),
')',
PHP_EOL;

echo sprintf('Found %d entries for package %s', count($feed), $packageName),
PHP_EOL;

foreach ($feed as $item) {
    echo '#', $feed->key(), PHP_EOL;
    echo 'Title : ', $item->getTitle(), PHP_EOL;
    echo 'Link : ', $item->getLink(), PHP_EOL;
    echo 'Description : ', substr($item->getDescription(), 0, 40), PHP_EOL;
}
