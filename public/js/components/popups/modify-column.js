import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {ColumnStore} from "../../stores/column-store.js";

export class ModifyColumn extends Popup {
    submit(e) {
        const data = API.formHandler(e)
        API.update(`/colonne/${this.props.column_id}/modify`, data)
        ColumnStore.modifyColumn(this.props.column_id, (column) => {
            column.titrecolonne = data.titrecolonne
            return column
        })
        this.close()
    }

    render() {
        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <label for="title">Titre</label>
            <input type="text" id="title" value="${this.props.name}" name="titrecolonne" required>
            <button type="submit">Modifier</button>
        </form>
        `)
    }
}