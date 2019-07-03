import NotificationTypes from '@/class/NotificationTypes';

export default class Notification {
    constructor (id, type, messages, duration = null) {
        this.id = id;
        this.type = type;

        if (Array.isArray(messages)) {
            this.messages = messages;
        } else {
            this.messages = [messages];
        }

        if (duration === null) {
            this.setDurationFromType(type);
        } else {
            this.duration = duration;
        }
    }

    setDurationFromType (notificationType) {
        const types = new NotificationTypes();
        let duration = 5000;

        switch (notificationType) {
            case types.SUCCESS:
                duration = 4000;
                break;
            case types.INFO:
                duration = 7000;
                break;
            case types.WARNING:
                duration = 8000;
                break;
            case types.ERROR:
                duration = 10000;
                break;
        }

        this.duration = duration;
    }
};
