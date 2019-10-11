<template>
    <panel-item :field="field">
        <span slot="value" class="fontawesome-index-icon"><i :class="field.value"></i></span>
    </panel-item>
</template>

<script>
    import { library } from '@fortawesome/fontawesome-pro'
    // import { fas } from '@fortawesome/free-solid-svg-icons'
    // import { far } from '@fortawesome/free-regular-svg-icons'
    // import { fab } from '@fortawesome/free-brands-svg-icons'

    import { fas } from '@fortawesome/pro-solid-svg-icons'
    import { far } from '@fortawesome/pro-regular-svg-icons'
    import { fal } from '@fortawesome/pro-light-svg-icons'
    import { fad } from '@fortawesome/pro-duotone-svg-icons'

    // Add all icons to the library so you can use it in your page
    library.add(fas, far, fal, fad)

    import '@fortawesome/fontawesome-free/js/fontawesome.js'

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
}
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
