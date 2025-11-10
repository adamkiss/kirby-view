<?php

namespace AdamKiss\View;

use Kirby\Toolkit\A;
use Kirby\Filesystem\F;
use Kirby\Filesystem\Dir;
use Tempest\View\ViewConfig;
use Tempest\View\GenericView;
use Tempest\View\ViewComponent;
use Adamkiss\View\Renderer;
use Tempest\View\ViewCache;
use Tempest\View\ViewCachePool;

class Plugin {
	public static ?Plugin $instance = null;
	private ?Renderer $renderer = null;

	public function __construct() {
		$__ = Dir::index(kirby()->roots()->snippets(), true);
		$__ = A::filter($__, fn ($path) => str_ends_with($path, '.view.php'));
		$__ = A::map($__, function ($path) {
			$prepend = str_replace('/', '-', dirname($path));
			$prepend = $prepend === '.' ? '' : $prepend . '-';
			$name = basename($path, '.view.php');
			return new ViewComponent(
				"x-{$prepend}{$name}",
				F::read(kirby()->roots()->snippets() . '/' . $path),
				$path,
				false
			);
		});
		$components = A::keyBy($__, fn (ViewComponent $component) => $component->name);
		$components['x-snippet'] = new ViewComponent(
			"x-snippet",
			F::read(__DIR__ . '/../x-snippet.view.php'),
			__DIR__ . '/../x-snippet.view.php',
			false
		);

		$config = new ViewConfig()->addViewComponents(...$components);
		$cache = new ViewCache(option('debug', true), new ViewCachePool(kirby()->roots()->cache() . '/views'));

		$this->renderer = Renderer::make($config, $cache);
	}

	public static function instance(): Plugin {
		static::$instance ??= new Plugin();
		return static::$instance;
	}

	public static function initialize(): Plugin {
		return static::instance();
	}

	public function render(string $name, array $data = []): string {
		return $this->renderer->render($this->view($name, $data));
		try {
		} catch (\Throwable $th) {
			return <<<ERR
			<div style="border:2px solid red; padding:1em; margin:1em 0;">
				<strong>View Rendering Error:</strong>
				<pre>{$th->getMessage()}</pre>
			</div>
			ERR;
		}

	}

	private function view(string $name, array $data = []): GenericView {
		$path = kirby()->roots()->templates() . "/{$name}.view.php";
		return new GenericView($path, $data);
	}

}
