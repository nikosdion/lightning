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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;

$app->getDocument()->getWebAssetManager()
	->useScript('core')
	->useScript('keepalive')
	->useScript('field.passwordview');

Text::script('JSHOWPASSWORD');
Text::script('JHIDEPASSWORD');
?>
<form id="login-form-<?= $module->id; ?>" class="mod-login" action="<?= Route::_('index.php', true); ?>" method="post">

	<?php if ($params->get('pretext')) : ?>
		<div class="mod-login__pretext pretext">
			<p><?= $params->get('pretext'); ?></p>
		</div>
	<?php endif; ?>

	<div class="mod-login__userdata userdata">
		<div class="mod-login__username form-group">
			<?php if (!$params->get('usetext', 0)) : ?>
				<div class="input-group">
					<input id="modlgn-username-<?= $module->id; ?>" type="text" name="username" class="form-control" autocomplete="username" placeholder="<?= Text::_('MOD_LOGIN_VALUE_USERNAME'); ?>">
					<span class="input-group-append">
						<label for="modlgn-username-<?= $module->id; ?>" class="sr-only"><?= Text::_('MOD_LOGIN_VALUE_USERNAME'); ?></label>
						<span class="input-group-text" title="<?= Text::_('MOD_LOGIN_VALUE_USERNAME'); ?>">
							<span class="icon-user icon-fw" aria-hidden="true"></span>
						</span>
					</span>
				</div>
			<?php else : ?>
				<label for="modlgn-username-<?= $module->id; ?>"><?= Text::_('MOD_LOGIN_VALUE_USERNAME'); ?></label>
				<input id="modlgn-username-<?= $module->id; ?>" type="text" name="username" class="form-control" autocomplete="username" placeholder="<?= Text::_('MOD_LOGIN_VALUE_USERNAME'); ?>">
			<?php endif; ?>
		</div>

		<div class="mod-login__password form-group">
			<?php if (!$params->get('usetext', 0)) : ?>
				<div class="input-group">
					<input id="modlgn-passwd-<?= $module->id; ?>" type="password" name="password" autocomplete="current-password" class="form-control" placeholder="<?= Text::_('JGLOBAL_PASSWORD'); ?>">
					<span class="input-group-append">
						<label for="modlgn-passwd-<?= $module->id; ?>" class="sr-only"><?= Text::_('JGLOBAL_PASSWORD'); ?></label>
						<button type="button" class="btn btn-secondary input-password-toggle">
							<span class="icon-eye icon-fw" aria-hidden="true"></span>
							<span class="sr-only"><?= Text::_('JSHOWPASSWORD'); ?></span>
						</button>
					</span>
				</div>
			<?php else : ?>
				<label for="modlgn-passwd-<?= $module->id; ?>"><?= Text::_('JGLOBAL_PASSWORD'); ?></label>
				<input id="modlgn-passwd-<?= $module->id; ?>" type="password" name="password" autocomplete="current-password" class="form-control" placeholder="<?= Text::_('JGLOBAL_PASSWORD'); ?>">
			<?php endif; ?>
		</div>

		<?php if (count($twofactormethods) > 1) : ?>
			<div class="mod-login__twofactor form-group">
				<?php if (!$params->get('usetext', 0)) : ?>
					<div class="input-group">
						<span class="input-group-prepend" title="<?= Text::_('JGLOBAL_SECRETKEY'); ?>">
							<span class="input-group-text">
								<span class="icon-star" aria-hidden="true"></span>
							</span>
							<label for="modlgn-secretkey-<?= $module->id; ?>" class="sr-only"><?= Text::_('JGLOBAL_SECRETKEY'); ?></label>
						</span>
						<input id="modlgn-secretkey-<?= $module->id; ?>" autocomplete="one-time-code" type="text" name="secretkey" class="form-control" placeholder="<?= Text::_('JGLOBAL_SECRETKEY'); ?>">
						<span class="input-group-append" title="<?= Text::_('JGLOBAL_SECRETKEY_HELP'); ?>">
							<span class="input-group-text">
								<span class="icon-question icon-fw" aria-hidden="true"></span>
							</span>
						</span>
					</div>
				<?php else : ?>
					<label for="modlgn-secretkey-<?= $module->id; ?>"><?= Text::_('JGLOBAL_SECRETKEY'); ?></label>
					<div class="input-group">
						<input id="modlgn-secretkey-<?= $module->id; ?>" autocomplete="one-time-code" type="text" name="secretkey" class="form-control" placeholder="<?= Text::_('JGLOBAL_SECRETKEY'); ?>">
						<span class="input-group-append" title="<?= Text::_('JGLOBAL_SECRETKEY_HELP'); ?>">
							<span class="input-group-text">
								<span class="icon-question icon-fw" aria-hidden="true"></span>
							</span>
						</span>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if (PluginHelper::isEnabled('system', 'remember')) : ?>
			<div class="mod-login__remember form-group">
				<div id="form-login-remember-<?= $module->id; ?>" class="form-check">
					<label class="form-check-label">
						<input type="checkbox" name="remember" class="form-check-input" value="yes">
						<?= Text::_('MOD_LOGIN_REMEMBER_ME'); ?>
					</label>
				</div>
			</div>
		<?php endif; ?>

		<div class="mod-login__submit form-group">
			<button type="submit" name="Submit" class="btn btn-primary akeeba-sociallogin-link-button-j4 akeeba-sociallogin-link-button-email akeeba-sociallogin-link-button-email">
				<span class="icon-user icon-social-user"></span>
				<?= Text::_('JLOGIN'); ?>
			</button>
		</div>

		<?php foreach($extraButtons as $button):
			$dataAttributeKeys = array_filter(array_keys($button), function ($key) {
				return substr($key, 0, 5) == 'data-';
			});
			?>
			<div class="mod-login__submit form-group">
				<button type="button"
						class="btn btn-secondary btn-block mt-4 <?= $button['class'] ?? '' ?>"
						<?php foreach ($dataAttributeKeys as $key): ?>
						<?= $key ?>="<?= $button[$key] ?>"
						<?php endforeach; ?>
						<?php if ($button['onclick']): ?>
						onclick="<?= $button['onclick'] ?>"
						<?php endif; ?>
						title="<?= Text::_($button['label']) ?>"
						id="<?= $button['id'] ?>"
						>
					<?php if (!empty($button['icon'])): ?>
						<span class="<?= $button['icon'] ?>"></span>
					<?php elseif (!empty($button['image'])): ?>
						<?= HTMLHelper::_('image', $button['image'], Text::_($button['tooltip'] ?? ''), [
							'class' => 'icon',
						], true) ?>
					<?php elseif (!empty($button['svg'])): ?>
						<?= $button['svg']; ?>
					<?php endif; ?>
					<?= Text::_($button['label']) ?>
				</button>
			</div>
		<?php endforeach; ?>

		<?php
			$usersConfig = ComponentHelper::getParams('com_users'); ?>
			<ul class="mod-login__options list-unstyled">
				<li>
					<a href="<?= Route::_('index.php?option=com_users&view=reset'); ?>">
					<?= Text::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
				</li>
				<li>
					<a href="<?= Route::_('index.php?option=com_users&view=remind'); ?>">
					<?= Text::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
				</li>
				<?php if ($usersConfig->get('allowUserRegistration')) : ?>
				<li>
					<a href="<?= Route::_($registerLink); ?>">
					<?= Text::_('MOD_LOGIN_REGISTER'); ?> <span class="icon-arrow-alt-circle-right"></span></a>
				</li>
				<?php endif; ?>
			</ul>
		<input type="hidden" name="option" value="com_users">
		<input type="hidden" name="task" value="user.login">
		<input type="hidden" name="return" value="<?= $return; ?>">
		<?= HTMLHelper::_('form.token'); ?>
	</div>
	<?php if ($params->get('posttext')) : ?>
		<div class="mod-login__posttext posttext">
			<p><?= $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
</form>
