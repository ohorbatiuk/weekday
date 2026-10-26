<?php

// Symbols mapping to convert a custom font to the default one.
// @var array<int, string>
const VOCABULARY = [
	0 => 'о',
	4 => 'ч',
	6 => 'б',
	8 => 'в',
];

// The "UA" icon identifier.
// @see https://developer.lametric.com/icons
// @var int
const ICON = 46587;

// Key names for the days array storing icons for each font type.
// @var string[]
const KEYS = ['icon', 'iconDefault'];

// The serial number of Tuesday in the week that begins on Sunday.
// @var int
const TUESDAY = 2;

[$weekday, $month, $monthday] = array_map('intval', explode(',', date('w,n,j')));

foreach ((array) ((require 'holidays.php')[$month] ?? []) as $date)
	if (is_numeric($date))
	{
		if ($holiday = $date === $monthday) break;
	}
	elseif ($date[0] === $weekday)
	{
		[$current_weekday, $current_monthday] = [$weekday, $monthday];

		while ($current_monthday--)
		{
			if ($current_weekday === $weekday) $date[1]--;

			$current_weekday = $current_weekday ? $current_weekday - 1 : 6;
		}

		if ($holiday = $date[1] === 0) break;
	}

$frame['text'] = ($day = (require 'days.php')[$weekday])['name'];

if ($default = ($_GET['font'] ?? '') === 'Default') $frame['text'] = strtr($frame['text'], VOCABULARY);

if ($holiday ?? FALSE)
{
	if (isset($day['delete'])) $frame['text'] = mb_substr($frame['text'], $day['delete']);

	// Add dot(s) after a word to move the word to an icon as closely as possible.
	// @see https://help.lametric.com/support/discussions/topics/6000060555
	if (isset($day['suffix'])) $frame['text'] .= str_repeat('.', $day['suffix']);

	if ($default) $frame['icon'] = $day[KEYS[$weekday !== TUESDAY]] ?? null;

	$frame['icon'] ??= $day[KEYS[$weekday === TUESDAY]] ?? ICON;
}

header('Content-Type: application/json');

echo json_encode(['frames' => [$frame]]);
