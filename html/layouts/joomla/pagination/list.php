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

defined('JPATH_BASE') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

$list = $displayData['list'];

HTMLHelper::_('stylesheet', Uri::root() . 'templates/lightning/css/pagination.css', ['version' => 'auto']);

?>
<nav role="navigation" aria-label="<?php echo Text::_('JLIB_HTML_PAGINATION'); ?>">
	<ul class="pagination">
		<?php echo $list['start']['data']; ?>
		<?php echo $list['previous']['data']; ?>

		<?php foreach ($list['pages'] as $page) : ?>
			<?php echo $page['data']; ?>
		<?php endforeach; ?>

		<?php echo $list['next']['data']; ?>
		<?php echo $list['end']['data']; ?>
	</ul>
</nav>
