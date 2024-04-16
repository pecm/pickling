<?php
declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

//$client = Pickling\Factory::createPecl();
$client = Pickling\Factory::createPear();

$userName = 'derick';

try {
  $feed = $client->getUserFeed($userName);
} catch (RuntimeException $exception) {
  printf('No feed found for user %s.' . PHP_EOL, $userName);
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

echo sprintf('Found %d entries for user %s', count($feed), $userName), PHP_EOL;

foreach ($feed as $item) {
  echo '#', $feed->key(), PHP_EOL;
  echo 'Title : ', $item->getTitle(), PHP_EOL;
  echo 'Link : ', $item->getLink(), PHP_EOL;
  echo 'Description : ', substr($item->getDescription(), 0, 40), PHP_EOL;
}
