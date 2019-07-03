<template>
    <div>
        <div id="SideNav">
            <VendorMenu/>
        </div>

        <VendorTopNav/>

        <div id="VendorBody">
            <router-view/>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import VendorMenu from '@/components/vendor/VendorMenu';
import VendorTopNav from '@/components/vendor/VendorTopNav';

export default {
    name: 'SectionVendor',
    components: { VendorMenu, VendorTopNav },
    computed: {
        ...mapState({
            TypeAccount: state => state.GeneralModule.TypeUser
        })
    },
    beforeCreate () {
        const vendorRoutes = [
            'vendorProduct',
            'vendorProfile',
            'vendorSections',
            'vendorOrders',
            'vendorFaq'
        ];

        if (!vendorRoutes.includes(this.$route.name)) {
            this.$router.push({ name: 'vendorProduct' });
        }
    },
    mounted () {
        this.$store.dispatch('GET_COMMANDE');

        this.intervalid1 = setInterval(() => {
            this.$store.dispatch('GET_COMMANDE');
        }, 10000);
    },
    beforeDestroy () {
        clearInterval(this.intervalid1);
    }
};
</script>

<style lang="scss" scoped>
#VendorBody {
    padding: 0;
    position: absolute;
    left: 110px;
    right: 20px;
    top: 70px;
}
</style>
