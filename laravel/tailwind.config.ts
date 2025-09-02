import type { Config } from "tailwindcss";

const config: Config = {
  content: [
    "./resources/**/*.{blade.php,vue,js,ts}",
  ],
  theme: {
    fontFamily: {
      sans: ["Noto Sans JP", "Noto Sans", "system-ui", "sans-serif"],
    },
    extend: {
      colors: {
        primary: {
          50: "#FFF7ED",
          100: "#FFE8D9",
          500: "#F97316",
          600: "#EA580C",
        },
        gray: {
          50: "#F8F8FB",
          100: "#F1F1F4",
          200: "#E8E8EB",
          400: "#CACACE",
          500: "#949497",
          600: "#757578",
          700: "#626264",
          800: "#414143",
        },
        red: {
          50: "#FEF2F2",
          500: "#EF4444",
          600: "#DC2626",
        },
        yellow: {
          50: "#FFFBEB",
        },
      },
    },
  },
  plugins: [],
};

export default config;
