/*
 * @package     Lightning
 *
 * @copyright   Copyright (C) 2020-2021 Nicholas K. Dionysopoulos. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * This template is a derivative work of the Lightning template which is
 * Copyright (C) 2020 JoomJunk.
 */

const {Worker, isMainThread, parentPort}  = require("worker_threads")
const {readFile, mkdir, writeFile, rmdir} = require("fs")
const postcss                             = require("postcss")
const {compressFile}                      = require("./gzip-assets.es6");

const plugins = [
    require("postcss-easy-import"),
    require("postcss-mixins"),
    require("postcss-custom-selectors"),
    require("postcss-nested"),
    require("autoprefixer"),
    require("postcss-custom-media"),
    require("postcss-discard-comments"),
    require("postcss-simple-vars"),
    require("postcss-conditionals"),
    require("cssnano")({
        preset: "default",
    })
]

const files = {
    "src/css/hiq.css":                                            "css/template.css",
    "src/css/switch.css":                                         "css/switch.css",
    "src/css/media/vendor/joomla-custom-elements/joomla-tab.css": "css/vendor/joomla-custom-elements/joomla-tab.css",
    "src/css/media/layouts/modal/modal.css":                      "css/modal.css",
    "src/css/media/vendor/choicesjs/choices.css":                 "css/vendor/choicesjs/choices.min.css",
    "src/css/media/system/frontediting.css":                      "css/system/frontediting.css",
    "src/css/media/system/fields/calendar.css":                   "css/system/fields/calendar.min.css",
    "src/css/media/system/fields/joomla-field-media.css":         "css/system/fields/joomla-field-media.min.css",
    "src/css/media/mod_menu/menu.css":                            "css/mod_menu/menu.min.css",
    "src/css/media/layouts/pagination/pagination.css":            "css/pagination.css",
    "src/css/mediamanager/mediamanager.css":                      "css/com_media/mediamanager.min.css",
}

const ProcessCss = (file, dest) =>
{
    const dir = dest.substring(0, dest.lastIndexOf("/"))
    readFile(file, (err, css) =>
    {
        postcss(plugins)
            .process(css, {from: file, to: dest})
            .then((result) =>
            {
                mkdir(`${__dirname}/${dir}`, {recursive: true}, (err) =>
                {
                    if (err)
                    {
                        throw err
                    }

                    writeFile(dest, result.css, {flag: "wx"}, () => {
                        compressFile(dest, true);

                        return true;
                    })

                });
            })
    })
}

if (isMainThread)
{
    // Delete the CSS directory from the main thread
    rmdir(`${__dirname}/css`, {recursive: true}, (err) =>
    {
        if (err)
        {
            throw err
        }
    })

    const worker = new Worker(__filename)
    worker.postMessage("message")
}
else
{
    parentPort.once("message", () =>
    {
        Object.entries(files).forEach(([key, val]) =>
        {
            ProcessCss(key, val)
        })
    })
}
