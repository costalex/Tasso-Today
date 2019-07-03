<template>
    <div id="ModifProduitParametre">
        <div class="panel panel-default col-md-12 col-sm-12 col-xs-12 no-padding">
            <div
                class="panel-heading col-md-12 col-sm-12 col-xs-12 no-padding"
                style="padding-left: 15px;"
            >
                <h4>Paramètres</h4>
            </div>

            <div class="panel-body col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                    <p>Produit affiché</p>
                    <select v-model="obj_select">
                        <option
                            v-for="produit in ProduitStockActiver"
                            :key="produit.id"
                            :value="produit"
                        >
                            {{ produit.id }}
                        </option>
                    </select>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                    <p>Modèle</p>
                    {{ obj_select.model }}
                </div>

                <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                    <p>Prix TTC</p>
                    {{ (obj_select.prix * 1).toFixed(2).replace('.', ',') }}€
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ModifyProductBodyParameter',
    data: () => {
        return {
            obj_stock: [],
            obj_select: ''
        };
    },
    computed: {
        ProduitStockActiver () {
            let sameStock = false;

            let stockSave = [];
            for (let i = 0; i < this.obj_stock.length; i++) {
                if (this.obj_stock[i].activer) {
                    stockSave.push(this.obj_stock[i]);
                    if (this.obj_select === this.obj_stock[i]) {
                        sameStock = true;
                    }
                } else {
                    this.obj_stock[i].afficher = false;// eslint-disable-line vue/no-side-effects-in-computed-properties
                }
            }

            if (!sameStock || stockSave.length === 0) {
                this.obj_select = '';// eslint-disable-line vue/no-side-effects-in-computed-properties
            }

            if (stockSave[0] && !this.obj_select) {
                stockSave[0].afficher = true;
                this.obj_select = stockSave[0];// eslint-disable-line vue/no-side-effects-in-computed-properties
            }

            return stockSave;
        }
    },
    watch: {
        obj_select: function (value) {
            for (let i = 0; i < this.obj_stock.length; i++) {
                if (value) {
                    this.obj_stock[i].afficher =
                        this.obj_stock[i].id === value.id && this.obj_stock[i].activer;
                }
            }

            this.$store.commit('SET_MODIF_PRODUIT_DATA', this.obj_stock);
        }
    },
    mounted: function () {
        let select = false;
        this.obj_stock = this.$store.getters.getProduitStock;
        for (let i = 0; i < this.obj_stock.length; i++) {
            if (this.obj_stock[i].afficher && this.obj_stock[i].activer) {
                this.obj_select = this.obj_stock[i];
                select = true;
            }
        }

        if (!select) {
            for (let i = 0; i < this.obj_stock.length; i++) {
                if (this.obj_stock[i].activer) {
                    this.obj_select = this.obj_stock[i];
                    break;
                }
            }
        }
    }
};
</script>
