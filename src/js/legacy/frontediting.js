/*
 * @package     Lightning
 *
 * @copyright   Copyright (C) 2020-2021 Nicholas K. Dionysopoulos. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * This template is a derivative work of the Lightning template which is
 * Copyright (C) 2020 JoomJunk.
 */

/**
 * JavaScript behavior to add front-end hover edit icons for modules and menu items.
 */
(() => {

  // Module edit buttons
  const modules = document.querySelectorAll('.jmoddiv');
  modules.forEach((module) => {
    module.addEventListener('mouseenter', () => {
      const editButton = document.createElement('a')
      editButton.classList.add('btn', 'jmodedit')
      editButton.setAttribute('href', module.getAttribute('data-jmodediturl'))
      editButton.setAttribute('target', module.getAttribute('data-target'))
      editButton.setAttribute('title', module.getAttribute('data-jmodtip'))

      const icon = document.createElement('span')
      icon.classList.add('fas', 'fa-edit')

      // Append icon
      editButton.appendChild(icon)

      // Append link
      module.insertBefore(editButton, module.firstChild)
    })

    module.addEventListener('mouseleave', ({ target }) => {
      if (target.querySelector('.jmodedit') !== null) {
        target.querySelector('.jmodedit').remove()
      }
    })
  })

  // Menu edit buttons
  const menuItems = document.querySelectorAll('.jmoddiv .nav-item')
  menuItems.forEach((item) => {
    let parent

    item.addEventListener('mouseenter', () => {
      parent = item.closest('.jmoddiv')
      if (parent.getAttribute('data-jmenuedittip') !== null) {
        const link = item.querySelector('a')
        const id = /\bitem-(\d+)\b/.exec(item.getAttribute('class'));
        const url = parent.getAttribute('data-jmodediturl').replace(/\/index.php\?option=com_config&view=modules([^\d]+).+$/, `/administrator/index.php?option=com_menus&view=item&layout=edit$1${id[1]}`)

        const editButton = document.createElement('a')
        editButton.classList.add('btn', 'jfedit-menu')
        editButton.setAttribute('href', url)
        editButton.setAttribute('target', '_blank')
        editButton.setAttribute('title', parent.getAttribute('data-jmenuedittip').replace('%s', id[1]))

        const icon = document.createElement('span')
        icon.classList.add('fas', 'fa-edit')

        // Append icon
        editButton.appendChild(icon)

        // Append link
        item.insertBefore(editButton, item.firstChild)
      }
    })

    item.addEventListener('mouseleave', () => {
      if (parent && parent.getAttribute('data-jmenuedittip') !== null) {
        parent.querySelector('.jfedit-menu').remove()
      }
    })
  })
})();
