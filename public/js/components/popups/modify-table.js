import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {Store} from "../../store.js";
import {UserStore} from "../../stores/user-store.js";
import {Notif} from "../../notifications.js";

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
        API.update(`/tableau/${this.props.table}/modify-name`, data)
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

    copyToClipboard(e) {
        e.preventDefault();
        navigator.clipboard.writeText("lien d'invitation");
        Notif.success("Copié", "Le lien d'invitation a été copié dans le presse-papier");
    }

    render() {
        const isAdmin = UserStore.isAdmin()

        const userList = this.state.users ? this.state.users.map(user => `
            <div class="flex justify-between rounded-md bg-slate-100 px-3 py-1">
                <div class="flex flex-col">
                    <p class="font-medium whitespace-nowrap">
                    ${user.login}</p>
                    <span class="text-xs">${UserStore.roleToText(user.role)}</span>
                </div>
                ${isAdmin && !UserStore.isMe(user.login) ? `
                <i onclick="removeUser" data-login="${user.login}" class="mt-1 text-red-500 cursor-pointer fa-solid fa-trash"></i>` : ""}
            </div>
        `).join("") : ""

        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            ${isAdmin ? `
            <label for="title">
            <i class="fas fa-pencil-alt"></i>
            Titre</label>
            <input type="text" id="title" value="${Store.get("table")}" name="titretableau" required autofocus>
            <button type="submit">
            <i class="fas fa-save"></i>
            Appliquer</button>` : ""}
        </form>
        
        <div class="flex flex-col gap-2">
            ${isAdmin ? `
                <label for="user">
                <i class="fas fa-user"></i>
                Utilisateurs</label>
                <div class="flex relative">
                    <input placeholder="ajouter un utilisateur" type="text" id="user" name="user" class="border !rounded-l px-4 py-2 w-full h-9">
                    <button class="absolute right-0 border bg-slate-100 text-neutral-900 !rounded-r flex items-center h-9 px-4 py-2 my-2">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            ` : ""}
            ${userList}
            
            <button onclick="copyToClipboard" class="btnPrimary mt-3">
            <i class="fas fa-copy"></i>
            Partager
            </button>
            
            ${!isAdmin ? `
                <button class="w-full !bg-red-100 !text-red-500 h-9 font-medium rounded-md text-sm" onclick="leave">
                <i class="fas fa-sign-out-alt"></i>
                Quitter</button>` : `
                <button class="w-full !bg-red-100 !text-red-500 h-9 font-medium rounded-md text-sm" onclick="delete">
                <i class="fas fa-trash"></i>
                Supprimer</button>
                `}
        </form>
        `)
    }
}