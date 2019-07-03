<template>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th
                        v-for="(item, index) in columns"
                        :key="index"
                        :class="{ active: sortKey === item.key }"
                        @click="sortBy(item.key)"
                    >
                        {{ item.value | capitalize }}
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="(entry, index) in filteredData"
                    :key="index"
                >
                    <td
                        v-for="item in columns"
                        :key="item.key"
                        class="col-md-2 col-sm-2 col-xs-2"
                    >
                        <img
                            v-if="item.key === 'path_file_photo_principale'"
                            :src="entry[item.key][0].image_miniature[1]"
                            class="img-principale"
                        >

                        <div v-else-if="item.key === 'action'">
                            <div v-if="typeligne === 'Enseigne'">
                                <button
                                    class="btn edit-button"
                                    @click="selecteenseigne(entry)"
                                >
                                    Modifier
                                </button>
                            </div>

                            <div v-else-if="typeligne === 'Produit_enseigne'">
                                <button
                                    class="btn btn-edit-enseigne-product"
                                    @click="selecteproduitenseigne(entry)"
                                >
                                    Modifier
                                </button>
                            </div>

                            <div v-else-if="typeligne === 'Requests'" >
                                <button
                                    class="btn examinate-btn"
                                    @click="selectedemande(entry)"
                                >
                                    Modifier
                                </button>
                            </div>

                            <div v-else-if="typeligne === 'Produit'" >
                                <button
                                    class="btn examinate-btn"
                                    @click="selecteModifProduit(entry)"
                                >
                                    Modifier
                                </button>
                            </div>
                        </div>

                        <div v-else-if="item.key === 'have'">
                            <span
                                v-if="entry[item.key]"
                                class="enable-product"
                                @click="deleteproduit(entry)"
                            >
                                <i class="fa fa-circle fa-2x" />
                            </span>

                            <span
                                v-else
                                class="disable-product"
                                @click="addproduit(entry)"
                            >
                                <i class="fa fa-circle-o fa-2x" />
                            </span>
                        </div>

                        <div v-else-if="typeligne === 'Fond' && item.key === 'active'">
                            <button @click="selecteeactivefond(entry)">cercle</button>
                            <button @click="selecteedeletefond(entry)">croix</button>
                        </div>

                        <template v-else-if="item.key === 'prix' && typeligne === 'Produit_enseigne'">
                            <div
                                v-for="(stock, index) in entry['stocks']"
                                :key="index"
                            >
                                <p v-if="stock.activer && stock.afficher">
                                    {{ ((stock.prix * 1).toFixed(2).replace('.', ',')) + '€/' + stock.model }}
                                </p>
                            </div>
                        </template>

                        <div v-else-if="item.key === 'delete_shop'">
                            <span
                                class="enable-product"
                                data-toggle="modal"
                                data-target="#ModalDeleteObj"
                            >
                                <i class="fa fa-times-circle fa-2x" />
                            </span>

                            <ModalDeleteObj
                                v-show="showModal"
                                id="ModalDeleteObj"
                                :obj="entry"
                                class="modal fade"
                                role="dialog"
                            />
                        </div>

                        <p v-else>
                            {{ entry[item.key] }}
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import ModalDeleteObj from '@/components/admin/PageShopDetails/Products/DeleteProductModal';

export default {
    name: 'Table',
    components: {
        ModalDeleteObj
    },
    filters: {
        capitalize: function (str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    },
    props: {
        typeligne: { type: String, required: true },
        data: { type: Array, required: true },
        columns: { type: Array, required: true },
        filterKey: { type: String, required: true },
        orderKey: { type: String, default: '' }
    },
    data: function () {
        const sortOrders = {};
        this.columns.forEach((key) => {
            sortOrders[key] = 1;
        });

        return {
            sortKey: this.orderKey,
            sortOrders: sortOrders,
            showModal: false
        };
    },
    computed: {
        filteredData: function () {
            const sortKey = this.sortKey;
            const filterKey = this.filterKey && this.filterKey.toLowerCase();
            const order = this.sortOrders[sortKey] || 1;
            let data = this.data;

            if (filterKey) {
                data = data.filter(function (row) {
                    return Object.keys(row).some(function (key) {
                        return String(row[key]).toLowerCase().indexOf(filterKey) > -1;
                    });
                });
            }

            if (sortKey) {
                data = data.slice().sort(function (a, b) {
                    a = a[sortKey];
                    b = b[sortKey];
                    return (a === b ? 0 : a > b ? 1 : -1) * order;
                });
            }

            return data;
        }
    },
    methods: {
        sortBy (key) {
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
        },
        selecteenseigne (data) {
            this.$store.commit('SET_ENSEIGNE_REFERENCE', data['id']);
            this.$store.commit('SET_ENSEIGNE_NAME', data['nom']);

            this.$router.push({
                name: 'AdminShopsDetails',
                params: {
                    shopId: data.id
                }
            });
        },
        selectedemande (data) {
            this.$store.commit('SET_ENSEIGNE_REFERENCE', data['id']);
            this.$store.commit('SET_ENSEIGNE_NAME', data['nom_enseigne']);
        },
        selecteModifProduit (data) {
            this.$store.dispatch('INITIALIZE_INFO_PRODUIT', data.ref_produit)
                .then(() => {
                    this.$router.push({
                        name: 'AdminProductModify',
                        params: {
                            produtId: data.id
                        }
                    });
                });
        },
        selecteedeletefond (data) {},
        selecteeactivefond (data) {},
        addproduit (data) {
            data['have'] = true;
            this.$store.dispatch('SET_ADD_PRODUIT_ENSEIGNE', data['id']);
        },
        deleteproduit (data) {
            data['have'] = false;
            this.$store.dispatch('SET_DELETE_PRODUIT_ENSEIGNE', data['id']);
        },
        selecteproduitenseigne (data) {
            this.$store.commit('SET_MODIF_PRODUIT');
            this.$store.commit('SET_MODIF_PRODUIT_INFO', data);
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.table-responsive {
    .edit-button {
        @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
        @include font-size(1);
        align-items: center;
        align-self: center;
        background-color: $primary-color;
        border-radius: 5px;
        color: $white;
        display: inline-block;
        font-family: $default-font-family;
        font-weight: 400;
        text-align: center;
        text-decoration: none;

        &:hover {
            @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
            color: $white;
        }
    }

    .enable-product,
    .disable-product{
        i {
            cursor: pointer;
        }
    }
}

.table-responsive-admin {
    @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
    border: 1px solid $grey;
    border-radius: 5px;

    .table {
        margin: 0;
    }

    thead {
        background-color: $light-blue;

        tr {
            th {
                @include font-size(1.5);
                border-bottom: 1px solid $grey;
                color: $secondary-color;
                font-family: $default-font-family;
                font-weight: 500;
                padding: 10px 0 10px 0;
                &:last-child {
                    padding-right: 10px;
                }
            }
        }
    }

    tbody {
        tr {
            border-bottom: 1px solid $light-grey;
            td {
                @include font-size(1.3);
                padding: 10px 0 10px 0;
                vertical-align: baseline;

                &:first-child {
                    padding-left: 5px;
                }

                &:last-child {
                    padding-right: 5px;
                }

                .enable-product,
                .disable-product {
                    background-color: $white;
                    border: none;
                    color: $primary-color;
                    padding-left: 15px;

                    i {
                        cursor: pointer;
                    }
                }

                .disable-product {
                    color: $grey;
                }

                .fa-times-circle {
                    cursor: pointer;
                }

                .img-principale {
                    width: 100px;
                    height: 100px;
                }
            }
        }
        p {
            @include font-size(1.4);
            color: $secondary-color;
            font-family: $default-font-family;
        }
    }
}

.table-responsive-vendor {
    @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
    border: 1px solid $grey;
    border-radius: 5px;
    .table {
        margin: 0;
    }

    thead {
        background-color: $light-blue;

        tr {
            th {
                @include font-size(1.5);
                border-bottom: 1px solid $grey;
                color: $secondary-color;
                font-family: $default-font-family;
                font-weight: 500;
                padding: 10px;
            }
        }
    }

    tbody {
        tr {
            border-bottom: 1px solid $light-grey;
            td {
                padding: 10px;
                vertical-align: baseline;

                .btn-edit-enseigne-product {
                    @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
                    @include font-size(1.3);
                    align-items: center;
                    align-self: center;
                    background-color: $primary-color;
                    border-radius: 5px;
                    color: $white;
                    display: inline-block;
                    font-family: $default-font-family;
                    font-weight: 400;
                    text-align: center;
                    text-decoration: none;

                    &:hover {
                        @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
                        color: $white;
                    }
                }

                .img-principale {
                    width: 100px;
                    height: 100px;
                }
            }
        }
        p {
            @include font-size(1.4);
            color: $secondary-color;
            font-family: $default-font-family;
        }
    }
}
</style>
