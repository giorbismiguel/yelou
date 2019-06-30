export default {
    methods: {
        getCurrentPositionUser() {
            let location = {lat: -0.180653, lng: -78.467834}
            if (!navigator.geolocation) {
                return location
            }

            navigator.geolocation.getCurrentPosition(position => {
                location = {lat: position.coords.latitude, lng: position.coords.longitude}
            });

            return location;
        }
    }
};