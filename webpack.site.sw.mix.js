const mix = require('laravel-mix')
const fs = require('fs')
const path = require('path')
const chalk = require('chalk')
const stripIndent = require('common-tags').stripIndent

// npm run prod --section=site.sw

let fileSW = 'service-worker.js',
    filePathSW = `public/${fileSW}`

mix.webpackConfig({
    plugins: [{
        apply: (compiler) => {
            compiler.hooks.beforeCompile.tap('BeforeRunPlugin', (params, callback) => {

                let manifestPaths = JSON.parse(fs.readFileSync(path.resolve('./public/mix-manifest.json'), 'utf8'))

                fs.copyFile(`./resources/js/service-worker-vanilla.js`, filePathSW, (err) => {
                    if (err) throw err;

                    console.info('\n\n', chalk.black.bgGreen.italic(`service-worker-vanilla.js was copied to ${fileSW}`), '\n\n');

                    fs.readFile(path.resolve(filePathSW), 'utf8', (err, contentFileSW) => {
                        if (err) return console.log(chalk.white.bgRed.bold(err));

                        let newContentFileSW = contentFileSW.replace(/^DummyUrlsToCache$/gm, stripIndent(String.raw)`
                            const urlsToCache = [
                                '${manifestPaths['/site/js/app.js']}',
                                '${manifestPaths['/site/css/app.css']}',
                            ];
                        `);

                        fs.writeFile(path.resolve(filePathSW), newContentFileSW, 'utf8', (err) => {
                            if (err) throw err;
                        });
                    });
                });

            })
        }
    }]
})

mix.version([`${filePathSW}`]).mergeManifest()

mix.then(webpackStats => {
    console.log(chalk.black.bgGreen.bold(`Webpack build succssesfully done!`, '\n'));
})
