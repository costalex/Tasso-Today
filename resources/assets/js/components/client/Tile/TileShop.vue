<template>
    <div>
        <div
            class="shop-tile"
            @click="$emit('click')"
        >
            <img
                :src="imagePath"
                :alt="`Entreprise ${categoryName}`"
                class="img-responsive"
            >

            <p>
                {{ shopName }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'TileShop',
    props: {
        shopName: { type: String, required: true },
        imagePath: { type: String, required: true }
    }
};
</script>

<style lang="scss" scoped>
.shop-tile {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2);
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    -webkit-transform: translateY(0);
    transform: translateY(0);
    transition: box-shadow .3s,-webkit-transform .3s;
    transition: box-shadow .3s,transform .3s;
    transition: box-shadow .3s,transform .3s,-webkit-transform .3s;
    cursor: pointer;
    margin-bottom: 20px;

    img {
        min-height: 200px;
        width: 100%;
        margin-bottom: 16px;
    }

    p {
        padding: 0 10px 10px 10px;
    }

    &:hover {
        box-shadow: 0 5px 10px 0 rgba(0,0,0,.1);
        -webkit-transform: translateY(-6px);
        transform: translateY(-6px);
    }
}

@media (min-width: 576px) and (max-width: 768px) {
    .shop-tile:nth-child(odd) {
        padding-right: 8px;
    }

    .shop-tile:nth-child(even) {
        padding-left: 8px;
    }
}

@media (min-width: 769px)  {
    .shop-tile {
        padding-right: 8px;
        padding-left: 8px;
    }

    .shop-tile:nth-of-type(4n+1) {
        margin-left: -8px;
    }
}
</style>
