import { readFileSync, existsSync } from "node:fs";

const colors = {};
if (existsSync("theme.json")) {
	const theme = JSON.parse(readFileSync("theme.json", "utf8"));
	for (const color of theme.settings.color.palette) {
		colors[color.slug] = color.color;
	}
}

/** @type {import('tailwindcss').Config} */
export default {
	content: ["./views/**/*.twig", "./src/js/**/*.js"],
	theme: {
		extend: {},
		colors,
	},
	plugins: [],
};
