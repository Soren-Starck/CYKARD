import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {ColumnStore} from "../../stores/column-store.js";

export class ModifyColumn extends Popup {
    submit(e) {
        const action = e.submitter.name
        const data = API.formHandler(e)
        if (action === "delete") {
            API.remove(`/colonne/${this.props.column_id}/delete`)
            ColumnStore.deleteColumn(this.props.column_id)
        } else {
            API.update(`/colonne/${this.props.column_id}/modify`, data)
            ColumnStore.modifyColumn(this.props.column_id, (column) => {
                column.titrecolonne = data.titrecolonne
                return column
            })
        }
        this.close()
    }

    render() {
        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <label for="title">
            <i class="fas fa-pencil-alt"></i>
            Titre*</label>
            <input type="text" id="title" minlength="1"
                       maxlength="50" value="${this.props.name}" name="titrecolonne" required autofocus>
            <div class="flex flex-row gap-4 w-full justify-between">
            <button class="w-full" type="submit" name="modify">
            <i class="fas fa-save"></i>
            Modifier</button>
            <button class="w-full !bg-red-100 !text-red-500" type="submit" name="delete">
            <i class="fas fa-trash"></i>
            Supprimer</button>
            </div>
        </form>
        `)
    }
}