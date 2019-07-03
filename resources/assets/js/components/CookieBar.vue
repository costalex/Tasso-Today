<template>
    <footer>
        <cookie-law
            class="cookie-rgpd"
            position="bottom"
            close-button-text="J'accepte"
            transition-name="slideFromBottom"
            close-button-class="btn"
            @accept="ThankYouMethod"
        >
            <div slot="message">
                <h1>Politique d'utilisation des Cookies 🍪</h1>

                <p>
                    En poursuivant votre navigation, vous acceptez le dépôt de cookies tiers destinés
                    à vous procurer la meilleure expérience en vous proposant des boutiques en fonction
                    de l'adresse de livraison et/ou de retrait saisie.
                </p>

                <button class="cookie-recipe">
                    <a
                        href="https://blog.tasso.today/la-recette-des-cookies-aux-pepites-de-chocolat-de-tasso-serieusement-la-meilleure/"
                        target="_blank"
                    >
                        Recette cookies Tasso
                    </a>
                </button>

                <button class="cookie-know-more">
                    <a
                        slot="buttonLink"
                        href="https://www.tassodelivery.com/politique_confidentialite"
                        target="_blank"
                    >
                        En savoir plus
                    </a>
                </button>
            </div>
        </cookie-law>
    </footer>
</template>

<script>
import CookieLaw from 'vue-bootstrap-cookie-law';

export default {
    components: { CookieLaw },
    methods: {
        ThankYouMethod () {
            this.$store.commit('SET_COOKI_ACCEPTED', true);
        }
    }
};
</script>

<style lang="scss">
@import '~sass/_variables';

footer {
    .cookie-base {
        width: 60% !important;
        padding: 10px 15px !important;
        margin: 0 auto;
        margin-bottom: 25px;
        border-radius: 4px;
        background-color: #FEFEFF !important;
        -webkit-box-shadow: 0 2px 4px 0 rgba(0,0,0,0.18);
        -moz-box-shadow: 0 2px 4px 0 rgba(0,0,0,0.18);
        box-shadow: 0 2px 4px 0 rgba(0,0,0,0.18);

        .cookie-content {
            text-align: justify;

            h1 {
                margin-top: 10px;
                margin-bottom: 20px;
                color: #212121;
                font-weight: 500;
                font-size: 18px;
            }

            p {
                margin-bottom: 25px;
                color: #4a4a4a;
                font-weight: 400;
            }

            .cookie-recipe {
                background: none;
                border: 1px solid #E3E9F6;
                border-radius: 4px;
                padding: 5px 10px;
                float: left;

                a {
                    text-decoration: none;
                    color: #5473C7 ;
                }
            }

            .cookie-recipe:hover {
                background-color: #E3E9F6;
            }

            .cookie-know-more {
                background-color: #E3E9F6;
                padding: 6px 10px;
                border: none;
                border-radius: 4px;
                float: right;
                margin-right: 90px;

                a {
                    text-decoration: none;
                    color: #5473C7;
                }
            }

            .cookie-know-more:hover {
                background-color: #5473C7;

                a {
                    color: #E3E9F6;
                }
            }
        }

        @media only screen and (max-width: 410px) {
            .cookie-content {
                text-align: center;

                h1, p {
                    text-align: justify;
                }

                .cookie-recipe {
                    float: none;
                    margin-bottom: 15px;
                }

                .cookie-know-more {
                    float: left;
                    width: 45%;
                }
            }
        }

        .cookie-buttons {
            clear: both;

            .cookie-button {
                padding: 6px 10px;
                background-color: #5DD39E;
                color: #F8FCFA;
                border-radius: 4px;
                cursor: pointer;
                position: absolute;
                bottom: 0;
                right: 0;
                margin-right: 15px;
                margin-bottom: 10px;
                text-transform: capitalize;
                -webkit-box-shadow: 0 1px 2px 0 rgba(0,0,0,0.18);
                -moz-box-shadow: 0 1px 2px 0 rgba(0,0,0,0.18);
                box-shadow: 0 1px 2px 0 rgba(0,0,0,0.18);
            }

            @media only screen and (max-width: 410px) {
                .cookie-button {
                    width: 45%;
                    clear: both;
                }
            }

            .cookie-button:hover {
                background-color: #1FA066;
                color: #F8FCFA;
            }
        }
    }

    @media only screen and (max-width: 860px) {
        .cookie-base {
            width: 100%;
            margin-bottom: 0;
            border-radius: 0;
        }
    }
}
</style>
