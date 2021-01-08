# Lightning for Dionysopoulos.me

Joomla 4 template using HiQ CSS for Dionysopoulos.me

Based on the [Lightning](https://github.com/C-Lodder/lightning/releases) template by Charlie Lodder/

## Browser Support

![Chrome](https://raw.github.com/alrra/browser-logos/master/src/chrome/chrome_48x48.png) | ![Firefox](https://raw.github.com/alrra/browser-logos/master/src/firefox/firefox_48x48.png) | ![Edge](https://raw.github.com/alrra/browser-logos/master/src/edge/edge_48x48.png) | ![IE](https://raw.github.com/alrra/browser-logos/master/src/archive/internet-explorer_9-11/internet-explorer_9-11_48x48.png) | ![Safari](https://raw.github.com/alrra/browser-logos/master/src/safari/safari_48x48.png) | ![Opera](https://raw.github.com/alrra/browser-logos/master/src/opera/opera_48x48.png)
--- | --- | --- | --- | --- | --- |
Latest :heavy_check_mark: | Latest :heavy_check_mark: | Latest :heavy_check_mark: | 11+ :x: | Latest :heavy_check_mark: | Latest :heavy_check_mark: |

## Build tasks
- Install dependencies:
```bash
npm ci
```

- Compile CSS:
```bash
npm run css
```

- Copy &amp; minify Javascript:
```bash
npm run js
```

- Create a Zip file:
```bash
npm run package
```

- Lint CSS:
```bash
npm run lint
```

- Build project:
This will process all Javascript, CSS and also create a zip file
```bash
npm run build
```
