import * as esbuild from 'esbuild'

const isDev = process.argv.includes('--dev')
const targetPackage = process.argv.find(arg => arg.startsWith('--package='))?.split('=')[1]

async function compile(options) {
  const context = await esbuild.context(options)

  if (isDev) {
    await context.watch()
  } else {
    await context.rebuild()
    await context.dispose()
  }
}

const defaultOptions = {
  define: {
    'process.env.NODE_ENV': isDev ? `'development'` : `'production'`,
  },
  bundle: true,
  mainFields: ['module', 'main'],
  platform: 'neutral',
  sourcemap: isDev ? 'inline' : false,
  sourcesContent: isDev,
  treeShaking: true,
  target: ['es2020'],
  minify: !isDev,
  plugins: [{
    name: 'watchPlugin',
    setup: function (build) {
      build.onStart(() => {
        console.log(`Build started at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`)
      })

      build.onEnd(result => {
        if (result.errors.length > 0) {
          console.log(`Build failed at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`, result.errors)
        } else {
          console.log(`Build finished at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`)
        }
      })
    }
  }],
}

const packages = [
  { name: 'admin', entry: 'shopper.js', outDir: 'dist', outFile: 'shopper.js' },
  { name: 'sidebar', entry: 'index.js', outDir: 'dist', outFile: 'sidebar.js' },
]

const filteredPackages = targetPackage
  ? packages.filter(pkg => pkg.name === targetPackage)
  : packages

filteredPackages.forEach(pkg => {
  compile({
    ...defaultOptions,
    platform: 'browser',
    entryPoints: [`./packages/${pkg.name}/resources/js/${pkg.entry}`],
    outfile: `./packages/${pkg.name}/${pkg.outDir}/${pkg.outFile}`,
  })
})
