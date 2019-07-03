<template>
    <div id="App">
        <div class="god-wrapper">
            <div class="page-wrapper">
                <NotificationsDisplay />

                <SectionAdmin v-if="TypeUser === 'Admin'" />

                <SectionVendor v-else-if="TypeUser === 'Vendeur'" />

                <SectionClient v-else-if="TypeUser === 'Client' || TypeUser === 'Guest'" />

                <CookieBar/>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import SectionAdmin from '@/sections/SectionAdmin';
import SectionVendor from '@/sections/SectionVendor';
import SectionClient from '@/sections/SectionClient';
import NotificationsDisplay from '@/components/NotificationsDisplay';
import CookieBar from '@/components/CookieBar';

export default {
    name: 'App',
    components: { NotificationsDisplay, SectionAdmin, SectionVendor, SectionClient, CookieBar },
    computed: {
        ...mapState({
            TypeUser: state => state.GeneralModule.TypeUser
        })
    },
    beforeUpdate () {
        this.$store.dispatch('GET_TYPE_USER');
    }
};
</script>

<style lang="scss">
@import '~sass/_variables';
@import '~sass/_mixins';

html {
    overflow: auto;
    height: 100%;
}

body {
    font-family: $default-font-family;
    margin: 0 auto;
    padding: 0;
    height: 100%;
    max-width: none;

    h1,
    h2,
    h3,
    h4,
    p {
        @include font-size($default-size);
        color: $black;
        font-family: $default-font-family;
    }

    /**
     * PADDING - MARGIN
     */
    .no-padding {
        padding: 0;
    }

    .no-margin {
        margin: 0;
    }

    /**
     * INPUT BUTTON FOCUS
     */
    input:focus,
    button:focus {
        outline:none;
    }

    /**
     * Cancel scroll to when modal is opened
     */
    &.modal-open {
        overflow: visible;
    }

    /**
     * MODAL
     */
    .modal-header {
        border: none;
    }

    /**
     * SLIDER - ROUND SLIDER
     */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color:#006EF4;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        background-color: #fff;
        bottom: 3px;
        left: 4px;
        content: "";
        height: 14px;
        width: 14px;
        position: absolute;
        transition: .4s;
        -webkit-transition: .4s;
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    /**
     * ROUND SWITCH
     */
    .switch {
        display: inline-block;
        position: relative;
        height: 20px;
        width: 41px;
        margin-top: 4px;
    }

    .switch input {
        display:none;
    }

    input:checked + .slider {
        background-color: $pastel-green;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px $pastel-green;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(19px);
        -ms-transform: translateX(19px);
        transform: translateX(19px);
    }

    /**
     * BTN-CIRCLE
     */
    .btn-circle-sm {
        width: 20px;
        height: 20px;
        line-height: 20px;
        padding:0;
        border-radius: 50%;
    }

    .btn-circle-lg {
        width: 40px;
        height: 40px;
        line-height: 40px;
        padding: 0;
        border-radius: 50%;
    }

    /**
     * LOADER
     */
    .loader {
        animation: spin 2s linear infinite;
        border: 10px solid $light-grey;
        border-top: 10px solid $primary-color;
        border-radius: 50%;
        height: 50px;
        margin-left: 36%;
        width: 50px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /**
     * BLUE-MENU-SELECTED
     */
    .blue-menu-selected {
        color: $primary-color !important;
    }

    /**
     * GOOGLE AUTOCOMPLETE
     */
    .pac-container {
        z-index: 9999 !important;
    }

    /**
     * DROPDOWN
     */
    .dropbutton {
        cursor: pointer;
    }

    .dropdwn:hover img {
        fill: $red;
    }

    .dropdwn:hover .dropbutton {
        fill: $primary-color;
    }

    .dropdwn-content {
        display: block;
        position: absolute;
        background-color: #1D2531;
        width: 140px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        border-radius: 2px;
        text-align: center;
        margin-top: 72px;
        z-index: 10;

        .deconnexion {
            font-family: $default-font-family;
            font-weight: 500;
            color: $red;

            &:hover {
                color: $red;
                font-weight: 600;
            }
        }

        a {
            float: none;
            color: #fff;
            padding: 10px 16px;
            text-decoration: none;
            display: inline-block;
            text-align: left;
            cursor: pointer;
            font-family: $default-font-family;
            font-weight: normal;
            font-size: 14px;

            &:hover {
                color: $primary-color;

            }
        }
    }

    .dropdwn {
        position: relative;

        &:hover {
            display: block;
            z-index: 10;
        }
    }

    /**
     * PANEL
     */
    .panel {
        @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);

        .panel-heading {
            .btn-edit-infos-enseigne {
                @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
                @include font-size(1.2);
                align-items: center;
                align-self: center;
                background-color: $white;
                border-radius: 5px;
                color: $black;
                display: inline-block;
                font-family: $default-font-family;
                font-weight: 500;
                margin: 10px;
                text-align: center;
                text-decoration: none;

                &:hover {
                    @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
                    color: $black;
                }
            }
        }
    }

    /**
     * ENSEIGNE
     */
    .details-enseigne,
    .contact-enseigne {
        padding-top: 10px;
        p {
            font-weight: 500;
        }

        input {
            border: 0;
            border-bottom: 1px solid $grey;
            font-family: $default-font-family;
            font-weight: normal;
        }

        select {
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

    /**
     * BODY CLIENT - MODAL
     */
    #BoddyClient {
        .modal-dialog {
            .modal-content {
                padding: 15px;
                border-radius: 0;
                border: none;

                .modal-header {
                    display: flex;
                    position: relative;
                    justify-content: flex-end;
                    width: 100%;
                    height: 18px;
                    padding: 0;
                    margin-bottom: 25px;

                    button.btn-default {
                        display: flex;
                        position: relative;
                        justify-content: flex-end;
                        background-color: transparent;
                        border: none;
                        margin: 0;
                        padding: 0;

                        &:hover {
                            background-color: #ff88ee00;
                        }

                        img {
                            height: 18px;

                            &:hover {
                                fill: red;
                            }
                        }
                    }
                }

                h2 {
                    display: flex;
                    position: relative;
                    justify-content: center;
                    width: 100%;
                    margin: 0 auto 30px;
                    align-items: center;
                    font-size: 16px;
                }

                .modify-address-explore {
                    width: 90%;
                    margin: 0 auto;

                    .explore-header {
                        display: flex;
                        position: relative;
                        margin: 0 auto;
                        width: 100%;
                        padding-bottom: 12px;
                        border-bottom: 1px solid rgba(217, 219, 224, 0.5) ;

                        h3 {
                            display: flex;
                            position: relative;
                            justify-content: flex-start;
                            margin: 0;
                            width: 100%;
                        }

                        p {
                            display: flex;
                            position: relative;
                            justify-content: flex-end;
                            width: 40%;
                            margin: 0;
                            font-size: .9em;
                        }
                    }

                    .explore-cities {
                        margin-top: 30px;
                        height: 100%;
                        width: 100%;
                        display: inline-block;

                        div ul {
                            list-style-type: none;
                            text-align: left;
                            padding: 0;

                            li {
                                margin-bottom: 25px;
                            }
                        }
                    }
                }
            }

            @media only screen and (max-width: 600px) {
                .modal-dialog {
                    margin: 0;

                    #AddressBar {
                        width: 100%;
                    }

                    .modify-address-explore {
                        width: 100%;
                    }
                }
            }
        }
    }

    /**
     * BODY BOUTIQUE - MODAL
     */
    #BoddyBoutique {
        .boutique-navbar {
            .modal-dialog {
                .modal-content {
                    padding: 15px;
                    border-radius: 0;
                    border: none;

                    .modal-header {
                        display: flex;
                        position: relative;
                        justify-content: flex-end;
                        width: 100%;
                        height: 18px;
                        padding: 0;
                        margin-bottom: 25px;

                        button.btn-default {
                            display: flex;
                            position: relative;
                            justify-content: flex-end;
                            background-color: none;
                            border: none;
                            margin: 0;
                            padding: 0;

                            &:hover {
                                background-color: #ff88ee00;
                            }

                            img {
                                height: 18px;

                                &:hover {
                                    fill: red;
                                }
                            }
                        }
                    }

                    h2 {
                        display: flex;
                        position: relative;
                        justify-content: center;
                        width: 100%;
                        margin: 0 auto 30px;
                        align-items: center;
                        font-size: 16px;
                    }

                    .modify-address-explore {
                        width: 90%;
                        margin: 0 auto;

                        .explore-header {
                            display: flex;
                            position: relative;
                            margin: 0 auto;
                            width: 100%;
                            padding-bottom: 12px;
                            border-bottom: 1px solid rgba(217, 219, 224, 0.5) ;

                            h3 {
                                display: flex;
                                position: relative;
                                justify-content: flex-start;
                                margin: 0;
                                width: 100%;
                            }

                            p {
                                display: flex;
                                position: relative;
                                justify-content: flex-end;
                                width: 40%;
                                margin: 0;
                                font-size: .9em;
                            }
                        }

                        .explore-cities {
                            margin-top: 30px;
                            height: 100%;
                            width: 100%;
                            display: inline-block;

                            div ul {
                                list-style-type: none;
                                text-align: left;
                                padding: 0;

                                li {
                                    margin-bottom: 25px;
                                }
                            }
                        }
                    }
                }

                @media only screen and (max-width: 600px) {
                    .modal-dialog {
                        margin: 0;

                        .modify-address-explore {
                            width: 100%;
                        }
                    }
                }
            }
        }

        #EtagereBoutique {
            .product-tile-container {
                .product-tile {
                    .modal-header {
                        border: none;
                    }
                }
            }

            .modal-dialog {
                .modal-content {
                    padding: 15px;
                    border-radius: 0;
                    border: none;

                    .modal-header {
                        width: 100%;
                        height: 20px;
                        padding: 0;
                        margin-bottom: 25px;
                        display: flex;
                        position: relative;

                        h2 {
                            margin: 0;
                            font-family: "Rubik", Verdana, Sans-serif;
                            font-size: 20px;
                            font-weight: 500;
                            color: #000;
                        }

                        button.btn-default {
                            position: absolute;
                            right: 0;
                            background-color: transparent;
                            border: none;
                            margin: 0;
                            padding: 0;

                            &:hover {
                                background-color: #ff88ee00;
                            }

                            img {
                                height: 18px;
                                width: 18px;

                                &:hover {
                                    fill: red;
                                }
                            }
                        }
                    }

                    .product-infos {
                        button:focus {
                            outline: none;
                            border:none;
                            box-shadow: none;
                        }

                        h4 {
                            font-family: "Rubik", Verdana, Sans-serif;
                            font-size: .8em;
                            font-weight: 500;
                            color: #212121;
                            text-transform: uppercase;
                            margin-bottom: 1.6em;

                        }

                        .product-info-container {
                            margin-top: 30px;

                            #main-picture {
                                margin: auto;
                            }

                            #product-pictures {
                                display: flex;
                                position: relative;
                                justify-content: space-evenly;
                                margin-top: 30px;

                                .sub-img img {
                                    width: 50px;
                                    height: 50px;
                                }
                            }

                            #Information {
                                margin-top: 50px;

                                p.description-product {
                                    font-weight: 200;
                                    font-size: 1em;
                                    line-height: 1.5em;
                                    text-align: justify;
                                    word-wrap: break-word;
                                    color: #737373;
                                }
                            }
                        }

                        @media only screen and (max-width: 600px) {
                            .product-info-container {
                                padding: 0;
                            }
                        }

                        .product-action-container {
                            margin-top: 30px;

                            .price {
                                border-bottom: 1px solid $grey;
                                padding: 0;
                                padding-bottom: 50px;
                                text-align: left;

                                h3 {
                                    @include font-size(1.8);
                                    font-weight: 500;
                                    margin: 0;
                                }
                            }

                            .block-quantite {
                                border-bottom: 1px solid $grey;
                                margin-top: 20px;
                                padding-bottom: 40px;

                                .add-quantity {
                                    display: flex;
                                    position: relative;
                                    justify-content: space-evenly;

                                    .btn-quantite {
                                        @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
                                        height: 30px;
                                        width: 30px;
                                        padding: 0;
                                        border: none;
                                        border-radius: 100%;
                                        background-color: #F7F7F8;
                                        outline: none;
                                        outline-offset: 0;

                                        &:hover {
                                            @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
                                            background-color: #FFF;
                                        }
                                    }

                                    .quantity-number {
                                        display: flex;
                                        position: relative;
                                        justify-content: center;
                                        align-items: center;
                                        min-width: 30px;

                                        p {
                                            margin: 0;
                                        }
                                    }
                                }
                            }

                            .batch-block {
                                margin: 0;
                                margin-top: 20px;

                                .batches-container {
                                    margin-left: -10px;
                                    margin-right: -10px;

                                    .batches {
                                        width: auto;
                                        height: auto;
                                        margin-bottom: 15px;

                                        .btn-lot {
                                            @include font-size(1.2);
                                            @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
                                            border: none;
                                            border-radius: 2px;
                                            min-height: 30px;
                                            background-color: #F7F7F8;

                                            &:hover {
                                                @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
                                                background-color: #FFF;
                                            }

                                            &.model-selected {
                                                background-color: #00CC99;
                                                color: #FFF;
                                            }
                                        }
                                    }
                                }
                            }

                            .add-product-basket {
                                display: flex;
                                position: relative;
                                min-height: 50px;
                                margin: 0;
                                margin-top: 100px;

                                div {
                                    width: 100%;
                                    height: 50px;
                                }

                                .product-info-add-cart {
                                    font-size: 16px;
                                    @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
                                    @include prefix(border-radius, 2px, webkit moz ms o);
                                    height: 50px;
                                    width: 100%;
                                    background-color: #00CC99;
                                    bottom: 0;
                                    color: #F8FCFA;
                                    position: absolute;
                                    left: 0;

                                    &:hover {
                                        @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
                                    }
                                }
                            }

                            @media only screen and (max-width: 600px) {
                                .add-product-basket {
                                    margin-bottom: -15px;
                                    margin-top: 40px;
                                }
                            }
                        }

                        @media only screen and (max-width: 600px) {
                            .product-action-container {
                                padding: 0;
                            }
                        }
                    }
                }
            }

            @media only screen and (max-width: 600px) {
                .modal-dialog {
                    margin: 0;

                    #AddressBar {
                        width: 100%;
                    }

                    .modify-address-explore {
                        width: 100%;
                    }
                }
            }
        }
    }
}
</style>

<style lang="scss" scoped>
#App {
    height: 100%;

    .god-wrapper {
        width: 100%;
        height: 100%;
        position: relative;

        .page-wrapper {
            position: relative;
            height: 100%;
        }
    }
}
</style>
