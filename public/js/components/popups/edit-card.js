import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {ColumnStore} from "../../stores/column-store.js";

export class EditCard extends Popup {
    submit(e) {
        const data = API.formHandler(e)
        API.update(`/carte/${this.props.card_id}/modify`, data)
        ColumnStore.modifyCard(this.props.column_id, this.props.card_id, (card) => {
            card.titrecarte = data.titrecarte
            card.descriptifcarte = data.descriptifcarte
            card.couleurcarte = data.couleurcarte
            return card
        })
        this.close()
    }

    render() {
        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <label for="title">Titre</label>
            <input type="text" id="title" name="titrecarte" value="${this.props.title}" required autofocus>
            <label for="description">Description</label>
            <textarea id="description" name="descriptifcarte">${this.props.description}</textarea>
            <label for="color">Couleur</label>
            <input type="color" id="color" name="couleurcarte" value="${this.props.color}">
            <button type="submit">Modifier</button>
        </form>
        `)
    }
}