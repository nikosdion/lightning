/*
 * @package     Lightning
 *
 * @copyright   Copyright (C) 2020-2021 Nicholas K. Dionysopoulos. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * This template is a derivative work of the Lightning template which is
 * Copyright (C) 2020 JoomJunk.
 */

const { createWriteStream } = require('fs')
const archiver = require('archiver')
const pkg = require('./package.json')

const templateOutput = createWriteStream(`${__dirname}/tpl_lightning-v${pkg.version}.zip`)

const template = archiver('zip', {
  zlib: { level: 9 }
})

// Listen for all archive data to be written
templateOutput.on('close', () => {
  console.log(`Template has been packaged successfully`)
})

// Catch this error explicitly
template.on('error', (err) => {
  throw err
})

// Pipe archive data to the file
template.pipe(templateOutput)

// Append the files and directories
template.file('component.php')
template.file('error.php')
template.file('favicon.ico')
template.file('index.php')
template.file('offline.php')
template.file('template_preview.png')
template.file('template_thumbnail.png')
template.file('templateDetails.xml')
template.directory('css/')
template.directory('favicon/')
template.directory('helper/')
template.directory('html/')
template.directory('js/')
template.directory('language/')

// Finalise the template (ie we are done appending files but streams have to finish yet)
template.finalize()