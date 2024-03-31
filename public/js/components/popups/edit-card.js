import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {ColumnStore} from "../../stores/column-store.js";
import {Store} from "../../store.js";

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

    render() {
        const users = Store.get("users") ?? []

        const userList = users.map(user => `
        <p class="bg-black text-white px-2 rounded pb-1">${user.login}</p>
        `).join("")

        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <label for="title">Titre</label>
            <input type="text" id="title" name="titrecarte" value="${this.props.title}" required autofocus>
            <label for="description">Description</label>
            <textarea id="description" name="descriptifcarte">${this.props.description}</textarea>
            <p>Utilisateurs assignées</p>
            <div class="flex flex-row gap-1 w-full">
                ${userList}
                <i class="fas fa-plus bg-black text-white p-2 cursor-pointer rounded"></i>
            </div>
            <label for="color">Couleur</label>
            <input type="color" id="color" name="couleurcarte" value="${this.props.color}">
            <div class="flex flex-row gap-4 w-full justify-between">
            <button class="w-full" type="submit">Modifier</button>
            <button class="w-full !bg-red-100 !text-red-500" type="submit" name="delete">Supprimer</button>
            </div>
        </form>
        `)
    }
}