import { existsSync, readFileSync } from "node:fs";

const colors = {};
const breakpoints = {};
if (existsSync("theme.json")) {
  const theme = JSON.parse(readFileSync("theme.json", "utf8"));
  for (const color of theme.settings.color.palette) {
    colors[color.slug] = color.color;
  }
  if ("breakpoints" in theme.settings.custom) {
    for (const breakpoint of Object.keys(theme.settings.custom.breakpoints)) {
      breakpoints[breakpoint] = theme.settings.custom.breakpoints[breakpoint];
    }
  }
}

/** @type {import('tailwindcss').Config} */
export default {
  content: ["./views/**/*.twig", "./src/js/**/*.js", "./theme.json"],
  theme: {
    extend: {
      fontFamily: {
        inter: ["Inter", "sans-serif"],
        "inter-tight": ["Inter Tight", "sans-serif"],
      },
      fontSize: {
        xs: ["0.75rem", { lineHeight: "1.5" }],
        sm: ["0.875rem", { lineHeight: "1.5715" }],
        base: ["1rem", { lineHeight: "1.5", letterSpacing: "-0.017em" }],
        lg: ["1.125rem", { lineHeight: "1.5", letterSpacing: "-0.017em" }],
        xl: ["1.25rem", { lineHeight: "1.5", letterSpacing: "-0.017em" }],
        "2xl": ["1.5rem", { lineHeight: "1.415", letterSpacing: "-0.017em" }],
        "3xl": ["2rem", { lineHeight: "1.3125", letterSpacing: "-0.017em" }],
        "4xl": ["2.5rem", { lineHeight: "1.25", letterSpacing: "-0.017em" }],
        "5xl": ["3.25rem", { lineHeight: "1.2", letterSpacing: "-0.017em" }],
        "6xl": ["3.75rem", { lineHeight: "1.1666", letterSpacing: "-0.017em" }],
        "7xl": ["4.5rem", { lineHeight: "1.1666", letterSpacing: "-0.017em" }],
      },
      animation: {
        "infinite-scroll": "infinite-scroll 60s linear infinite",
        "infinite-scroll-inverse":
          "infinite-scroll-inverse 60s linear infinite",
      },
      keyframes: {
        "infinite-scroll": {
          from: { transform: "translateX(0)" },
          to: { transform: "translateX(-100%)" },
        },
        "infinite-scroll-inverse": {
          from: { transform: "translateX(-100%)" },
          to: { transform: "translateX(0)" },
        },
      },
      colors: {
        ...colors,
        transparent: "rgba(0,0,0,0)",
      },
    },
    screens: { ...breakpoints },
  },
  safelist: [
    {
      pattern: /^([mp][tbrlxy]?)-.+$/,
      variants: ["sm", "md", "lg", "xl", "2xl"],
    },
    {
      pattern: /^((inline-)?(flex|hidden|block|grid))$/,
      variants: ["sm", "md", "lg", "xl", "2xl"],
    },
    {
      pattern: /^(justify|items|align|self)-.+$/,
      variants: ["sm", "md", "lg", "xl", "2xl"],
    },
  ],
  plugins: [require("@tailwindcss/forms")],
};
