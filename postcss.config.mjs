import postcssPresetEnv from "postcss-preset-env";
import postcssImport from "postcss-import";
import postcssMixins from "postcss-mixins";
import postcssMinify from "postcss-minify";
import tailwindcss from "tailwindcss";
import nesting from "tailwindcss/nesting/index.js";

export default {
	map: {
		inline: false,
	},
	plugins: [
		postcssImport,
		postcssMixins,
		nesting,
		tailwindcss,
		postcssPresetEnv({ stage: 2, features: { "nesting-rules": false } }),
		//postcssMinify,
	],
};
