<?php

[$weekday, $month, $monthday] = array_map('intval', explode(',', date('w,n,j')));

foreach ((array) ((require 'holidays.php')[$month] ?? []) as $dates)
	if ($holiday = in_array($monthday, $dates = (array) $dates)) break;
	else {
		$dates = array_column(array_filter($dates, 'is_array'), 1, 0);

		if (isset($dates[$weekday])) {
			[$current_weekday, $current_monthday] = [$weekday, $monthday];

			while ($current_monthday--) {
				if ($current_weekday === $weekday) $dates[$weekday]--;

				$current_weekday = $current_weekday ? $current_weekday - 1 : 6;
			}

			if ($holiday = $dates[$weekday] === 0) break;
		}
	}

$frame['text'] = ($day = (require 'days.php')[$weekday])['name'];

if ($holiday ?? FALSE) {
	if (isset($day['delete'])) $frame['text'] = mb_substr($frame['text'], $day['delete']);

	/**
	 * Add dot(s) after a word to move the word to an icon as closely as possible.
	 *
	 * @see https://help.lametric.com/support/discussions/topics/6000060555
	 */
	if (isset($day['suffix'])) $frame['text'] .= str_repeat('.', $day['suffix']);

	/**
	 * The "UA" icon identifier.
	 *
	 * @see https://developer.lametric.com/icons
	 */
	$frame['icon'] = $day['icon'] ?? 46587;
}

header('Content-Type: application/json');

echo json_encode(['frames' => [$frame]]);
