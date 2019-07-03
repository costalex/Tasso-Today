<template>
    <div class="row category-tile-list">
        <TileShopCategory
            v-for="category in categories"
            :key="category.id"
            :category-name="category.nom"
            :image-path="category.img_path"
            class="col-sm-2 col-xs-6"
            @click="$emit('click', category)"
        />
    </div>
</template>

<script>
import TileShopCategory from './TileProductCategory';

export default {
    name: 'TileProductCategoryList',
    components: { TileShopCategory },
    props: {
        categories: { type: Array, required: true }
    }
};
</script>

<style lang="scss" scoped>
.category-tile-list {
    padding: 0 5px;
    margin: 5px 0;
}
</style>
