// tailwind.config.js
import defaultTheme from "tailwindcss/defaultTheme";
import flowbite from "flowbite/plugin";


/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.jsx",
        "./resources/**/*.tsx",
        "./node_modules/flowbite/**/*.js", 
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                pink: {
                    1: "#FA9AC2",
                    2: "#78415A",
                    3: "#F0A8C7",
                    4: "#FFDAEA",
                },
            },
        },
    },
    plugins: [
        flowbite,
    ],
};
