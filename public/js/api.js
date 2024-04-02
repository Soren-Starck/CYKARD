import CookiesCrous from "./cookies-crous.js";
import {Notif} from "./notifications.js";

const host = window.location.origin + "/api";

function getToken() {
    const jwt = CookiesCrous.get("jwt")
    if (!jwt) return null
    return /"([^"]*)"/g.exec(decodeURI(jwt))[1]
}

async function actions(method, route, data, onError) {
    const jwt = getToken()
    let headers = {}
    if (data) headers["Content-Type"] = "application/json"
    if (jwt) headers["Authorization"] = "Bearer " + jwt
    try {
        const res = await fetch(host + route, {
            method,
            headers,
            ...(data ? {
                body: JSON.stringify(data)
            } : {})
        });
        if (!res.ok && onError) onError(res)
        if (res.status === 204) return null
        return await res.json()
    } catch (e) {
        Notif.error("Erreur", "Une erreur est survenue lors du chargement des données", route + method)
    }
    return null
}

export class API {
    static async get(route, onError) {
        return await actions("GET", route, null, onError)
    }

    static async create(route, data, onError) {
        return await actions("POST", route, data, onError)
    }

    static async update(route, data, onError) {
        return await actions("PATCH", route, data, onError)
    }

    static async remove(route, data, onError) {
        return await actions("DELETE", route, data, onError)
    }

    static formHandler(event) {
        event.preventDefault()
        const formData = new FormData(event.target)
        const data = {}
        formData.forEach((value, key) => data[key] = value)
        return data
    }
}

