<?php

const DAYS = [
  'п0неділ0к',
  'вівт0р0к',
  'середа',
  '4етвер',
  'п\'ятниця',
  'су60та',
  'неділя',
];

$response = [
  'frames' => [
    [
      'text' => DAYS[date('N') - 1],
    ],
  ],
];

header('Content-Type: text/json');

print json_encode($response);
