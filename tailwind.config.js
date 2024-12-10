/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
      ],
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
                danger: '#FF0000'
            },
            fontFamily: {
                'myriad': ['"Myriad Pro"'],
                'roboto': ['"Roboto"'],
                'josefin': ['"Josefin Sans"'],
            },
        },
      },
  plugins: [],
}

