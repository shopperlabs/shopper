const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
  purge: {
    enable: true,
    content: [
      "./resources/assets/js/**/*.js",
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
      colors: {},
      boxShadow: {
        smooth: "0 2px 20px 0 rgba(0, 0, 0, 0.05)",
        bigger: "0 10px 20px 0 rgba(0, 0, 0, 0.01)",
      },
      spacing: {
        125: "31.25rem",
        140: "35rem"
      },
      fontFamily: {
        sans: ["Inter var", ...defaultTheme.fontFamily.sans]
      }
    },
  },
  variants: {
    translate: ["responsive", "hover", "focus", "active", "group-hover"],
    backgroundColor: ["responsive", "hover", "focus", "group-hover", "focus-within", "odd"],
    textColor: ["responsive", "hover", "focus", "group-hover", "focus-within", "odd"],
    borderWidth: ["responsive", "odd", "hover", "focus", "odd"],
  },
  plugins: [
    require("@tailwindcss/ui")({
      layout: 'sidebar',
    }),
  ]
};
