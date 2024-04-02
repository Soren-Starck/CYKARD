import {API} from "./api.js";

const events = {}

async function fetchData(route) {
    const data = await API.get(route)
    events[route].data = data
    return data
}

/**
 * @param route {string}
 * @param callback {function}
 * @param timeout {number}
 */
export async function fetcher(route, callback, timeout = 0) {
    if (events[route])
        events[route].callbacks.push(callback)
    else
        events[route] = {callbacks: [callback], data: null}

    if (events[route].data) callback(events[route].data)
    else callCallback(callback, await fetchData(route))
    if (timeout > 0) {
        setInterval(() => {
            mutate(route)
        }, timeout * 1000);
    }
}

function callCallback(callback, data) {
    if (data) callback(data)
}

export function mutate(route) {
    (async () => {
        const data = await fetchData(route)
        if (!data) return
        for (const cb of events[route].callbacks)
            cb(data)
    })()
}