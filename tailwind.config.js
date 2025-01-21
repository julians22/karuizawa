const animate = require("tailwindcss-animate")

/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: ["class"],
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
	],
  prefix: "",
  theme: {
    container: {
        center: true,
        padding: '1rem',
        screens: {
            sm: "540px",
            md: "720px",
            lg: "960px",
            xl: "1140px",
            xxl: "1320px"
        },
    },
    letterSpacing: {
        tightest: '-.075em',
        tighter: '-.05em',
        normal: '0',
        wider: '.05em',
        widest: '.15em',
    },
    extend: {
        colors: {
            primary: {
                DEFAULT: '#21362c',
                50: '#294634',
                100: '#41695b',
                200: '#5d7261',
                300: '#AFC30A',
            },
            secondary: {
                DEFAULT: '#dcd3cb',
                50: '#715745',
            },
            danger: '#FF0000',
            blue: {
                ka: '#3A80D5',
            },
            pink: {
                ka: '#F2089E',
            }
        },
        fontFamily: {
            'myriad': ['"Myriad Pro"'],
            'roboto': ['"Roboto"'],
            'josefin': ['"Josefin Sans"'],
        },
        keyframes: {
            "accordion-down": {
            from: { height: 0 },
            to: { height: "var(--radix-accordion-content-height)" },
            },
            "accordion-up": {
            from: { height: "var(--radix-accordion-content-height)" },
            to: { height: 0 },
            },
        },
        animation: {
            "accordion-down": "accordion-down 0.2s ease-out",
            "accordion-up": "accordion-up 0.2s ease-out",
        },
    },
  },
  plugins: [animate],
}
