export default class Errors {

    constructor () {
        this.errors = {};
    }

    put (errorResponseData) {

        if (typeof errorResponseData.errors === 'object') {
            this.errors = errorResponseData.errors;
        }
    }

    has (field) {
        return this.errors.hasOwnProperty(field);
    }

    first (field) {
        return this.has(field) ? this.errors[field][0] : '';
    }

    forget () {
        this.errors = {};
    }

}