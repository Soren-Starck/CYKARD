import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {ColumnStore} from "../../stores/column-store.js";
import {Store} from "../../store.js";
import {UserStore} from "../../stores/user-store.js";

export class EditCard extends Popup {
    onMount() {
        Store.subscribe("columns", () => {
            this._render()
        })
    }

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

    unassign() {
        API.remove(`/carte/${this.props.card_id}/unassign-user`)
        ColumnStore.modifyCard(this.props.column_id, this.props.card_id, (card) => {
            card.user_carte_login = null
            return card
        })
    }

    selectColor(event) {
        const color = event.target.getAttribute('data-color');
        document.getElementById('color').value = color;
        const colorCircles = event.target.parentElement.children;
        for (let i = 0; i < colorCircles.length; i++) {
            colorCircles[i].classList.remove('selected');
            if (colorCircles[i].getAttribute('data-color') === color) {
                colorCircles[i].classList.add('selected');
            }
        }
    }

    render() {
        const users = Store.get("users") ?? []
        const canModify = UserStore.canModify()
        const assigned = ColumnStore.getAssigned(this.props.column_id, this.props.card_id)

        const userList = users.map(user => `
    <option value="${user.login}">${user.login}</option>
    `).join("")

        const colors = ['#f87171', '#facc15', '#4ade80', '#60a5fa', '#c084fc', '#f472b6'];
        const colorList = colors.map(color => `
        <div class="color-circle ${this.props.color === color ? 'selected' : ''}" style="background-color: ${color};" onclick="selectColor" data-color="${color}"></div>
    `).join("");

        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <label for="title">
            <i class="fas fa-pencil-alt"></i>
            Titre</label>
            <input type="text" id="title" name="titrecarte" value="${this.props.title}" required autofocus>
            <label for="description">
            <i class="fas fa-align-left"></i>
            Description</label>
            <textarea id="description" name="descriptifcarte" class="max-h-24 min-h-5">${this.props.description}</textarea>
            
            <label class="whitespace-nowrap">
            <i class="fas fa-user"></i>
            Utilisateur assigné</label>
            <div class="flex flex-col md:flex-row gap-1 w-full">
                ${!assigned ? (canModify ? `
                   <div class="w-full flex flex-col md:flex-row gap-4">
                       <select id="assign-select" class="w-full !my-0">
                         ${userList}
                        </select>
                       <button onclick="assign" class="btnSecondary w-full" type="button">
                       <i class="fas fa-user-plus"></i>
                       Assigner</button>
                    </div>` : "Aucun utilisateur assigné")
            : `
            <div class="w-full flex flex-col md:flex-row gap-4">
            <p class="bg-black text-white px-2 py-1 rounded pb-1">${this.props.assigned}</p>
        ${canModify ? `
            <button onclick="unassign" class="btnSecondary w-full" type="button">
            <i class="fas fa-user-edit"></i>
            Retirer</button>` : ""}
            </div>`}
        </div>

        <label for="color">
        <i class="fas fa-palette"></i>
        Couleur</label>
        <div class="flex gap-2">
        ${colorList}
        </div>
        <input type="hidden" id="color" name="couleurcarte" value="${this.props.color}">
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