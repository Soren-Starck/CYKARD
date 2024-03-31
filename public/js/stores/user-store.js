import {Store} from "../store.js";

export class UserStore {
    static canModify() {
        const me = Store.get("me")
        if (!me) return false
        return me.role !== "USER_READ"
    }

    static isAdmin() {
        const me = Store.get("me")
        if (!me) return false
        return me.role === "USER_ADMIN"
    }
}