export class Store {
    static data = {}
    static subscribers = {}
    static subscribersOnce = {}

    static set(key, value) {
        Store.data[key] = value
        this._emit(key)
    }

    static _emit(key) {
        if (Store.subscribers[key] && Store.data[key])
            Store.subscribers[key].forEach(callback => callback(Store.data[key]))

        if (Store.subscribersOnce[key] && !Store.data[key]) {
            Store.subscribersOnce[key].forEach(callback => callback(Store.data[key]))
            Store.subscribersOnce[key] = []
        }
    }

    static get(key) {
        return Store.data[key]
    }

    static getByIndex(key, index) {
        return Store.data[key][index]
    }

    static removeByIndex(key, index) {
        const copy = {...Store.data[key]}
        delete copy[index]
        Store.set(key, copy)
    }

    static clear(key) {
        Store.data[key] = {}
    }

    static subscribe(key, callback) {
        if (!Store.subscribers[key]) Store.subscribers[key] = []
        Store.subscribers[key].push(callback)
        if (Store.data[key]) callback(Store.data[key])
    }

    static subscribeOnce(key, callback) {
        if (!Store.subscribersOnce[key]) Store.subscribersOnce[key] = []
        Store.subscribersOnce[key].push(callback)
        if (Store.data[key]) callback(Store.data[key])
    }
}