import CookiesCrous from "./cookies-crous.js";
import {API} from "./api.js";

const events = {}

async function getData(route) {
    const cache = CookiesCrous.get("fetch_" + route);
    if (cache) return JSON.parse(cache);
    const data = await API.get(route)
    CookiesCrous.set("fetch_" + route, JSON.stringify(data));
    return data;
}

/**
 * @param route {string}
 * @param callback {function}
 * @param timeout {number}
 */
export function fetcher(route, callback, timeout = 0) {
    document.addEventListener("fetch" + route, async () => {
        try {
            const data = await getData(route);
            callback(data)
        } catch (e) {
            console.log(e)
        }
    });
    events[route] = true
    callEvent(route)
    if (timeout > 0) {
        setTimeout(() => {
            mutate(route)
        }, timeout * 1000);
    }
}

function callEvent(route) {
    document.dispatchEvent(new CustomEvent("fetch" + route));
}

export function mutate(route) {
    CookiesCrous.delete("fetch_" + route)
    callEvent(route);
}

window.addEventListener('focus', () => {
    for (const event of Object.keys(events))
        mutate(event)
});