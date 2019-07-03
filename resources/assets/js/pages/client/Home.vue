<template>
    <div
        id="LandingClient"
        class="page-wrap-home"
    >
        <div class="client-homepage-windshield">
            <div class="address-bar-block">
                <h1>
                    Tout. Partout.
                    <br>
                    Quand vous voulez.
                </h1>

                <h2>Vos commerces de proximité à portée de clic.</h2>

                <AddressBar google-autocomplete-id="map1" />

                <p class="beta-notice">
                    <span>BETA</span>

                    🚧👷‍♀️: des bugs ? des recommandations ? Faites-nous en part :

                    <a
                        href="mailto:contact@tasso.today?Subject=Avis%20Beta"
                        target="_blank"
                    >
                        contact@tasso.today
                    </a>
                </p>
            </div>

            <img
                class="l-salad"
                src="/storage/bobby_images/landing_page/salad.png"
            >

            <img
                class="l-tomato"
                src="/storage/bobby_images/landing_page/tomato.png"
            >

            <img
                class="l-cheese"
                src="/storage/bobby_images/landing_page/cheese.png"
            >

            <img
                class="l-grocery"
                src="/storage/bobby_images/landing_page/grocery-bag.png"
            >

            <img
                class="r-cactus"
                src="/storage/bobby_images/landing_page/cactus-jar.png"
            >

            <img
                class="r-croissant"
                src="/storage/bobby_images/landing_page/croissant.png"
            >

            <img
                class="r-tshirt"
                src="/storage/bobby_images/landing_page/tshirt.png"
            >

            <img
                class="r-sandwich"
                src="/storage/bobby_images/landing_page/sandwich.png"
            >
        </div>

        <div class="homepage-wrapper">
            <div class="col-sm-10 col-xs-12 perks">
                <div class="col-sm-4 col-xs-12 perk-1">
                    <img
                        class="img-responsive"
                        src="/storage/bobby_images/landing_page/logo_all_product.svg"
                    >

                    <p>Vos produits préférés</p>
                </div>

                <div class="col-sm-4 col-xs-12 perk-2">
                    <img
                        class="img-responsive"
                        src="/storage/bobby_images/landing_page/logo_click_collect.svg"
                    >

                    <p>Retrait en boutique sous 1h</p>
                </div>

                <div class="col-sm-4 col-xs-12 perk-3">
                    <img
                        class="img-responsive"
                        src="/storage/bobby_images/landing_page/bike_delivery.svg"
                    >

                    <p>Livraison écologique à vélo sous 3h</p>
                </div>
            </div>

            <div class="product-category-list-container">
                <h1>Votre ville et ses commerces s'ouvrent à vous</h1>

                <TileProductCategoryList
                    :categories="productCategoryListToShow"
                    @click="searchShopFamille"
                />
            </div>

            <div class="goods-deliver">
                <img src="/storage/bobby_images/landing_page/goods-we-deliver.png">
                <div class="gd-content">
                    <h3>
                        On vous livre tout,
                        <br>
                        et pas que des livres.
                    </h3>

                    <p>
                        Faites livrer vos courses et achats en une heure ou sur rendez-vous pour que vous puissiez
                        profiter de votre famille et amis. Que vous ayez besoin d’un kilo de tomates,
                        d’une paire de chaussures ou d’un kit apéritif, vous trouverez tout sur Tasso.
                    </p>
                </div>
            </div>

            <div class="click-collect">
                <div class="cc-content">
                    <h3>
                        Récupérez vos achats,
                        <br>
                        sans faire la queue.
                    </h3>

                    <p>
                        Mieux que le coupe file, avec l’option « Retrait » de Tasso, faites vos courses en ligne
                        sans perdre de temps en magasin, ni en caisse. Retirez directement vos achats en boutique
                        sans faire la queue, quand vous voulez. Dites au revoir à l’attente.
                    </p>
                </div>

                <img src="/storage/bobby_images/landing_page/click-collect.png">
            </div>
        </div>

        <div class="ready-order">
            <h2>Prêt à commander ?</h2>

            <p>Découvrez les commerces locaux disponibles en livraison et en retrait en magasin autour de chez vous.</p>

            <AddressBar google-autocomplete-id="map2" />
        </div>

        <div class="vendor-join">
            <h3>Commerçants ? Franchisés ? Enseignes ?  🛍️</h3>

            <p>
                Gagnez en visibilité en ligne avec la marketplace Tasso, proposez à vos clients des services à forte
                valeur ajoutée avec nos solutions clé en main.
            </p>

            <p class="vj-cta">Créez votre boutique en ligne en toute simplicité !</p>

            <div class="vj-buttons">
                <button class="vj-start">
                    <a
                        href="https://tasso5.typeform.com/to/CfdXOV"
                        target="_blank"
                    >
                        Démarrer
                    </a>
                </button>
            </div>
        </div>

        <footer class="container-fluid homepage-footer">
            <div class="homepage-footer-container">
                <div class="row homepage-footer-content">
                    <div class="col-sm-3 col-xs-6 f-discover">
                        <a>
                            <img
                                src="/storage/bobby_images/marketplace-client/tasso-footer-logo.svg"
                                class="tasso-footer-logo"
                            >
                        </a>

                        <ul>
                            <h4>Découvrez Tasso !</h4>

                            <li>
                                <a href="https://blog.tasso.today/">
                                    Oyez, Oyez !
                                    <i>(le blog)</i>
                                </a>
                            </li>

                            <li class="social-icons">
                                <a
                                    href="https://www.instagram.com/tassofr/?hl=fr"
                                    target="_blank"
                                >
                                    <img src="/storage/bobby_images/marketplace-client/icon-instagram.svg">
                                </a>

                                <a
                                    href="https://www.facebook.com/tassofr/"
                                    target="_blank"
                                >
                                    <img src="/storage/bobby_images/marketplace-client/icon-facebook.svg">
                                </a>

                                <a
                                    href="https://twitter.com/tassofr"
                                    target="_blank"
                                >
                                    <img src="/storage/bobby_images/marketplace-client/icon-twitter.svg">
                                </a>

                                <a
                                    href="https://www.linkedin.com/company/tassodelivery/"
                                    target="_blank"
                                >
                                    <img src="/storage/bobby_images/marketplace-client/icon-linkedin.svg">
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-sm-3 col-xs-6 f-world">
                        <ul>
                            <h4>Le monde de Tasso</h4>

                            <li>
                                <a
                                    href="https://tassodelivery.com/index"
                                    target="_blank"
                                >
                                    www.tassodelivery.com
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-sm-3 col-xs-6 f-qa">
                        <ul>
                            <h4>Vos questions, nos réponses</h4>

                            <li>
                                <a
                                    href="mailto:aide@tasso.today?Subject=J'ai%20besoin%20d'aide%20!"
                                    target="_top"
                                >
                                    aide@tasso.today
                                </a>
                            </li>

                            <li>
                                <a
                                    href="https://twitter.com/TassoAide"
                                    target="_blank"
                                >
                                    @TassoAide
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-sm-3 col-xs-6 f-partners">
                        <ul>
                            <h4>Partenaires</h4>

                            <li>
                                <a @click="$router.push({ name: 'loginPage' })">
                                    Connexion Espace Partenaire
                                </a>
                            </li>
                        </ul>

                        <ul>
                            <h4>Coursiers</h4>

                            <li>
                                <a
                                    href="https://api.tassodelivery.com/registration/courier"
                                    target="_blank"
                                >
                                    Devenir coursier Tasso
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row footer-terms-content">
                    <a
                        class="col-sm-4 col-xs-12"
                        @click="$router.push({ name: 'cgv' })"
                    >
                        Conditions Générales de Vente et d'Utilisation
                    </a>
                    <a
                        class="col-sm-3 col-xs-12"
                        @click="$router.push({ name: 'politique' })"
                    >
                        Politique de confidentialité
                    </a>
                    <a
                        class="col-sm-3 col-xs-12"
                        @click="$router.push({ name: 'mentions' })"
                    >
                        Mentions Légales
                    </a>

                    <a class="col-sm-2 col-xs-12">
                        ©2018 TASSO
                    </a>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import AddressBar from '@/components/client/PageHome/HomePageAddressBar';
import TileProductCategoryList from '@/components/client/Tile/TileProductCategoryList';

export default {
    name: 'Home',
    components: {
        AddressBar,
        TileProductCategoryList
    },
    computed: {
        ...mapState({
            General_panier: state => state.CartModule.panier_general
        }),
        categories: {
            get () {
                return this.$store.getters.getCategories;
            }
        },
        Famillies: {
            get () {
                return this.$store.getters.getFamilles;
            }
        },
        Shop: {
            get () {
                return this.$store.getters.getEnseignes;
            }
        },
        productCategoryListToShow () {
            let productCategoryListToShow = [];

            productCategoryListToShow =
                this.Famillies.filter(x => x.id !== 0 && x.id !== 3);

            return productCategoryListToShow;
        }
    },
    mounted () {
        if (this.$session.get('panier')) {
            this.$store.commit('SET_GENERAL_PANIER_CLIENT', this.$session.get('panier'));
            this.$store.dispatch('GET_GENERAL_INFORMATIONS');
            this.$session.remove('panier');
        }

        this.$store.commit('RESET_PANIER');
        this.$store.dispatch('INITIALIZE_LIST_FAMILLE');
        this.$store.commit('SET_RESET_INFO');
    },
    beforeUpdate () {
        if (this.$session.exists('panier')) {
            this.$session.remove('panier');
        }

        this.$session.set('panier', this.General_panier);
    },
    methods: {
        searchShopFamille (famille) {
            this.$store.commit('SET_SELECT_FAMILLE', famille.id);

            this.$router.push({
                name: 'family',
                params: {
                    name_famille: famille.nom
                }
            });
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

#LandingClient {
    margin: 0 auto;
    padding: 0;
    max-width: none;
    position: relative;

    .client-homepage-windshield {
        display: flex;
        position: relative;
        align-items: center;
        height: 572px;
        background-color: #FFF6E6;
        margin-top: -72px;
        margin-bottom: 60px;
        overflow: hidden;

        img {
            position: absolute;
            -webkit-animation-timing-function: ease-out;
            animation-timing-function: ease-out;
        }

        @media (max-width: 650px) {
            img {
                display: none;
            }
        }

        .l-salad {
            -webkit-animation: salad 5s forwards;
            animation: salad 5s forwards;
            width: 72px;
            left: 75px;
        }

        .l-tomato {
            -webkit-animation: tomato 5s forwards;
            animation: tomato 5s forwards;
            width: 30px;
            left: 280px;
        }

        .l-cheese {
            -webkit-animation: cheese 5s forwards;
            animation: cheese 5s forwards;
            width: 50px;
            left: 70px;
        }

        .l-grocery {
            -webkit-animation: grocery 5s forwards;
            animation: grocery 5s forwards;
            width: 120px;
            left: 180px;
        }

        @-webkit-keyframes salad {
            from {bottom: -100px;}
            to {bottom: 260px;}
        }

        @keyframes salad {
            from {bottom: -100px;}
            to {bottom: 260px;}
        }

        @-webkit-keyframes tomato {
            from {bottom: -100px;}
            to {bottom: 320px;}
        }

        @keyframes tomato {
            from {bottom: -100px;}
            to {bottom: 320px;}
        }

        @-webkit-keyframes cheese {
            from {bottom: -100px;}
            to {bottom: 70px;}
        }

        @keyframes cheese {
            from {bottom: -100px;}
            to {bottom: 70px;}
        }

        @-webkit-keyframes grocery {
            from {bottom: -100px;}
            to {bottom: 115px;}
        }

        @keyframes grocery {
            from {bottom: -100px;}
            to {bottom: 115px;}
        }

        @media (max-width: 1050px) {
            .l-salad {
                left: 55px;
            }

            @-webkit-keyframes salad {
                from {bottom: -100px;}
                to {bottom: 320px;}
            }

            @keyframes salad {
                from {bottom: -100px;}
                to {bottom: 320px;}
            }

            .l-tomato {
                left: 180px;
            }

            @-webkit-keyframes tomato {
                from {bottom: -100px;}
                to {bottom: 400px;}
            }

            @keyframes tomato {
                from {bottom: -100px;}
                to {bottom: 400px;}
            }

            .l-grocery {
                left: 40px;
            }

            @-webkit-keyframes cheese {
                from {bottom: -100px;}
                to {bottom: 40px;}
            }

            @keyframes cheese {
                from {bottom: -100px;}
                to {bottom: 25px;}
            }
        }

        .r-cactus {
            -webkit-animation: cactus 5s forwards;
            animation: cactus 5s forwards;
            width: 60px;
            right: 230px;
        }

        .r-croissant {
            -webkit-animation: croissant 5s forwards;
            animation: croissant 5s forwards;
            width: 50px;
            right: 170px;
        }

        .r-tshirt {
            -webkit-animation: tshirt 5s forwards;
            animation: tshirt 5s forwards;
            width: 120px;
            right: 75px;
        }

        .r-sandwich {
            -webkit-animation: sandwich 5s forwards;
            animation: sandwich 5s forwards;
            width: 110px;
            right: 180px;
        }

        @-webkit-keyframes cactus {
            from {bottom: -100px;}
            to {bottom: 240px;}
        }

        @keyframes cactus {
            from {bottom: -100px;}
            to {bottom: 240px;}
        }

        @-webkit-keyframes croissant {
            from {bottom: -100px;}
            to {bottom: 360px;}
        }

        @keyframes croissant {
            from {bottom: -100px;}
            to {bottom: 360px;}
        }

        @-webkit-keyframes tshirt {
            from {bottom: -100px;}
            to {bottom: 140px;}
        }

        @keyframes tshirt {
            from {bottom: -100px;}
            to {bottom: 140px;}
        }

        @-webkit-keyframes sandwich {
            from {bottom: -100px;}
            to {bottom: 70px;}
        }

        @keyframes sandwich {
            from {bottom: -100px;}
            to {bottom: 70px;}
        }

        @media (max-width: 1050px) {
            .r-cactus {
                right: 60px;
            }

            @-webkit-keyframes cactus {
                from {bottom: -100px;}
                to {bottom: 280px;}
            }

            @keyframes cactus {
                from {bottom: -100px;}
                to {bottom: 280px;}
            }

            @-webkit-keyframes croissant {
                from {bottom: -100px;}
                to {bottom: 385px;}
            }

            @keyframes croissant {
                from {bottom: -100px;}
                to {bottom: 385px;}
            }

            .r-tshirt {
                right: 30px;
            }

            @-webkit-keyframes tshirt {
                from {bottom: -100px;}
                to {bottom: 110px;}
            }

            @keyframes tshirt {
                from {bottom: -100px;}
                to {bottom: 110px;}
            }

            .r-sandwich {
                right: 110px;
            }

            @-webkit-keyframes sandwich {
                from {bottom: -100px;}
                to {bottom: 20px;}
            }

            @keyframes sandwich {
                from {bottom: -100px;}
                to {bottom: 20px;}
            }
        }

        .address-bar-block {
            margin: 0 auto;
            padding: 0 10px;
            text-align: center;
            background-image: none;
            background-color: transparent;
            background-size: cover;
            vertical-align: middle;
            z-index: 90;

            h1 {
                font-size: 54px;
                padding: 0;
                margin: 0 auto;
                margin-bottom: 25px;
            }

            h2 {
                font-size: 22px;
                font-weight: 400;
                color: #2D3138;
                padding: 0;
                margin: 0 auto;
            }

            .beta-notice {
                font-weight: 400;
                margin: 0 auto;
                margin-top: 30px;
                padding: 10px 15px;
                background-color: #FFF;
                border-radius: 4px;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.18);

                span {
                    padding: 2px 5px;
                    margin-right: 10px;
                    border-radius: 4px;
                    font-weight: 500;
                    color: #FFF;
                    -webkit-animation-name: beta-glow;
                    -webkit-animation-duration: 4s;
                    -webkit-animation-iteration-count: infinite;
                    -webkit-animation-direction: alternate;
                    animation-name: beta-glow;
                    animation-duration: 4s;
                    animation-iteration-count: infinite;
                    animation-direction: alternate;
                }

                @-webkit-keyframes beta-glow {
                    from {background-color: #FF6600;}
                    to {background-color: #E83F0C;}
                }

                @keyframes beta-glow {
                    from {background-color: #FF6600;}
                    to {background-color: #E83F0C;}
                }
            }
        }
    }

    @media (max-width: 770px) {
        .client-homepage-windshield {
            padding-top: 120px;

            .address-bar-block {
                h1 {
                    font-size: 45px;
                }
            }
        }
    }

    .homepage-wrapper {
        max-width: 1200px;
        margin: 0 auto;

        .perks {
            width: 100%;
            margin-bottom: 60px;

            .perk-1, .perk-2, .perk-3 {
                text-align: center;
                margin-bottom: 25px;

                img {
                    margin: 0 auto;
                    margin-bottom: 15px;
                }
            }
        }

        .product-category-list-container {
            margin: 0 auto 60px auto;
            padding-right: 15px;
            padding-left: 15px;
            width: 95%;
            max-width: 1200px;

            h1 {
                font-size: 34px;
                font-weight: 500;
                margin-bottom: 50px;
                text-align: center;
            }
        }

        .goods-deliver {
            display: flex;
            position: relative;
            justify-content: space-around;
            align-items: center;
            margin-bottom: 80px;
            text-align: justify;

            img {
                max-width: 100%;
                max-height: 323px;
            }

            .gd-content {
                max-width: 525px;

                h3 {
                    font-size: 42px;
                    margin-bottom: 15px;
                }

                p {
                    font-size: 16px;
                    color: #4A4A4A;
                }
            }
        }

        .click-collect {
            display: flex;
            position: relative;
            justify-content: space-around;
            align-items: center;
            margin-bottom: 80px;
            text-align: justify;

            img {
                max-width: 100%;
                max-height: 323px;
            }

            .cc-content {
                max-width: 525px;

                h3 {
                    font-size: 42px;
                    margin-bottom: 15px;
                }

                p {
                    font-size: 16px;
                    color: #4A4A4A;
                }
            }
        }

        @media (max-width: 770px) {
            .goods-deliver {
                flex-direction: column-reverse;
            }

            .click-collect {
                display: inline-block;
            }

            .goods-deliver, .click-collect {
                padding: 0 15px;
                text-align: center;
                width: 100%;

                .gd-content, .cc-content {
                    margin: 0 auto;
                    margin-bottom: 60px;
                    text-align: justify;
                }
            }
        }
    }

    .ready-order {
        width: 100%;
        height: 750px;
        background-color: #FFC936;
        text-align: center;
        margin-bottom: -330px;
        padding: 10px;

        h2 {
            font-size: 44px;
            font-weight: 500;
            color: #000;
            margin-bottom: 20px;
            margin-top: 60px;
        }

        p {
            font-size: 16px;
            font-weight: 400;
            color: #4A4A4A;
            margin: 0 auto;
            margin-bottom: 30px;
            max-width: 415px;
        }
    }

    .vendor-join {
        max-width: 900px;
        min-height: 220px;
        margin: 0 auto;
        padding: 20px 25px;
        background-color: #FFF;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.18);
        position: relative;
        z-index: 9;
        margin-bottom: -100px;

        h3 {
            margin-top: 0;
            font-size: 28px;
            font-weight: 500;
            color: #000;
        }

        p {
            font-size: 16px;
            font-weight: 400;
            color: #737373;
            padding: 15px 0 0 0;
        }

        .vj-cta {
            font-weight: 500;
            color: #4A4A4A;
            padding: 0 0 5px 0;
            margin: 0;
        }

        button {
            text-decoration: none;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;

            a {
                text-decoration: none;
            }

            &:hover {
                a {
                    text-decoration: none;
                }
            }
        }

        .vj-buttons {
            display: flex;
            position: relative;
            justify-content: flex-end;

            .vj-more {
                background-color: #E3E9F6;

                a {
                    color: #5473C7;
                }

                &:hover {
                    background-color: #5473C7;

                    a {
                        color: #E3E9F6;
                    }
                }
            }

            .vj-start {
                background-color: #5DD39E;
                margin-left: 15px;
                box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.18);

                a {
                    color: #F8FCFA;
                    text-transform: uppercase;
                }

                &:hover {
                    background-color: #1FA066;

                    a {
                        color: #F8FCFA;
                    }
                }
            }
        }
    }

    @media (max-width: 770px) {
        .vendor-join {
            margin-left: 10px;
            margin-right: 10px;

            .vj-cta {
                padding: 0 0 25px 0;
            }

            @media (max-width: 570px) {
                .vj-buttons button {
                    width: 50%;
                }
            }
        }
    }

    .homepage-footer {
        background-color: #2D3138;

        .homepage-footer-container {
            max-width: 1200px;
            margin: 0 auto;
            margin-top: 20px;
            padding-top: 120px;

            .col-xs-6 {
                min-height: 200px;
                margin-top: 20px;
            }

            ul {
                text-decoration: none;
                list-style-type: none;
                padding: 0;

                h4 {
                    margin: 0;
                    text-transform: uppercase;
                    color: #FFC936;
                    font-size: 12px;
                    font-weight: 500;
                    letter-spacing: .5px;
                }

                li {
                    padding-top: 17px;

                    a {
                        color: #fff;
                        display: inline-block;
                        cursor: pointer;
                        font-size: 14px;
                        font-weight: 300;
                        margin: 0;

                        &:hover {
                            text-decoration: none;
                        }
                    }
                }
            }

            .f-discover {

                .tasso-footer-logo {

                    min-width: 120px;
                    max-width: 50%;
                    margin-bottom: 40px;
                }

                .social-icons {

                    a {
                        margin-right: 4px;
                    }

                    a:last-child {
                        margin-right: 0;
                    }

                    img {
                        height: 25px;
                    }
                }

                .social-icons:last-child {
                    margin-right: 0;
                }
            }

            .f-partners {
                ul:nth-child(2) h4 {
                    margin-top: 30px;
                }
            }
        }

        .footer-terms-content {
            max-width: 1200px;
            min-height: 40px;
            line-height: 40px;
            margin: 0 auto;
            border-top: solid #8F95A3 1px;
            text-align: center;

            a {
                color:#8F95A3;
                font-size: 12px;
                font-weight: 400;
                text-transform: uppercase;
                padding: 0;
                text-decoration: none;
                cursor: pointer;
            }

            a:first-child {
                text-align: left;
            }

            a:last-child {
                text-align: right;
                cursor: default;
            }

            @media only screen and (max-width: 769px)  {
                a:first-child {
                    text-align: center;
                }
            }
        }
    }
}
</style>
