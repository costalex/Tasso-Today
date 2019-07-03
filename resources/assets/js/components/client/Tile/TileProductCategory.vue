<template>
    <div
        class="category-tile"
        @click="$emit('click')"
    >
        <img
            :src="imagePath"
            :alt="`Catégorie ${categoryName}`"
            class="img-responsive"
        >
    </div>
</template>

<script>
export default {
    name: 'TileProductCategory',
    props: {
        categoryName: { type: String, required: true },
        imagePath: { type: String, required: true }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';

.category-tile {
    padding: 0 5px;
    margin: 5px 0;

    img {
        border-radius: 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,.25);
        cursor: pointer;
        -webkit-transition: all 0.3s cubic-bezier(.25,.8,.25,1);
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);

        &:hover {
            box-shadow: 0 14px 28px $shadow-box-color, 0 10px 10px rgba(0,0,0,0.22);
        }
    }
}
</style>
