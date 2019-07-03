<template>
    <transition-group
        class="notifications-display"
        name="list"
        tag="div"
    >
        <div
            v-for="notification in notifications"
            :key="notification.id"
            :class="{
                'alert-success': notification.type === notificationTypes.SUCCESS,
                'alert-info': notification.type === notificationTypes.INFO,
                'alert-warning': notification.type === notificationTypes.WARNING,
                'alert-danger': notification.type === notificationTypes.ERROR
            }"
            :style="{
                height: (notification.messages.length * 33) + 'px',
                top: notificationTop + 'px'
            }"
            class="alert"
        >
            <p
                v-for="(message, index) in notification.messages"
                :key="notification.id + '-' + index"
                class="message"
            >
                {{ message }}
            </p>

            <p
                class="close"
                @click="closeNotification(notification.id)"
            >
                &times;
            </p>
        </div>
    </transition-group>
</template>

<script>
import { mapGetters } from 'vuex';
import NotificationTypes from '@/class/NotificationTypes';

export default {
    name: 'NotificationsDisplay',
    data () {
        return {
            notificationTypes: new NotificationTypes()
        };
    },
    computed: {
        ...mapGetters([
            'notifications'
        ]),
        ...mapGetters({
            userType: 'getTypeUser'
        }),
        notificationTop () {
            let top = 10;

            if (this.userType === 'Admin' || this.userType === 'Vendeur') {
                top = 50;
            }

            return top;
        }
    },
    methods: {
        closeNotification (notificationId) {
            this.$store.dispatch('REMOVE_NOTIFICATION', notificationId);
        }
    }
};
</script>

<style lang="scss">
.notifications-display {
    position: fixed;
    width: 50%;
    max-width: 800px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;

    .alert {
        width: 100%;
        position: relative;
        margin-bottom: 1px;
        padding: 0;

        display: inline-block;
        margin-right: 10px;

        .message {
            padding-top: 5px;
            padding-left: 10px;
        }

        .close {
            position: absolute;
            top: 1px;
            right: 0;
            margin-right: 10px;
        }
    }

    .list-enter-active,
    .list-leave-active {
        transition: all 0.5s;
    }

    .list-enter, .list-leave-to {
        opacity: 0;
    }

    .list-enter {
        transform: translateY(-20px);
    }

    .list-leave-to {
        transform: translateY(20px);
    }
}
</style>
