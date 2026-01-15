import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },

            colors: {
                accent: {
                    DEFAULT: "#0EA5E9",
                    hover: "#38BDF8",
                    focus: "#0284C7",
                },


                success: "#22C55E",
                warning: "#FACC15",
                error: "#EF4444",
                info: "#0EA5E9",


                bg: "rgb(var(--bg) / <alpha-value>)",
                surface: "rgb(var(--surface) / <alpha-value>)",
                border: "rgb(var(--border) / <alpha-value>)",

                text: {
                    DEFAULT: "rgb(var(--text) / <alpha-value>)",
                    muted: "rgb(var(--text-muted) / <alpha-value>)",
                },
            },
        },
    },

    plugins: [forms],

    darkMode: "class",
};
