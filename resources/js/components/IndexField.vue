<template>
  <span class="fontawesome-index-icon">
    <i :class="field.value"></i>
  </span>
</template>

<script>
export default {
  props: ["resourceName", "field"],
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
