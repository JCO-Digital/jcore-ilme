import postcssPresetEnv from "postcss-preset-env";
import postcssImport from "postcss-import";
import postcssMixins from "postcss-mixins";
import postcssReplace from "postcss-replace";
import tailwindcss from "tailwindcss";
import nesting from "tailwindcss/nesting/index.js";
import { existsSync, readFileSync } from "node:fs";

const breakpoints = {};
if (existsSync("theme.json")) {
  const theme = JSON.parse(readFileSync("theme.json", "utf8"));
  if ("breakpoints" in theme.settings.custom) {
    for (const breakpoint of Object.keys(theme.settings.custom.breakpoints)) {
      breakpoints[`jcore-breakpoint-${breakpoint}`] =
        theme.settings.custom.breakpoints[breakpoint];
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
    postcssReplace({ data: breakpoints }),
    nesting,
    tailwindcss,
    postcssPresetEnv({ stage: 2, features: { "nesting-rules": false } }),
  ],
};
