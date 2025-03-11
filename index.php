<?php

[$day_of_week, $month, $day_of_month] = array_map('intval', explode(',', date('w,n,j')));
$day = (require 'days.php')[$day_of_week];
$name = $day['name'];
$holiday = FALSE;

foreach (require 'holidays.php' as $date) {
	if ($month === $date['month']) {
		if ($day_of_month === ($date['day'] ?? 0)) {
			$holiday = TRUE;
			break;
		}
		elseif ($day_of_week === ($date['day-of-week'] ?? 0)) {
			if (($expected_repeats = $date['repeats'] ?? 1) > 1) {
				$found_repeats = 0;
				$current_day_of_week = $day_of_week;
				$current_day_of_month = $day_of_month;

				while ($current_day_of_month--) {
					if ($current_day_of_week == $day_of_week) {
						$found_repeats++;
					}

					if ($current_day_of_week) {
						$current_day_of_week--;
					}
					else {
						$current_day_of_week = 6;
					}
				}

				if ($found_repeats != $expected_repeats) {
					continue;
				}
			}

			$holiday = TRUE;
			break;
		}
	}
}

if ($holiday) {
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
	 * @var int $icon
	 */
	$icon = $day['icon'] ?? 46587;
}

$frame = ['text' => $name];

if (isset($icon)) {
	$frame['icon'] = $icon;
}

$response = ['frames' => [$frame]];

header('Content-Type: application/json');

echo json_encode($response);
