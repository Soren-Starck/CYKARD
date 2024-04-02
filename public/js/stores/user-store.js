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
        me.role = me.role.replace(/[^a-zA-Z_]/g, "")
        return me.role === "USER_ADMIN"
    }

    static roleToText(role) {
        role = role.replace(/[^a-zA-Z_]/g, "")
        switch (role) {
            case "USER_READ":
                return "Lecture seule"
            case "USER_EDITOR":
                return "Lecture et écriture"
            case "USER_ADMIN":
                return "Administrateur"
        }
    }

    static isMe(login) {
        const me = Store.get("me")
        if (!me) return false
        me.role = me.role.replace(/[^a-zA-Z_]/g, "")
        return me.login === login
    }

    static changeRole(login, role) {
        let users = Store.get("users")
        users = users.map(u => {
            if (u.login !== login) return u
            u.role = role.replace(/[^a-zA-Z_]/g, "")
            return u
        })
        Store.set("users", users)
    }
}