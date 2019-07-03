<template>
    <div>
        <ClientTopNav/>

        <div id="BoddyClient">
            <router-view/>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import ClientTopNav from '@/components/client/ClientTopNav';

export default {
    name: 'SectionClient',
    components: { ClientTopNav },
    computed: {
        ...mapState({
            CookieAccepted: state => state.GeneralModule.CookieAccepted,
            TypeUser: state => state.GeneralModule.TypeUser
        })
    },
    beforeMount () {
        const vendorRoutes = [
            'vendorProduct',
            'vendorProfile',
            'vendorSections',
            'vendorOrders',
            'vendorFaq'
        ];

        const adminRoutes = [
            'ShopListBody',
            'AdminVendorRegistration',
            'AdminShopsDetails',
            'AdminProductModify',
            'AdminProduct'
        ];

        if ((vendorRoutes.includes(this.$route.name) || adminRoutes.includes(this.$route.name)) &&
            this.TypeUser === 'Guest'
        ) {
            this.$router.push({ name: 'home' });
        } else if ((this.$route.name === 'home' ||
            (vendorRoutes.includes(this.$route.name) || adminRoutes.includes(this.$route.name))) &&
            this.TypeUser === 'Client'
        ) {
            this.$router.push({ name: 'familliesList' });
        }
    },
    mounted () {
        if (this.CookieAccepted) {
            this.$session.start();
        }
    }
};
</script>
