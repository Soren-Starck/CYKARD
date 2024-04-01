import {API} from "../../api.js";
import {TablesStore} from "../../stores/tables-store.js";
import {Popup} from "../popup.js";

export class DeleteTable extends Popup {
    submit(e) {
        e.preventDefault()
        API.remove(`/tableau/${this.props.table_id}/delete`)
        TablesStore.delete(this.props.table_id)
        this.close()
    }

    render() {
        const name = TablesStore.getName(this.props.table_id)

        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <p>Le tableau '${name}' sera supprimé à jamais.</p>
            <button type="submit">
            <i class="fas fa-trash"></i>
            Supprimer</button>
        </form>
        `)
    }
}