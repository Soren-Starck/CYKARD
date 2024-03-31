import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {Store} from "../../store.js";

export class ModifyTable extends Popup {
    onMount() {
        Store.subscribe("users", (users) => {
            this.setState({
                users
            })
        })

        Store.subscribe("me", (me) => {
            this.setState({me})
        })
    }

    submit(e) {
        const data = API.formHandler(e)
        API.update(`/tableau/${this.props.table}/modify`, data)
        Store.set("table", data.titretableau)
        this.close()
    }

    removeUser(e) {
        const login = e.target.getAttribute("data-login")
        let users = Store.get("users")
        users = users.filter(user => user.login !== login)
        Store.set("users", users)
        const logins = users.map(user => user.login)
        console.log(logins)
        /*API.update(`/tableau/${this.props.table}/modify`, {
            userslogins: logins
        })*/
    }

    leave() {
        console.log("leave")
    }

    render() {
        const userList = this.state.me && this.state.me.role === "USER_ADMIN" ? this.state.users.map(user => `
            <div class="flex justify-between">
                <p>${user.login}</p>
                ${user.login === this.state.me.login ? "" : `
                <i onclick="removeUser" data-login="${user.login}" class="text-red-500 cursor-pointer fa-solid fa-trash"></i>`}
            </div>
        `).join("") : ""

        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <label for="title">Titre</label>
            <input type="text" id="title" value="${Store.get("table")}" name="titretableau" required autofocus>
            ${userList}
            <button type="submit" class="mt-5">Modifier</button>
            ${this.state.me && this.state.me.role !== "USER_ADMIN" ? `
                <button onclick="leave">Quitter</button>` : ""}
        </form>
        `)
    }
}