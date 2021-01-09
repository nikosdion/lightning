<?php
/*
 * @package     Lightning
 *
 * @copyright   Copyright (C) 2020-2021 Nicholas K. Dionysopoulos. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * This template is a derivative work of the Lightning template which is
 * Copyright (C) 2020 JoomJunk.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\Template\Lightning\ImageResizer;

include_once __DIR__ . '/helper/ImageResizer.php';
include_once __DIR__ . '/helper/metas.php';
include_once __DIR__ . '/helper/styles.php';
include_once __DIR__ . '/helper/scripts.php';

/** @var JDocumentHtml $this */

$app       = Factory::getApplication();
$sitename  = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$pageclass = $app->getMenu()->getActive()->getParams()->get('pageclass_sfx');

// Get the template options
$themeSwitcher = (boolean) $this->params->get('theme-switcher', 1);
$inlineCSS     = (boolean) $this->params->get('inline-css', 1);
$deferCss      = (boolean) $this->params->get('defer-css', 1);
$deferJs       = (boolean) $this->params->get('defer-js', 1);

// Output as HTML5
$this->setHtml5(true);

// Show the theme switcher?
if ($themeSwitcher)
{
	HTMLHelper::_('stylesheet', 'switch.css', ['version' => 'auto', 'relative' => true]);
}

// Dark Mode switcher JavaScript
HTMLHelper::_('script', 'switch.min.js', ['version' => 'auto', 'relative' => true], ['type' => 'module']);

// Font Awesome
HTMLHelper::_('stylesheet', 'media/vendor/fontawesome-free/css/fontawesome.min.css', ['version' => 'auto']);

/**
 * Inline the template CSS?
 *
 * Strangely, doing so seems to _increase_ the LCP (Largest Contentful Paint) on fast connections and has a negligible
 * impact on slower connections. Furthermore, it increases the nodes size count which has a negative impact on the
 * layout engine. Finally, it increases the page weight on repeated access.
 */
if ($inlineCSS)
{
	$css = file_get_contents(__DIR__ . '/css/template.css');
}
else
{
	HTMLHelper::_('stylesheet', sprintf('templates/%s/css/template.css', basename(__DIR__)), ['version' => 'auto']);

	// Tell the browser to start preloading the template CSS before it's done parsing the DOM
	$this->addHeadLink(sprintf('%stemplates/%s/css/template.css', Uri::root(true), basename(__DIR__)), 'preload', 'rel', ['as'          => 'style',
	                                                                                                                      'crossorigin' => 'anonymous',
	]);
}

// Logo file or site title param
$logoFile = $this->params->get('logoFile');

if ($logoFile)
{
	$width  = (int) $this->params->get('logoWidth', 0);
	$height = (int) $this->params->get('logoHeight', 0);

	if (($width <= 0) || ($height <= 0))
	{
		$resizer = ImageResizer::getInstance();

		try
		{
			[$width, $height] = $resizer->getImageSize($logoFile);
		}
		catch (Exception $e)
		{
			$width  = 50;
			$height = 50;
		}
	}

	$logo = sprintf('<img src="%s%s" alt="%s" width="%s" height="%s">', Uri::root(), htmlspecialchars($logoFile, ENT_QUOTES), $sitename, $width, $height);
}
elseif ($this->params->get('siteTitle'))
{
	$logo = '<span title="' . $sitename . '">' . htmlspecialchars($this->params->get('siteTitle'), ENT_COMPAT, 'UTF-8') . '</span>';
}
else
{
	$logo = '<svg clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 1080 1081" height="60" xmlns="http://www.w3.org/2000/svg"><path d="m0 .67h1080v1080h-1080z" fill="none"/><g fill-rule="nonzero"><path d="m244.4 258.4h583.2c17.774 0 32.4 14.626 32.4 32.4v518.4c0 17.774-14.626 32.4-32.4 32.4h-583.2c-17.774 0-32.4-14.626-32.4-32.4v-518.4c0-17.774 14.626-32.4 32.4-32.4z" fill="#d43431" transform="matrix(1.23457 0 0 1.23457 -121.72352 -138.3427)"/><path d="m.259-.667h.128l.279.667h-.686zm.111.491-.05-.119-.05.119zm.165.096-.212-.507-.212.507zm-.216-.441.176.421h-.352z" fill="#f2f2f3" transform="matrix(689.01598614 0 0 585.12938978 317.4500661 735.81063791)"/></g></svg>';
}

$hasSidebar = '';

if ($this->countModules('sidebar-left'))
{
	$hasSidebar .= ' has-sidebar-left';
}

if ($this->countModules('sidebar-right'))
{
	$hasSidebar .= ' has-sidebar-right';
}

$faviconPath = 'templates/' . $this->template . '/favicon';

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');
$this->setMetaData('theme-color', '#415058');

// Favicons
$this->setMetaData('msapplication-config', $faviconPath . '/browserconfig.xml');
$this->setMetaData('msapplication-TileColor', '#415058');

$this->addHeadLink($faviconPath . '/apple-touch-icon.png', 'apple-touch-icon', 'rel', ['sizes' => '180x180']);
$this->addHeadLink($faviconPath . '/favicon-32x32.png', 'icon', 'rel', ['sizes' => '32x32']);
$this->addHeadLink($faviconPath . '/favicon-16x16.png', 'icon', 'rel', ['sizes' => '16x16']);
$this->addHeadLink($faviconPath . '/site.webmanifest.json', 'manifest');
$this->addHeadLink($faviconPath . '/safari-pinned-tab.svg', 'mask-icon', 'rel', ['color' => '#1F292E']);
$this->addHeadLink($faviconPath . '/favicon.ico', 'shortcut icon');

// DNS pre-fetching. It accelerates loading of external resources on medium to high latency connection
// -- ReCAPTCHA
$this->addHeadLink("https://www.google.com", 'dns-prefetch');
$this->addHeadLink("https://www.gstatic.com", 'dns-prefetch');
// -- Our CDN (downloads, static resources)
$this->addHeadLink("https://cdn.dionysopoulos.me", 'dns-prefetch');

// Asset preloading
$this->addHeadLink(sprintf('%smedia/vendor/fontawesome-free/webfonts/fa-solid-900.woff2', Uri::root(true)), 'preload', 'rel', ['as'          => 'font',
                                                                                                                               'crossorigin' => 'anonymous',
]);

// Get Joomla's buffer
$menu         = $this->getBuffer('modules', 'menu', ['style' => 'none']);
$search       = $this->getBuffer('modules', 'search', ['style' => 'none']);
$banner       = $this->getBuffer('modules', 'banner', ['style' => 'default']);
$topA         = $this->getBuffer('modules', 'top-a', ['style' => 'default']);
$topB         = $this->getBuffer('modules', 'top-b', ['style' => 'default']);
$sidebarLeft  = $this->getBuffer('modules', 'sidebar-left', ['style' => 'default']);
$mainTop      = $this->getBuffer('modules', 'main-top', ['style' => 'default']);
$message      = $this->getBuffer('message');
$breadcrumbs  = $this->getBuffer('modules', 'breadcrumbs', ['style' => 'none']);
$component    = $this->getBuffer('component');
$mainBottom   = $this->getBuffer('modules', 'main-bottom', ['style' => 'default']);
$sidebarRight = $this->getBuffer('modules', 'sidebar-right', ['style' => 'default']);
$bottomA      = $this->getBuffer('modules', 'bottom-a', ['style' => 'default']);
$bottomB      = $this->getBuffer('modules', 'bottom-b', ['style' => 'default']);
$footer       = $this->getBuffer('modules', 'footer', ['style' => 'none']);
$debug        = $this->getBuffer('modules', 'debug', ['style' => 'none']);
$metas        = $this->getBuffer('metas');
$styles       = $this->getBuffer('styles');
$scripts      = $this->getBuffer('scripts');

/**
 * Load CSS at the bottom of the page?
 *
 * Causes an increased CLS (Cumulative Layout Shift) as the browser needs to re-evaluate the layout after having already
 * done the work before the DOM is fully loaded and the JS injects the new stylesheets into the DOM.
 */
if ($deferCss)
{
	$cachedStyleSheets = json_encode(array_values($this->getBuffer('styles')));
}

?>
<!DOCTYPE html>
<html lang="<?= $this->language ?>" dir="<?= $this->direction ?>" prefix="og: http://ogp.me/ns#">
<head>
	<?= $metas ?>
	<?php if ($inlineCSS): ?>
		<style><?= $css ?></style>
	<?php endif ?>
	<?php if (!$deferCss): ?>
		<?= implode("\n", $this->getBuffer('styles')) ?>
	<?php endif ?>
	<?php if (!$deferJs): ?>
		<?= $scripts ?>
	<?php endif; ?>
</head>
<body class="site-grid site <?= $pageclass . $hasSidebar ?>">
<header class="grid-child container-header full-width header <?= $this->countModules('banner') ? 'has-banner' : '' ?>">
	<nav class="navbar">
		<div class="navbar-brand">
			<a href="<?= $this->baseurl ?>/">
				<?= $logo ?>
				<span class="sr-only"><?= Text::_('TPL_LIGHTNING_LOGO_LABEL') ?></span> </a>
			<?php if ($this->params->get('siteDescription')) : ?>
				<div><?= htmlspecialchars($this->params->get('siteDescription')) ?></div>
			<?php endif ?>
		</div>

		<?php if ($this->countModules('menu') || $this->countModules('search')) : ?>
			<div class="navbar-menu">
				<?= $this->getBuffer('modules', 'menu', $attribs = ['style' => 'none']) ?>
				<?php if ($this->countModules('search')) : ?>
					<div>
						<?= $search ?>
					</div>
				<?php endif ?>
			</div>
			<span id="navbar-menu-toggle" class="navbar-menu-toggle"><span></span></span>
		<?php endif ?>
		<?php if ($themeSwitcher) : ?>
			<div class="color-scheme-switch" id="color-scheme-switch">
				<input type="radio" name="color-scheme-switch" value="is-light" class="color-scheme-switch-radio"
					   aria-label="Light color scheme"> <input type="radio" name="color-scheme-switch" value="is-system"
															   class="color-scheme-switch-radio"
															   aria-label="System color scheme"> <input type="radio"
																										name="color-scheme-switch"
																										value="is-dark"
																										class="color-scheme-switch-radio"
																										aria-label="Dark color scheme">
				<label class="color-scheme-switch-label" for="color-scheme-switch"></label>
			</div>
		<?php endif ?>
	</nav>
</header>

<?php if ($this->countModules('banner')) : ?>
	<div class="grid-child full-width container-banner">
		<?= $banner ?>
	</div>
<?php endif ?>

<?php if ($this->countModules('top-a')) : ?>
	<div class="grid-child container-top-a">
		<?= $topA ?>
	</div>
<?php endif ?>

<?php if ($this->countModules('top-b')) : ?>
	<div class="grid-child container-top-b">
		<?= $topB ?>
	</div>
<?php endif ?>

<?php if ($this->countModules('sidebar-left')) : ?>
	<div class="grid-child container-sidebar-left">
		<?= $sidebarLeft ?>
	</div>
<?php endif ?>

<div class="grid-child container-component">
	<?= $mainTop ?>
	<?= $message ?>
	<?= $breadcrumbs ?>
	<?= $component ?>
	<?= $mainBottom ?>
</div>

<?php if ($this->countModules('sidebar-right')) : ?>
	<div class="grid-child container-sidebar-right">
		<?= $sidebarRight ?>
	</div>
<?php endif ?>

<?php if ($this->countModules('bottom-a')) : ?>
	<div class="grid-child container-bottom-a">
		<?= $bottomA ?>
	</div>
<?php endif ?>

<?php if ($this->countModules('bottom-b')) : ?>
	<div class="grid-child container-bottom-b">
		<?= $bottomB ?>
	</div>
<?php endif ?>

<?php if ($this->countModules('footer')) : ?>
	<footer class="grid-child container-footer full-width footer">
		<div class="container">
			<?= $footer ?>
		</div>
		<div class="container copyright">
			<p>
				Copyright &copy;2007-<?= date('Y') ?> Nikolaos Dionysopoulos. All legal rights reserved.
			</p>
		</div>
	</footer>
<?php endif ?>

<?= $debug ?>

<?php if ($deferCss): ?>
	<script>
        (() =>
        {
            const styles = <?= $cachedStyleSheets ?>;
            styles.forEach(item =>
            {
                document.body.insertAdjacentHTML("beforeend", item);
            })
        })()
	</script>
<?php endif ?>

<?php if ($deferJs): ?>
	<?= $scripts ?>
<?php endif; ?>
</body>
</html>
