/*
 * @package     Lightning
 *
 * @copyright   Copyright (C) 2020-2021 Nicholas K. Dionysopoulos. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * This template is a derivative work of the Lightning template which is
 * Copyright (C) 2020 JoomJunk.
 */

(() => {

  const toggle = document.getElementById('navbar-menu-toggle')
  if (toggle) {
    toggle.addEventListener('click', () => {
      const toggle = document.getElementById('navbar-menu-toggle')
      document.body.classList.toggle('menu-open');
    })
  }

})()