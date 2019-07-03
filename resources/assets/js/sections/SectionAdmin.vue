<template>
    <div>
        <div id="SideNav">
            <AdminMenu/>
        </div>

        <AdminTopNav/>

        <div
            id="AdminBody"
            class="col-xs-10"
        >
            <router-view/>
        </div>
    </div>
</template>

<script>
import AdminMenu from '@/components/admin/AdminMenu';
import AdminTopNav from '@/components/admin/AdminTopNav';

export default {
    name: 'SectionAdmin',
    components: { AdminMenu, AdminTopNav },
    beforeCreate () {
        const adminRoutes = [
            'ShopListBody',
            'AdminVendorRegistration',
            'AdminShopsDetails',
            'AdminProductModify',
            'AdminProduct'
        ];

        if (!adminRoutes.includes(this.$route.name)) {
            this.$router.push({ name: 'AdminProduct' });
        }
    }
};
</script>

<style lang="scss">
@import '~sass/_variables';
@import '~sass/_mixins';

#AdminBody {
    padding: 0 !important;
    position: absolute;
    left: 110px;
    right: 20px;
    top: 70px;

    .filter-picker {
        @include prefix(box-shadow, none, webkit moz ms o);
        border: 1px solid $grey;
        border-radius: 5px;
        display: inline-block;
        margin-right: 10px;
        overflow: hidden;
        padding: 5px 8px;
        width: -moz-available;
        width: -webkit-fill-available;
    }
}
</style>
