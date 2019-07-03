<template>
    <button
        class="btn btn-filter"
        @click="$emit('click')"
    >
        {{ filterName }}
    </button>
</template>

<script>
export default {
    name: 'ButtonFilter',
    props: {
        filterName: { type: String, required: true }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.btn-filter {
    @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
    @include font-size(1.5);
    background-color: transparent;
    border: none;
    border-radius: 5px;
    color: $secondary-color;
    height: 30px;

    a {
        text-decoration: none;
        color: #000;
    }

    &:hover {
        @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
        background-color: $primary-color;

        a {
            color: #FFF;
            text-decoration: none;
        }
    }
}
</style>
