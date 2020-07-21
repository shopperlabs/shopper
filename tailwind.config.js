const defaultTheme = require("tailwindcss/defaultTheme");
const rgba = require("hex-to-rgba");

module.exports = {
  purge: {
    enable: true,
    content: [
      "./resources/assets/ts/**/*.ts",
      "./resources/assets/ts/**/*.tsx",
      "./resources/views/**/*.php",
      "./src/**/*.php",
    ],
    options: {
      defaultExtractor: content => content.match(/[\w-/.:]+(?<!:)/g) || [],
      whitelistPatterns: [/-active$/, /-enter$/, /-leave-to$/, /show$/]
    }
  },
  theme: {
    extend: {
      colors: {
        brand: {
          50: "#50ccff",
          100: "#E6F2F9",
          200: "#BFDEF0",
          300: "#99CBE7",
          400: "#048DDB",
          500: "#007CC3",
          600: "#0070B0",
          700: "#004A75",
          800: "#003858",
          900: "#00253B"
        }
      },
      boxShadow: theme => ({
        smooth: "0 2px 20px 0 rgba(0, 0, 0, 0.05)",
        bigger: "0 10px 20px 0 rgba(0, 0, 0, 0.01)",
        "outline-brand": `0 0 0 3px ${rgba(theme("colors.brand.400"), 0.45)}`
      }),
      spacing: {
        125: "31.25rem",
        140: "35rem"
      },
      fontFamily: {
        body: ["Inter var", ...defaultTheme.fontFamily.sans]
      }
    },
    customForms: theme => ({
      default: {
        "input, textarea, select, multiselect, checkbox, radio": {
          borderWidth: defaultTheme.borderWidth[2],
          "&:focus": {
            outline: "none",
            boxShadow: "none",
            borderColor: theme("colors.brand.400")
          }
        }
      }
    }),
    spinner: theme => ({
      default: {
        color: theme("colors.brand.500"), // color you want to make the spinner
        size: "1em", // size of the spinner (used for both width and height)
        border: "2px", // border-width of the spinner (shouldn't be bigger than half the spinner's size)
        speed: "500ms" // the speed at which the spinner should rotate
      }
    })
  },
  variants: {
    translate: ["responsive", "hover", "focus", "active", "group-hover"],
    backgroundColor: ["responsive", "hover", "focus", "group-hover", "focus-within", "odd"],
    textColor: ["responsive", "hover", "focus", "group-hover", "focus-within", "odd"],
    borderWidth: ["responsive", "odd", "hover", "focus", "odd"],
    fontFamily: ["responsive", "hover", "focus"],
    zIndex: ["responsive", "focus"],
    spinner: ["responsive"]
  },
  plugins: [
    require("@tailwindcss/ui"),
    require("tailwindcss-spinner")()
  ]
};
