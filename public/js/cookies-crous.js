export default class CookiesCrous {
    static get(name) {
        const cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            if (cookie.startsWith(name + '=')) {
                return cookie.substring(name.length + 1, cookie.length);
            }
        }
        return null;
    }

    static set(name, value, duration) {
        let expirationDate = '';
        if (duration) {
            const date = new Date();
            date.setTime(date.getTime() + (duration * 1000));
            expirationDate = ';expires=' + date.toUTCString();
        }
        const secureFlag = location.protocol === 'https:' ? ';secure' : '';
        const domain = window.location.hostname;
        const sameSite = 'Strict';
        document.cookie = name + '=' + value + expirationDate + ';path=/;domain=' + domain + ';SameSite=' + sameSite + secureFlag;
    }

    static exists(name) {
        return document.cookie.split(';').some(cookie => cookie.trim().startsWith(name + '='));
    }

    static delete(name) {
        document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;domain=' + window.location.hostname;
    }
}