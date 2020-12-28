<template>
  <span class="fontawesome-index-icon">
    <i :class="field.value"></i>
  </span>
</template>

<script>
export default {
  props: ["resourceName", "field"],

  beforeMount() {
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
</style>
