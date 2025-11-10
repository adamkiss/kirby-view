<?php

use AdamKiss\View\Plugin;
use AdamKiss\View\Template;
use Kirby\Cms\App;
use Kirby\Cms\Event;

@require_once __DIR__ . '/vendor/autoload.php';

App::plugin('adamkiss/view', [
	'options' => [
		'views' => function () {
			return kirby()->roots()->cache() . '/views';
		}
	],
	'components' => [
		'template' => function (App $kirby, string $name, ?string $contentType = null, ?string $defaultType = 'html') {
			return new Template($kirby, $name, $contentType, $defaultType);
		},
	],
	'hooks' => [
		'system.loadPlugins:after' => function (Event $event) {
			$vp = Plugin::initialize();
		},
	],
	'routes' => [
		[
			// Block all requests to /url.view and return 404
			'pattern' => '(:all)\.view',
			'action' => fn ($_) => false,
		],
	],
]);
