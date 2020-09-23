<template>
  <span class="fontawesome-index-icon">
    <i :class="field.value"></i>
  </span>
</template>

<script>
export default {
  props: ["resourceName", "field"],

  beforeMount() {
    const { library } = require("@fortawesome/fontawesome-svg-core");
    const fab = require("@fortawesome/free-brands-svg-icons").fab;

    if (this.pro) {
      const fas = require("@fortawesome/pro-solid-svg-icons").fas;
      const far = require("@fortawesome/pro-regular-svg-icons").far;
      const fal = require("@fortawesome/pro-light-svg-icons").fal;
      const fad = require("@fortawesome/pro-duotone-svg-icons").fad;
      require("@fortawesome/fontawesome-pro/js/fontawesome.js");

      library.add(fas, far, fab, fal, fad);
    } else {
      const fas = require("@fortawesome/free-solid-svg-icons").fas;
      const far = require("@fortawesome/free-regular-svg-icons").far;
      require("@fortawesome/fontawesome-free/js/fontawesome.js");

      library.add(fas, far, fab);
    }
  },

  computed: {
    pro() {
      return this.field.pro || false;
    },
  },
};
</script>

<style>
.fontawesome-index-icon {
  font-size: 1.5em;
}

.fontawesome-index-icon svg {
  width: 1em !important;
  height: 1.5em !important;
}
</style>
