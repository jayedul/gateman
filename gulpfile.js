module.exports=require('react-materials/builders/build-release')({
	vendor: true,
	exclude: [
		'vendor/devjk/wp-toolkit/components',
		'vendor/devjk/wp-toolkit/package.json',
		'vendor/devjk/wp-toolkit/package-lock.json',
		'vendor/devjk/wp-toolkit/.eslintignore',
		'vendor/devjk/wp-toolkit/.eslintrc',
		'vendor/devjk/wp-toolkit/.eslintrc.js',
		'vendor/devjk/wp-toolkit/phpcs.xml',
		'vendor/devjk/wp-toolkit/webpack.config.js',
		'vendor/phpcompatibility',
		'vendor/squizlabs',
		'vendor/sirbrillig',
		'vendor/wp-coding-standards',
		'vendor/10up',
		'vendor/automattic'
	],
	delete_build_dir: true
});
