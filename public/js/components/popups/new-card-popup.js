import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {ColumnStore} from "../../stores/column-store.js";

export class NewCardPopup extends Popup {
    async submit(e) {
        const data = API.formHandler(e)
        const result = await API.create(`/colonne/${this.props.column_id}/carte`, data)
        if (!result) return
        ColumnStore.addCard(this.props.column_id, result)
        this.close()
    }

    render() {
        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <label for="title">Titre</label>
            <input type="text" id="title" name="titrecarte" required autofocus>
            <label for="description">Description</label>
            <textarea id="description" name="descriptifcarte"></textarea>
            <label for="color">Couleur</label>
            <input type="color" id="color" name="couleurcarte">
            <button type="submit">Créer</button>
        </form>
        `)
    }
}