<?php

namespace AdamKiss\View;

use Kirby\Cms\App;
use Kirby\Filesystem\F;
use Kirby\Template\Template as KirbyTemplate;
use Kirby\Toolkit\Tpl;

class Template extends KirbyTemplate {
	public const VIEW_EXTENSION = 'view.php';

	protected ?string $extension = null;

	private string $templatesPath;

	public function __construct(
		private App $kirby,
		string $name,
		?string $contentType = null,
		?string $defaultType = 'html'
	) {
		parent::__construct($name, $contentType, $defaultType);
		$this->templatesPath = $this->kirby->roots()->templates();
	}

	public function isViewTemplate(): bool {
		return $this->extension() === self::VIEW_EXTENSION;
	}

	public function render(array $data = []): string {
		if (!$this->isViewTemplate()) {
			return Tpl::load($this->file(), $data);
		}

		$vp = Plugin::instance();
		return $vp->render($this->name(), $data);
	}

	public function file(): ?string {
		return $this->getFilename($this->name());
	}

	public function extension(): string {
		if (! is_null($this->extension)) {
			// return from cache
			return $this->extension;
		}

		$filename = $this->file();

		return $this->extension = str_ends_with($filename, self::VIEW_EXTENSION) && file_exists($filename)
			? static::VIEW_EXTENSION
			: 'php';
	}

	public function getFilename(string $name): ?string {
		try {
			// Try the default blade template in the default template directory.
			return F::realpath("{$this->templatesPath}/{$name}.view.php", $this->templatesPath);
		} catch (\Exception) {
			// ignore errors, continue searching
		}

		try {
			// Try the default vanilla php template in the default template directory.
			return F::realpath("{$this->templatesPath}/{$name}.php", $this->templatesPath);
		} catch (\Exception) {
			// ignore errors, continue searching
		}

		// Look for the template with type extension provided by an extension.
		// This might be null if the template does not exist.
		return App::instance()->extension($this->store(), $name);
	}
}
