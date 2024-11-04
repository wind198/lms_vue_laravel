/**
 * .eslint.js
 *
 * ESLint configuration file.
 */

module.exports = {
  root: true,
  env: {
    node: true,
  },
  extends: [
    'vuetify',
    '@vue/eslint-config-typescript',
    './.eslintrc-auto-import.json',
  ],
  rules: {
    'vue/multi-word-component-names': 'off',
    'vue/script-indent': 'off',
    'vue/valid-template-root': 'warn',
    indent: 'off',
    'vue/html-indent': 'off',
    camelcase: 'off',
    'vue/attributes-order': 'off',
    'space-before-function-paren': 'off',
    'arrow-parens': 'warn',
  },
}
