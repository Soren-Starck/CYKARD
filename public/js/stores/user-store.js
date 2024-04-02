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

    static roleToText(role) {
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
        return me.login === login
    }

    static changeRole(login, role) {
        let users = Store.get("users")
        users = users.map(u => {
            if (u.login !== login) return u
            u.role = role
            return u
        })
        Store.set("users", users)
    }
}