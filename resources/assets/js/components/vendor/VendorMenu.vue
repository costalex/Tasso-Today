<template>
    <nav class="vendor-sidenav">
        <ul>
            <li>
                <a>
                    <img
                        id="tasso-logo"
                        alt="Logo Tasso"
                        title="Logo Tasso"
                        src="/storage/bobby_images/icons/tasso-symbol-yellow.svg"
                    >
                </a>
            </li>

            <li
                :class="{'active-entry': $route.name === 'vendorProduct' }"
                @click="$router.push({ name: 'vendorProduct' })"
            >
                <span class="entry">
                    <a id="Produits" />
                </span>

                <p>Produits</p>
            </li>

            <li
                :class="{'active-entry': $route.name === 'vendorSections' }"
                @click="$router.push({ name: 'vendorSections' })"
            >
                <span class="entry">
                    <a id="Rayons" />
                </span>

                <p>Rayons</p>
            </li>

            <li
                id="order-notification"
                :class="{'active-entry': $route.name === 'vendorOrders' }"
                @click="$router.push({ name: 'vendorOrders' })"
            >
                <span class="entry">
                    <a id="Commandes" />
                </span>

                <p>Commandes</p>

                <div
                    v-if="num_attente > 0"
                    class="notification-number"
                >
                    <p>{{ num_attente }}</p>
                </div>
            </li>

            <li
                :class="{'active-entry': $route.name === 'vendorFaq' }"
                @click="$router.push({ name: 'vendorFaq' })"
            >
                <span class="entry">
                    <a id="Aide" />
                </span>
            </li>
        </ul>
    </nav>
</template>
<script>
export default {
    name: 'VendorMenu',
    computed: {
        num_attente: {
            get () {
                let num = 0;
                let commande = this.$store.getters.getCommande;
                for (let i in commande) {
                    if (commande[i].statut === 'EN_ATTENTE') {
                        num++;
                    }
                }

                return num;
            }
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';

.vendor-sidenav {
    min-width: 90px;
    height: 100%;
    background-color: #1D2531;
    padding-top: 20px;
    position: fixed;
    z-index: 20;
    top: 0;
    left: 0;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;

    ul {
        list-style-type: none;
        padding: 0;
        text-align: center;
        background-color: #1D2531;
        position: relative;
        height: 100%;

        li {
            a {
                img#tasso-logo {
                    width: 40px;
                    margin-bottom: 32px;
                }
            }
        }

        li:not(:first-child) {
            height: auto;
            display: flex;
            position: relative;
            justify-content: center;
            align-items: center;
            margin: 10% 0;
            cursor: pointer;
            flex-direction: column;

            .entry {
                width: 46px;
                height: 46px;

                a {
                    height: 100%;
                    width: 100%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    text-decoration: none;
                }

                a#Produits {
                    background-image: url(/storage/bobby_images/icons/products-icon.svg);
                    background-repeat: no-repeat;
                    background-position: center;
                }

                a#Rayons {
                    background-image: url(/storage/bobby_images/icons/aisles-icon.svg);
                    background-repeat: no-repeat;
                    background-position: center;
                }

                a#Commandes {
                    background-image: url(/storage/bobby_images/icons/orders-icon.svg);
                    background-repeat: no-repeat;
                    background-position: center;
                }

                a#Aide {
                    background-image: url(/storage/bobby_images/icons/help-icon.svg);
                    background-repeat: no-repeat;
                    background-position: center;
                }

                a.active-entry {
                    background-color: #DDE4E9;
                    border-radius: 2px;
                }
            }

            p {
                margin: 0;
                margin-top: 4px;
                color: #99A5B2;
                text-transform: uppercase;
                font-size: 10px;
                font-weight: 300;
            }
        }

        li:not(:first-child).active-entry {

            .entry {

                a {
                    background-color: #DDE4E9;
                    border-radius: 2px;
                }

                a#Rayons {
                    background-image: url(/storage/bobby_images/icons/aisles-icon-hover.svg);
                    background-repeat: no-repeat;
                    background-position: center;
                }

                a#Commandes {
                    background-image: url(/storage/bobby_images/icons/orders-icon-hover.svg);
                    background-repeat: no-repeat;
                    background-position: center;
                }

                a#Produits {
                    background-image: url(/storage/bobby_images/icons/products-icon-hover.svg);
                    background-repeat: no-repeat;
                    background-position: center;
                }

                a#Aide {
                    background-image: url(/storage/bobby_images/icons/help-icon-hover.svg);
                    background-repeat: no-repeat;
                    background-position: center;
                }
            }

            p {
                color: #DDE4E9;
            }
        }

        li:not(:first-child).active-entry:before {
            content: "";
            position: absolute;
            left: 0;
            top: 18px;
            width: 5px;
            height: 10px;
            background-color: rgb(255, 255, 255);
            border-radius: 0 4px 4px 0;
        }

        li:not(:first-child):hover .entry {
            background-color: #2C343F;
            border-radius: 2px;
        }

        li#order-notification {

            .notification-number {
                position: absolute;
                height: 22px;
                width: 22px;
                top: -4px;
                right: 18px;
                background-color:#D0021B;
                border-radius: 100%;

                p {
                    font-size: 14px;
                    font-weight: 400;
                    color: #FFFFFF;
                    margin-top: 2px;
                }
            }
        }

        li:last-child {
            position: absolute;
            bottom: 15px;
            width: 100%;
            margin: 0;
        }

        @media screen and (max-height: 380px) {
            li:last-child {
                position: static;
            }
        }
    }
}
</style>
