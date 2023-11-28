import postcssPresetEnv from "postcss-preset-env";
import postcssImport from "postcss-import"
import postcssMinify from "postcss-minify"
export default {
    map: {
        inline: false
    },
    plugins: [
        postcssImport,
        postcssPresetEnv({stage: 2}),
        postcssMinify
    ]
}
