import Notification from '@/class/Notification';

const state = {
    notificationIndex: 0,
    notifications: [],
    notificationsWaiting: [],
    maximumNotifications: 5
};

const getters = {
    notifications: state => state.notifications
};

const mutations = {
    INCREMENT_NOTIFICATION_INDEX (state) {
        state.notificationIndex++;
    },
    SET_NOTIFICATIONS (state, notifications) {
        state.notifications = notifications;
    },
    SET_NOTIFICATIONS_WAITING (state, notifications) {
        state.notificationsWaiting = notifications;
    }
};

const actions = {
    NOTIFY ({ state, commit, dispatch }, { type, message, duration }) {
        commit('INCREMENT_NOTIFICATION_INDEX');

        let notification = null;

        if (duration === undefined) {
            notification = new Notification(state.notificationIndex, type, message);
        } else {
            notification = new Notification(state.notificationIndex, type, message, duration);
        }

        if (state.notifications.length < state.maximumNotifications) {
            dispatch('ADD_NOTIFICATION', notification);
        } else {
            dispatch('ADD_NOTIFICATION_TO_WAITING_QUEUE', notification);
        }
    },
    REMOVE_NOTIFICATION ({ state, commit, dispatch }, notificationId) {
        const notifications = [...state.notifications];

        const notificationIndex =
            notifications.findIndex(notification => notification.id === notificationId);

        if (notificationIndex !== -1) {
            notifications.splice(notificationIndex, 1);
        }

        commit('SET_NOTIFICATIONS', notifications);

        if (state.notificationsWaiting.length > 0) {
            dispatch('SHIFT_NOTIFICATIONS_WAITING')
                .then(notification => {
                    dispatch('ADD_NOTIFICATION', notification);
                });
        }
    },
    ADD_NOTIFICATION ({ state, commit, dispatch }, notification) {
        const notifications = [...state.notifications];

        notifications.unshift(notification);
        commit('SET_NOTIFICATIONS', notifications);

        if (notification.duration !== -1) {
            setTimeout(() => {
                dispatch('REMOVE_NOTIFICATION', notification.id);
            }, notification.duration);
        }
    },
    ADD_NOTIFICATION_TO_WAITING_QUEUE ({ state, commit }, notification) {
        const notifications = [...state.notificationsWaiting];
        notifications.push(notification);
        commit('SET_NOTIFICATIONS_WAITING', notifications);
    },
    SHIFT_NOTIFICATIONS_WAITING ({ state, commit }) {
        return new Promise((resolve) => {
            const notificationsWaiting = [...state.notificationsWaiting];
            const notification = notificationsWaiting.shift();
            commit('SET_NOTIFICATIONS_WAITING', notificationsWaiting);

            resolve(notification);
        });
    }
};

const Notifications = {
    state,
    getters,
    mutations,
    actions
};

export default Notifications;
