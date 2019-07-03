<template>
    <div class="command-tableau-responsive">
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
                        v-for="(item, index) in columns"
                        :key="index"
                    >
                        <img
                            v-if="item.key === 'path_file_photo_principale'"
                            :src="entry[item.key][0].image_miniature[0]"
                        >

                        <p v-else-if="item.key === 'stocks'">
                            {{ entry[item.key].model }}
                        </p>

                        <div
                            v-else-if="item.key === 'couleur'"
                            :style="'backgroundColor:' + entry['stocks'].couleur.code_hexa + ';'"
                        >
                            couleur
                        </div>

                        <p v-else-if="item.key === 'prix'">
                            {{ ((entry['stocks'].prix * entry['quantite']).toFixed(2).replace('.', ',' )) + '€' }}
                        </p>

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
export default {
    name: 'OrderTable',
    filters: {
        capitalize: function (str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    },
    props: {
        typeligne: { type: String, required: true },
        data: { type: Array, required: true },
        columns: { type: Array, required: true },
        filterKey: { type: String, required: true }
    },
    data: function () {
        const sortOrders = {};
        this.columns.forEach((key) => {
            sortOrders[key] = 1;
        });

        return {
            sortKey: '',
            sortOrders: sortOrders
        };
    },
    computed: {
        filteredData: function () {
            var sortKey = this.sortKey;
            var filterKey = this.filterKey && this.filterKey.toLowerCase();
            var order = this.sortOrders[sortKey] || 1;
            var data = this.data;

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
        sortBy: function (key) {
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.command-tableau-responsive {
    .table {
        border: 1px solid $grey;
        border-top-style: none;
        margin: 0;
    }

    thead {
        tr {
            th {
                @include font-size(1.2);
                border-bottom: none;
                color: $dark-grey;
                font-family: $default-font-family;
                font-weight: 500;
                padding: 10px;
            }
        }
    }

    tbody {
        tr {
            td {
                border-top: none;
                border-bottom: none;
                padding: 10px;
                vertical-align: baseline;

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
