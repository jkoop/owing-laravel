import { defineConfig } from "vite"
import laravel from "laravel-vite-plugin"

export default defineConfig({
	plugins: [
		laravel({
			input: [
				"resources/css/app.css",
				"resources/css/change-history.css",
				"resources/css/errors.css",
				"resources/css/input.css",
				"resources/css/successes.css",
				"resources/js/app.js",
				"resources/js/bootstrap.js",
				"resources/js/dashboard.js",
				"resources/js/datetime-absolute.js",
				"resources/js/datetime-relative.js",
				"resources/js/successes.js",
			],
			refresh: true,
		}),
	],
})
