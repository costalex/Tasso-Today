<template>
    <div
        id="AdminMenu"
        class="admin-sidenav"
    >
        <nav class="side-nav">
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
                    :class="{'active-entry': $route.name === 'AdminProduct' || $route.name === 'AdminProductModify' }"
                    @click="$router.push({ name: 'AdminProduct' })"
                >
                    <span class="entry">
                        <a id="Produits" />
                    </span>

                    <span class="cartouche">
                        Produits
                    </span>
                </li>

                <li
                    :class="{'active-entry': $route.name === 'ShopListBody' || $route.name === 'AdminVendorRegistration' || $route.name === 'AdminShopsDetails' }"
                    @click="$router.push({ name: 'ShopListBody' })"
                >
                    <span class="entry">
                        <a id="Shops" />
                    </span>

                    <span class="cartouche">
                        Partenaires
                    </span>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    name: 'AdminMenu'
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.admin-sidenav {
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

            span.cartouche {
                visibility: hidden;
                position: absolute;
                display: inline-block;
                left: 90%;
                z-index: 100;
                font-weight: 500;
                font-size: 12px;
                letter-spacing: .5px;
                text-align: center;
                padding: 5px 12px;
                min-width: 80px;
                background-color: rgba(255,255,255,.98);
                color: #191919;
                -webkit-border-radius: 2px;
                -moz-border-radius: 2px;
                -ms-border-radius: 2px;
                -o-border-radius: 2px;
                border-radius: 2px;
                -webkit-box-shadow: 0 3px 6px 0 rgba(52,74,97,.125);
                -moz-box-shadow: 0 3px 6px 0 rgba(52,74,97,.125);
                box-shadow: 0 3px 6px 0 rgba(52,74,97,.125);
            }

            span.cartouche:before {
                content: "";
                height: 0;
                position: absolute;
                width: 0;
                border-right: 4px solid rgba(255,255,255,.98);
                border-top: solid transparent 4px;
                border-bottom: solid transparent 4px;
                margin-top: -5px;
                top: 50%;
                left: -4px;
            }
        }

        li:not(:first-child) {
            height: 48px;
            display: flex;
            position: relative;
            justify-content: center;
            align-items: center;
            margin: 10% 0;
            cursor: pointer;

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

                a#Shops {
                    background-image: url(/storage/bobby_images/icons/partners-icon.svg);
                    background-repeat: no-repeat;
                    background-position: center;
                }
            }
        }

        li:hover {

            .entry {
                background-color: #2C343F;
                border-radius: 2px;
            }

            .cartouche {
                visibility: visible;
            }
        }

        li.active-entry {

            span.entry {
                background-color: #DDE4E9;
                border-radius: 2px;

                a#Produits {
                    background-image: url(/storage/bobby_images/icons/products-icon-hover.svg);
                    background-repeat: no-repeat;
                    background-position: center;
                }

                a#Shops {
                    background-image: url(/storage/bobby_images/icons/partners-icon-hover.svg);
                    background-repeat: no-repeat;
                    background-position: center;
                }
            }
        }

        li.active-entry:before {
            content: "";
            position: absolute;
            left: 0;
            width: 5px;
            height: 10px;
            background-color: rgb(255, 255, 255);
            border-radius: 0 4px 4px 0;
        }
    }

    .side-nav {
        @include font-size(1.3);
        font-family: $default-font-family;

        ul {
            list-style-type: none;
            margin-top: 15px;
            padding-left: 0;
        }

        li {
            padding-bottom: 20px;
        }

        a {
            color: $secondary-color;
            text-decoration: none;

            &:hover {
                color: $primary-color;
                text-decoration: none;
            }
        }
    }
}
</style>
