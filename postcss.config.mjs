import autoprefixer from "autoprefixer";
import postcssPresetEnv from "postcss-preset-env";
import postcssImport from "postcss-import"
export default {
    plugins: [
        postcssImport,
        autoprefixer,
        postcssPresetEnv({stage: 0})
    ]
}
