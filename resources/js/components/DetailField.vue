<template>
  <panel-item :field="field">
    <span slot="value" class="fontawesome-index-icon">
      <i :class="field.value"></i>
    </span>
  </panel-item>
</template>

<script>
export default {
  props: ["resource", "resourceName", "resourceId", "field"],
};
</script>

<style>
.fontawesome-index-icon {
  font-size: 1.5em;
}

.fontawesome-index-icon svg {
  width: 1.5em;
  height: 1.5em;
}
</style>
