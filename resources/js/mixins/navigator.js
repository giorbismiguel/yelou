export default {
    methods: {
        getCurrentPositionUser(options = {}) {
            return new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, options);
            });
        }
    }
};