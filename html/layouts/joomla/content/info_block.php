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

/** @var array $displayData */

$blockPosition = $displayData['params']->get('info_block_position', 0);

?>
	<dl class="article-info muted">

		<?php if ($displayData['position'] === 'above' && ($blockPosition == 0 || $blockPosition == 2)
				|| $displayData['position'] === 'below' && ($blockPosition == 1)
				) : ?>

			<dt class="article-info-term">
				<?php if ($displayData['params']->get('info_block_show_title', 1)) : ?>
					<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
				<?php endif; ?>
			</dt>

			<?php if ($displayData['params']->get('show_author') && !empty($displayData['item']->author )) : ?>
				<?php echo $this->sublayout('author', $displayData); ?>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_parent_category') && !empty($displayData['item']->parent_slug)) : ?>
				<?php echo $this->sublayout('parent_category', $displayData); ?>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_category')) : ?>
				<?php echo $this->sublayout('category', $displayData); ?>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_associations')) : ?>
				<?php echo $this->sublayout('associations', $displayData); ?>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_publish_date')) : ?>
				<?php echo $this->sublayout('publish_date', $displayData); ?>
			<?php endif; ?>

		<?php endif; ?>

		<?php if ($displayData['position'] === 'above' && ($blockPosition == 0)
				|| $displayData['position'] === 'below' && ($blockPosition == 1 || $blockPosition == 2)
				) : ?>
			<?php if ($displayData['params']->get('show_create_date')) : ?>
				<?php echo $this->sublayout('create_date', $displayData); ?>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_modify_date')) : ?>
				<?php echo $this->sublayout('modify_date', $displayData); ?>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_hits')) : ?>
				<?php echo $this->sublayout('hits', $displayData); ?>
			<?php endif; ?>
		<?php endif; ?>
	</dl>

	<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
		<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
			<meta itemprop="url" content="<?= \Joomla\CMS\Uri\Uri::base(false) ?>images/logos/webp/icon-red@4x.webp">
			<meta itemprop="width" content="400">
			<meta itemprop="height" content="400">
		</div>
		<meta itemprop="name" content="Dionysopoulos.me" />
	</div>
