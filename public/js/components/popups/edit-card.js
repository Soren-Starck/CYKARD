import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {ColumnStore} from "../../stores/column-store.js";
import {Store} from "../../store.js";
import {UserStore} from "../../stores/user-store.js";

export class EditCard extends Popup {
    submit(e) {
        const action = e.submitter.name
        const data = API.formHandler(e)
        if (action === "delete") {
            API.remove(`/carte/${this.props.card_id}/delete`)
            ColumnStore.deleteCard(this.props.column_id, this.props.card_id)
        } else {
            API.update(`/carte/${this.props.card_id}/modify`, data)
            ColumnStore.modifyCard(this.props.column_id, this.props.card_id, (card) => {
                card.titrecarte = data.titrecarte
                card.descriptifcarte = data.descriptifcarte
                card.couleurcarte = data.couleurcarte
                return card
            })
        }
        this.close()
    }

    assign() {
        const assigned = document.getElementById("assign-select").value
        API.post(`/carte/${this.props.card_id}/assign-user`, {userslogin: assigned})
        ColumnStore.modifyCard(this.props.column_id, this.props.card_id, (card) => {
            card.user_carte_login = assigned
            return card
        })
    }

    render() {
        const users = Store.get("users") ?? []
        const canModify = UserStore.canModify()

        const userList = users.map(user => `
        <option value="${user.login}">${user.login}</option>
        `).join("")

        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <label for="title">
            <i class="fas fa-pencil-alt"></i>
            Titre</label>
            <input type="text" id="title" name="titrecarte" value="${this.props.title}" required autofocus>
            <label for="description">
            <i class="fas fa-align-left"></i>
            Description</label>
            <textarea id="description" name="descriptifcarte">${this.props.description}</textarea>
            
            <label class="whitespace-nowrap">
            <i class="fas fa-user"></i>
            Utilisateur assigné</label>
            <div class="flex flex-col md:flex-row gap-1 w-full">
                ${this.props.assigned === "null" ? (canModify ? `
                   <div class="w-full flex flex-col md:flex-row gap-4">
                       <select id="assign-select" class="w-full !my-0">
                         ${userList}
                        </select>
                       <button onclick="assign" class="btnSecondary w-full" type="button">
                       <i class="fas fa-user-plus"></i>
                       Assigner</button>
                    </div>` : "Aucun utilisateur assigné")
            : `<p class="bg-black text-white px-2 rounded pb-1">${this.props.assigned}</p>`}
            </div>
            
            <label for="color">
            <i class="fas fa-palette"></i>
            Couleur</label>
            <input type="color" id="color" name="couleurcarte" value="${this.props.color}">
            <div class="flex flex-col md:flex-row gap-4 w-full justify-between">
            <button class="w-full whitespace-nowrap" type="submit">
            <i class="fas fa-save"></i>
            Modifier</button>
            <button class="w-full whitespace-nowrap !bg-red-100 !text-red-500" type="submit" name="delete">
            <i class="fas fa-trash"></i>
            Supprimer</button>
            </div>
        </form>
        `)
    }
}