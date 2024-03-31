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
        const userList = this.state.me && this.state.me.role !== "USER_ADMIN" ? this.state.users.map(user => `
            <div class="flex justify-between rounded-md bg-slate-100 px-3 py-1">
                <p class="font-medium">${user.login}</p>
                ${user.login === this.state.me.login ? "" : `
                <i onclick="removeUser" data-login="${user.login}" class="mt-1 text-red-500 cursor-pointer fa-solid fa-trash"></i>`}
            </div>
        `).join("") : ""

        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <label for="title">Titre</label>
            <input type="text" id="title" value="${Store.get("table")}" name="titretableau" required autofocus>
            <label for="user">Utilisateurs</label>
            <div class="flex relative">
                <input type="text" id="user" name="user" class="border !rounded-l px-4 py-2 w-full h-9">
                <button class="absolute right-0 border bg-slate-100 text-neutral-900 !rounded-r flex items-center h-9 px-4 py-2 my-2">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            ${userList}
            <button type="submit" class="mt-5">Modifier</button>
            ${this.state.me && this.state.me.role !== "USER_ADMIN" ? `
                <button class="w-full !bg-red-100 !text-red-500 h-9 font-medium rounded-md text-sm" onclick="leave">Quitter</button>` : ""}
        </form>
        `)
    }
}