<template>
    <div
        :class="{ 'open': openSuggestion }"
        style="position:relative"
    >
        <input
            :placeholder="value_palceholder"
            :value="value"
            class="form-control"
            type="text"
            @input="updateValue($event.target.value)"
            @keydown.enter="enter"
            @keydown.down="down"
            @keydown.up="up"
        >

        <ul
            v-if="activer_choix && matches.length > 1"
            class="dropdown-menu"
            style="width:100%"
        >
            <li
                v-for="(suggestion, index) in matches"
                :key="index"
                :class="{'active': isActive(index)}"
                @click="suggestionClick(index)"
            >
                <a
                    v-if="suggestion.nom && suggestion.nom !== value"
                    href="#"
                >
                    {{ suggestion.nom }}
                </a>

                <a
                    v-if="suggestion.nom_generique && suggestion.nom_generique !== value"
                    href="#"
                >
                    {{ suggestion.nom_generique }}
                </a>
            </li>
        </ul>

        <ul
            v-if="activer_choix && matches.length === 1 && ((matches[0].nom && matches[0].nom !== value) || (matches[0].nom_generique && matches[0].nom_generique !== value))"
            class="dropdown-menu"
            style="width:100%"
        >
            <li
                :class="{'active': isActive(0)}"
                @click="suggestionClick(0)"
            >
                <a
                    v-if="matches[0].nom && matches[0].nom !== value"
                    href="#"
                >
                    {{ matches[0].nom }}
                </a>

                <a
                    v-if="matches[0].nom_generique && matches[0].nom_generique !== value"
                    href="#"
                >
                    {{ matches[0].nom_generique }}
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        value: { type: String, required: true },
        suggestions: { type: Array, required: true },
        value_palceholder: { type: String, required: false, default: '' },
        activer_choix: { type: Boolean, required: false, default: true }
    },
    data () {
        return {
            open: false,
            current: 0
        };
    },
    computed: {
        matches () {
            let matches = [];

            if (Array.isArray(this.suggestions)) {
                matches = this.suggestions.filter((obj) => {
                    if (obj.nom) {
                        return obj.nom.indexOf(this.value) >= 0;
                    } else {
                        return obj.nom_enseigne.indexOf(this.value) >= 0;
                    }
                });
            }

            return matches;
        },
        openSuggestion () {
            return this.matches.length !== 0 && this.open;
        }
    },
    methods: {
        updateValue (value) {
            if (!this.open) {
                this.open = true;
                this.current = 0;
            }
            this.$emit('input', value);
        },
        enter () {
            this.$emit('input', this.matches[this.current].nom);
            this.open = false;
        },
        up () {
            if (this.current > 0) {
                this.current--;
            }
        },
        down () {
            if (this.current < this.matches.length - 1) {
                this.current++;
            }
        },
        isActive (index) {
            return index === this.current;
        },
        suggestionClick (index) {
            this.$emit('input', this.matches[index].nom);
            this.open = false;
        }

    }
};
</script>
