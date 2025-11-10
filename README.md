# Tempest/View for Kirby

This package enables [`Tempest/View`](https://tempestphp.com/2.x/essentials/views) for your own Kirby applications.

> [!NOTE]
> This is a very first, beta version of the plugin; I wanted to use Tempest View with Kirby, but haven't yet had time to add tests, etc. Continue on your own risk.

## Installation

### Installation with composer

```ssh
composer require adamkiss/kirby-view
```

## What is Tempest/View

According to [Tempest internals → View specifications](https://tempestphp.com/2.x/internals/view-spec):

> Tempest View is a server-side templating engine powered by PHP. Most of its syntax is inspired by Vue.js. Tempest View aims to stay as close as possible to HTML, using PHP where needed. All syntax builds on top of HTML and PHP so that developers don't need to learn any new syntax.

## Usage

After installing the plugin, any template ending in `.view.php` will be automatically processed by the plugin; All the snippets ending in `.view.php` will be also available as tempest `x-` components, i.e. `site/snippets/layout.php` will be available as `x-layout`.

You can also use pure PHP snippets with View, just use the included component `x-snippet`:

```
<x-snippet name="my-snippet" argument="static" :another="'dynamic'"></x-snippet>
```

All the documentation about Tempest/View is in the [official documentation](https://tempestphp.com/2.x/essentials/views).

### Views

All the generated (cached) views are stored in `{cache location}}/views` directory.

## Credits

- [Tempest PHP](https://tempestphp.com)
- [Lukas Leitsch](https://github.com/lukasleitsch) for [Kirby Blade](https://github.com/lukasleitsch/kirby-blade#readme)

## Copyright & License

2025 Adam Kiss

Licensed under MIT license
