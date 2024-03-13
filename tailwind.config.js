import {existsSync, readFileSync} from "node:fs";

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
    safelist: [
        {
            pattern: /^([mp][tbrlxy]?)-.+$/,
            variants: ["sm", "md", "lg", "xl"],
        },
        {
            pattern: /^((inline-)?(flex|hidden|block|grid))$/,
            variants: ["sm", "md", "lg", "xl"],
        },
        {
            pattern: /^(justify|items|align|self)-.+$/,
            variants: ["sm", "md", "lg", "xl"],
        }
    ],
    plugins: [],
};
