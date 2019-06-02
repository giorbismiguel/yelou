module.exports = {
    stringify(params) {
        const stringify = Object.keys(params)
            .filter(key => params[key] !== null & params[key] !== '')
            .map((key) => {
                if (typeof params[key] === 'object' && params[key]) {
                    return this.objectToArray(key, params[key])
                }

                return encodeURIComponent(key) + '=' + encodeURIComponent(params[key])
            }).join('&');

        return stringify.endsWith('&') ? stringify.slice(0, -1) : stringify;
    },

    objectToArray(param, object) {
        return Object.keys(object)
            .filter(key => object[key] !== null & object[key] !== '')
            .map(key => {
                return encodeURIComponent(`${param}[${key}]`) + '=' + encodeURIComponent(object[key])
            }).join('&')
    }
}
