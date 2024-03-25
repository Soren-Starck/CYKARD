import CookiesCrous from "./cookies-crous.js";

const host = "http://localhost:8000/api";

function getToken() {
    const jwt = CookiesCrous.get("jwt")
    if (!jwt) return null
    return /"([^"]*)"/g.exec(decodeURI(jwt))[1]
}

async function actions(method, route, data) {
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
        return await res.json()
    } catch (e) {
        console.log(e)
    }
    return null
}

export class API {
    static async get(route) {
        return await actions("GET", route)
    }

    static async create(route, data) {
        return await actions("POST", route, data)
    }

    static async update(route, data) {
        return await actions("PATCH", route, data)
    }

    static async remove(route) {
        return await actions("DELETE", route)
    }

    static formHandler(id, callback) {
        const form = document.getElementById(id)
        form.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const formDataObject = {};
            for (let [key, value] of formData.entries())
                formDataObject[key] = value;
            callback(formDataObject)
        })
    }
}

