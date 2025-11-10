<?php

use Kirby\Template\Slot;
use Kirby\Template\Snippet;
use Tempest\Support\Arr\ImmutableArray;
use Tempest\View\Slot as ViewSlot;

/** @var ImmutableArray $attributes */
/** @var ImmutableArray $slots */

if (!$attributes->hasKey('name')) {
	throw new InvalidArgumentException('The "name" attribute is required for the "x-snippet" component.');
}

$attributes = $attributes->toMutableArray();
$name = $attributes->pull('name');

$slots = $slots
	->map(fn(ViewSlot $slot, $name) => new Slot($name, $slot->content))
	->toArray();

$snippet = new Snippet($name, $attributes->toArray());
echo $snippet->render($attributes->toArray(), $slots);

?>
