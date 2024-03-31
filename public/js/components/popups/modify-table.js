import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {Store} from "../../store.js";
import {UserStore} from "../../stores/user-store.js";

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

    delete() {
        console.log("delete")
    }

    render() {
        const isAdmin = UserStore.isAdmin()

        const userList = this.state.users ? this.state.users.map(user => `
            <div class="flex justify-between rounded-md bg-slate-100 px-3 py-1">
                <p class="font-medium">${user.login}</p>
                ${isAdmin ? `
                <i onclick="removeUser" data-login="${user.login}" class="mt-1 text-red-500 cursor-pointer fa-solid fa-trash"></i>` : ""}
            </div>
        `).join("") : ""

        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            ${isAdmin ? `
            <label for="title">Titre</label>
            <input type="text" id="title" value="${Store.get("table")}" name="titretableau" required autofocus>
            <label for="user">Utilisateurs</label>
            <div class="flex relative">
                <input placeholder="ajouter un utilisateur" type="text" id="user" name="user" class="border !rounded-l px-4 py-2 w-full h-9">
                <button class="absolute right-0 border bg-slate-100 text-neutral-900 !rounded-r flex items-center h-9 px-4 py-2 my-2">
                    <i class="fas fa-search"></i>
                </button>
            </div>` : ""}
            ${userList}
            ${isAdmin ? `<button type="submit" class="mt-5">Modifier</button>` : ""}
            ${!isAdmin ? `
                <button class="w-full !bg-red-100 !text-red-500 h-9 font-medium rounded-md text-sm" onclick="leave">Quitter</button>` : `
                <button class="w-full !bg-red-100 !text-red-500 h-9 font-medium rounded-md text-sm" onclick="delete">Supprimer</button>
                `}
        </form>
        `)
    }
}