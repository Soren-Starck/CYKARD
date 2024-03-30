import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {ColumnStore} from "../../stores/column-store.js";

export class NewColumn extends Popup {
    async submit(e) {
        const data = API.formHandler(e)
        const result = await API.create(`/tableau/${this.props.table}/colonne`, data)
        if (!result) return
        result.cartes = []
        ColumnStore.addColumn(result)
        this.close()
    }

    render() {
        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <label for="title">Titre</label>
            <input type="text" id="title" name="TitreColonne" required>
            <button type="submit" name="modify">Créer</button>
        </form>
        `)
    }
}