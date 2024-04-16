<?php
declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

$pecl = Pickling\Factory::createPecl();

$categoryName = 'authentication';
try {
  $feed = $pecl->getCategoryFeed($categoryName);
} catch (RuntimeException $exception) {
  printf('No feed found for category %s.' . PHP_EOL, $categoryName);
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

echo sprintf('Found %d entries on category %s', count($feed), $categoryName), PHP_EOL;

foreach ($feed as $item) {
  echo '#', $feed->key(), PHP_EOL;
  echo 'Title : ', $item->getTitle(), PHP_EOL;
  echo 'Link : ', $item->getLink(), PHP_EOL;
  echo 'Description : ', substr($item->getDescription(), 0, 40), PHP_EOL;
}
