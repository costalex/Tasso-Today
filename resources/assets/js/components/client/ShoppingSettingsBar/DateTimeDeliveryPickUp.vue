<template>
    <div class="delivery-pickup-container">
        <div class="delivery-pickup-legend-container">
            <p class="delivery-pickup-legend">
                Être livré - Retirer :
            </p>
        </div>

        <div class="delivery-pickup-date-picker-container">
            <span class="icon-time">
                <date-picker
                    v-model="selectedDate"
                    :config="config"
                    class="date-picker"
                    name="date"
                    @dp-change="datePickerChange"
                />
            </span>

            <p class="delivery-time-end">
                - {{ deliveryTimeEnd }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'DateTimeDeliveryPickUp',
    props: {
        selectedDate: { type: Date, required: true },
        config: { type: Object, required: true },
        deliveryTimeEnd: { type: String, required: true }
    },
    methods: {
        datePickerChange (event) {
            this.$emit('date-picker-change', event);
        }
    }
};
</script>

<style lang="scss">
.bootstrap-datetimepicker-widget {
    .separator { /* les deux points entre heure et minute*/

    }
    .dow {  /*classe des jours (lundi, mardi ...)*/
        cursor: default;
    }
    .day {
        background-color:#5DD39E;
        color:#F8FCFA;
        cursor: pointer;
        text-align: center;
    }
    .day:hover {
        background-color:#1785FB;
    }
    .hour {
    }
    .minute {
    }
    .active {
        background-color: #1B5CFF;
    }
    .glyphicon-chevron-up { /*fleche au dessu de heure et minute*/

    }
    .glyphicon-chevron-down { /*fleche en dessou de heure et minute*/

    }
    .timepicker-minute { /* les minute du time picker*/ /* les heures du time picker */

    }
    .timepicker-hour, .timepicker-minute {
        display: flex;
        position: relative;
        justify-content: center;
        align-items: center;
        cursor:not-allowed;
        pointer-events: none;
    }
    .glyphicon-time { /*pendule en bas*/
        cursor:pointer;
    }
    .glyphicon-remove { /*croix du bas*/
        cursor:pointer;
    }
    .picker-switch {
        cursor:default;
        pointer-events: none;
        text-align: center;
    }
    .disabled { /*element désactivé*/
        color:#737373;
        background-color:#FFF;
        cursor:not-allowed;
    }
    .disabled:hover {
        background-color:#FFF;
    }

    .accordion-toggle {
        cursor:pointer;
        pointer-events: initial;
    }
    .prev { /* "<" prev */
        background-color:#FFF;
        cursor:pointer;
    }
    .next { /* ">" next */
        background-color:#FFF;
        cursor:pointer;
    }
    .month {
    }
}
</style>

<style lang="scss" scoped>
@import '~sass/_variables';

.delivery-pickup-container {
    width: 100%;
    text-align: left;
    padding: 0 5px 0 10px;

    .delivery-pickup-legend-container {
        .delivery-pickup-legend {
            margin: 0;
            padding-right: 10px;
            text-transform: uppercase;
            font-weight: 500;
            font-size: 12px;
            color: $primary-color;
        }
    }

    .delivery-pickup-date-picker-container {
        display: inline-flex;
        align-items: center;
        max-height: 20px;

        p {
            margin: 0;
        }

        .icon-time {
            display: flex;
            align-items: center;
            max-height: 26px;
            max-width: 125px;
            z-index: 10;

            .form-control {
                color: $black;
                width: auto;
                height: 20px;
                margin: 0;
                padding: 0;
                border: none;
                background-color: #ff88ee00;
                box-shadow: none;
                cursor: pointer;
            }

            .date-picker {
                margin: 0 auto;
            }
        }

        .delivery-time-end {
            margin-left: -6px !important;
        }

        @media only screen and (max-width: 373px) {
            .delivery-time-end {
                display: none;
            }
        }
    }

    @media only screen and (max-width: 395px) {
        padding-left: 10px;
    }

    @media only screen and (max-width: 767px) {
        padding: 0 0 0 10px;
    }
}
</style>
