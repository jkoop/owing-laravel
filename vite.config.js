import { defineConfig } from "vite"
import laravel from "laravel-vite-plugin"

export default defineConfig({
	plugins: [
		laravel({
			input: [
"resources/js/successes.js",
"resources/js/bootstrap.js",
"resources/js/app.js",
"resources/js/datetime-relative.js",
"resources/js/datetime-absolute.js",
"resources/css/successes.css",
"resources/css/change-history.css",
"resources/css/app.css",
"resources/css/errors.css",
"resources/css/input.css",
            ],
			refresh: true,
		}),
	],
})
