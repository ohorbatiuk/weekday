<?php

[$day_of_week, $month, $day_of_month] = explode(',', date('w,n,j'));
$days = json_decode(file_get_contents('days.json'), TRUE);
$day = $days[$day_of_week];
$name = $day['name'];

if ($month === '8' && $day_of_month === '24') {
  if (isset($day['delete'])) {
    $name = mb_substr($name, $day['delete']);
  }

  /**
   * Add dot(s) after a word to move the word to an icon as closely as possible.
   *
   * @see https://help.lametric.com/support/discussions/topics/6000060555
   */
  if (isset($day['suffix'])) {
    $name .= str_repeat('.', $day['suffix']);
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
