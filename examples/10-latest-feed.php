<?php
declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

$pecl = Pickling\Factory::createPecl();

try {
    $feed = $pecl->getLatestFeed();
} catch (RuntimeException $exception) {
    echo 'No recent feed found ', PHP_EOL;
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

echo sprintf('Found %d entries', count($feed)),
PHP_EOL;

foreach ($feed as $item) {
    echo '#', $feed->key(), PHP_EOL;
    echo 'Title : ', $item->getTitle(), PHP_EOL;
    echo 'Link : ', $item->getLink(), PHP_EOL;
    echo 'Description : ', substr($item->getDescription(), 0, 40) . ' ...', PHP_EOL;
}
