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

/** @var JDocumentError $this */

$app           = Factory::getApplication();
$sitename      = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu          = $app->getMenu()->getActive();
$pageclass     = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';
$themeSwitcher = (boolean)$this->params->get('theme-switcher', 1);

/** @var Throwable $incomingException */
$incomingException = $this->_error ?? new RuntimeException('Unspecified error');

// Template params
if ($themeSwitcher)
{
	HTMLHelper::_('stylesheet', 'switch.css', ['version' => 'auto', 'relative' => true]);
}
HTMLHelper::_('script', 'switch.min.js', ['version' => 'auto', 'relative' => true], ['type' => 'module']);

/**
 * Uncomment to inline the template CSS.
 *
 * Strangely, doing so seems to _increase_ the LCP (Largest Contentful Paint) on fast connections and has a negligible
 * impact on slower connections. Furthermore, it increases the nodes size count which has a negative impact on the
 * layout engine. Finally, it increases the page weight on repeated access.
 */
//$css = file_get_contents(__DIR__ . '/css/template.css');

// Fallback to good, old-fashioned loading of the CSS.
if (!isset($css))
{
	HTMLHelper::_('stylesheet', sprintf('templates/%s/css/template.css', basename(__DIR__)), ['version' => 'auto']);
}

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

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');
?>
<!DOCTYPE html>
<!--suppress XmlUnboundNsPrefix -->
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<?php if (isset($css)): ?>
		<style><?php echo $css; ?></style>
	<?php endif; ?>
	<jdoc:include type="styles" />
</head>

<body class="site-grid site <?php echo $pageclass; ?>">
	<header class="grid-child container-header full-width header">
		<nav class="navbar">
			<div class="navbar-brand">
				<a href="<?php echo $this->baseurl; ?>/">
					<?php echo $logo; ?>
				</a>
				<?php if ($this->params->get('siteDescription')) : ?>
					<div><?php echo htmlspecialchars($this->params->get('siteDescription')); ?></div>
				<?php endif; ?>
			</div>
			<?php if ($themeSwitcher) : ?>
				<div class="color-scheme-switch" id="color-scheme-switch">
					<input type="radio" name="color-scheme-switch" value="is-light" class="color-scheme-switch-radio" aria-label="Light color scheme">
					<input type="radio" name="color-scheme-switch" value="is-system" class="color-scheme-switch-radio" aria-label="System color scheme">
					<input type="radio" name="color-scheme-switch" value="is-dark" class="color-scheme-switch-radio" aria-label="Dark color scheme">
					<label class="color-scheme-switch-label" for="color-scheme-switch"></label>
				</div>
			<?php endif; ?>
		</nav>
	</header>

	<div class="grid-child container-component">
		<h1><?php echo Text::_('JERROR_LAYOUT_PAGE_NOT_FOUND'); ?></h1>
		<div class="card">
			<jdoc:include type="message" />
			<p><strong><?php echo Text::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></strong></p>
			<p><?php echo Text::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></p>
			<ul>
				<li><?php echo Text::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
				<li><?php echo Text::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
				<li><?php echo Text::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
				<li><?php echo Text::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
			</ul>
			<p><?php echo Text::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?></p>
			<p><a href="<?php echo $this->baseurl; ?>/index.php"><?php echo Text::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></p>
			<hr>
			<p><?php echo Text::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>
			<blockquote>
				<span class="badge badge-primary"><?php echo $this->error->getCode(); ?></span> <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
			</blockquote>
			<?php if ($this->debug) : ?>
				<div>
					<?php echo $this->renderBacktrace(); ?>
					<?php // Check if there are more Exceptions and render their data as well ?>
					<?php if ($this->error->getPrevious()) : ?>
						<?php $loop = true; ?>
						<?php // Reference $this->_error here and in the loop as setError() assigns errors to this property and we need this for the backtrace to work correctly ?>
						<?php // Make the first assignment to setError() outside the loop so the loop does not skip Exceptions ?>
						<?php $this->setError($incomingException->getPrevious()); ?>
						<?php while ($loop === true) : ?>
							<p><strong><?php echo Text::_('JERROR_LAYOUT_PREVIOUS_ERROR'); ?></strong></p>
							<p><?php echo htmlspecialchars($incomingException->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
							<?php echo $this->renderBacktrace(); ?>
							<?php $loop = $this->setError($incomingException->getPrevious()); ?>
						<?php endwhile; ?>
						<?php // Reset the main error object to the base error ?>
						<?php $this->setError($this->error); ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ($this->countModules('footer')) : ?>
	<footer class="grid-child container-footer full-width footer">
		<div class="container">
			<jdoc:include type="modules" name="footer" style="none" />
		</div>
	</footer>
	<?php endif; ?>

	<jdoc:include type="modules" name="debug" style="none" />

	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
</body>
</html>
