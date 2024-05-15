import postcssPresetEnv from "postcss-preset-env";
import postcssImport from "postcss-import";
import postcssMixins from "postcss-mixins";
import postcssMediaMinmax from "postcss-media-minmax";
import postcssSimpleVars from "postcss-simple-vars";
import tailwindcss from "tailwindcss";
import nesting from "tailwindcss/nesting/index.js";
import { existsSync, readFileSync } from "node:fs";

const colors = {};
const breakpoints = {};
if (existsSync("theme.json")) {
	const theme = JSON.parse(readFileSync("theme.json", "utf8"));
	for (const color of theme.settings.color.palette) {
		colors[`jcore-colors-${color.slug}`] = color.color;
	}
	if ("breakpoints" in theme.settings.custom) {
		for (const breakpoint of Object.keys(theme.settings.custom.breakpoints)) {
			breakpoints[`jcore-breakpoint-${breakpoint}`] = theme.settings.custom.breakpoints[breakpoint];
		}
	}
}

export default {
	map: {
		inline: false,
	},
	plugins: [
		postcssImport,
		postcssMixins,
		postcssMediaMinmax,
		postcssSimpleVars({
			variables: {
				...colors,
				...breakpoints,
			}
		}),
		nesting,
		tailwindcss,
		postcssPresetEnv({ stage: 2, features: { "nesting-rules": false } }),
	],
};
