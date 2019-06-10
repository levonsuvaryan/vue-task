export default class Auth {

    constructor () {
        this._access_token = this._loadAccessToken();

        if (this.isAuthenticated()) {
            this._addAccessTokenToAxiosHeaders();
        }
    }

    isAuthenticated () {
        return this._access_token !== null
    }

    isGuest () {
        return this._access_token === null;
    }

    login (accessToken) {
        if (accessToken && this.isGuest()) {
            this._saveAccessToken(accessToken);
            this._addAccessTokenToAxiosHeaders();
        }
    }

    logout () {
        if (this.isAuthenticated()) {
            this._removeAccessToken();
            this._deleteAccessTokenFromAxiosHeaders();
        }
    }

    _loadAccessToken () {
        return localStorage.getItem('access_token') || null;
    }

    _saveAccessToken (token) {
        this._access_token = token;
        localStorage.setItem('access_token', token);
    }

    _removeAccessToken () {
        this._access_token = null;
        localStorage.removeItem('access_token');
    }

    _addAccessTokenToAxiosHeaders () {
        window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this._access_token;
    }

    _deleteAccessTokenFromAxiosHeaders () {
        delete window.axios.defaults.headers.common["Authorization"];
    }

}


