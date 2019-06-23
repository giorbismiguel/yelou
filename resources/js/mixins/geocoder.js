export default {
    methods: {
        geocodedAddress(coordinates) {
            let geocoder = new google.maps.Geocoder(), formatAddress = null
            geocoder.geocode({'latLng': coordinates}, (result, status) => {
                if (status === google.maps.GeocoderStatus.OK) {
                    formatAddress = result[0].formatted_address
                }
            })

            return formatAddress;
        }
    }
};