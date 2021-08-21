<?php

const DAYS = [
  [
    'name' => 'неділя',
    'suffix' => 2,
  ],
  [
    'name' => 'п0неділ0к',
    'icon' => 46589, // The "UA Monday First Letters" icon identifier.
    'delete' => 2,
  ],
  [
    'name' => 'вівт0р0к',
    'icon' => 46586, // The "UA Tuesday First Letter" icon identifier.
    'delete' => 1,
    'suffix' => 1,
  ],
  ['name' => 'середа'],
  [
    'name' => '4етвер',
    'suffix' => 2,
  ],
  [
    'name' => 'п\'ятниця',
    'icon' => 46588, // The "UA Friday First Letter" icon identifier.
    'delete' => 1,
  ],
  [
    'name' => 'су60та',
    'suffix' => 1,
  ],
];

[$day_of_week, $month, $day_of_month] = explode(',', date('w,n,j'));

$day = DAYS[$day_of_week];
$name = $day['name'];

if ($month === '8' && $day_of_month === '24') {
  if (isset($day['delete'])) {
    $name = mb_substr($name, $day['delete']);
  }

  if (isset($day['suffix'])) {
    $name .= str_repeat(' ', $day['suffix']);
  }

  /**
   * The "UA" icon identifier.
   *
   * @see https://developer.lametric.com/icons
   *
   * @var int
   */
  $icon = $day['icon'] ?? 46587;
}

$frame = ['text' => $name];

if (isset($icon)) {
  $frame['icon'] = $icon;
}

$response = ['frames' => [$frame]];

header('Content-Type: text/json');

print json_encode($response);
