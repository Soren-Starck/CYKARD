export class Store {
    static data = {}
    static subscribers = {}

    static set(key, value) {
        Store.data[key] = value
        if (Store.subscribers[key] && Store.data[key]) {
            Store.subscribers[key].forEach(callback => callback(value))
        }
    }

    static get(key) {
        return Store.data[key]
    }

    static getByIndex(key, index) {
        return Store.data[key][index]
    }

    static clear(key) {
        Store.data[key] = {}
    }

    static subscribe(key, callback) {
        if (!Store.subscribers[key]) Store.subscribers[key] = []
        Store.subscribers[key].push(callback)
        if (Store.data[key]) callback(Store.data[key])
    }
}