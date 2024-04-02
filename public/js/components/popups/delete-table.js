import {API} from "../../api.js";
import {TablesStore} from "../../stores/tables-store.js";
import {Popup} from "../popup.js";

export class DeleteTable extends Popup {
    submit(e) {
        e.preventDefault()
        this.setState({loading: true})
        API.remove(`/tableau/${this.props.table_id}/delete`)
        TablesStore.delete(this.props.table_id)
        this.setState({loading: false})
        this.close()
    }

    render() {
        const name = TablesStore.getName(this.props.table_id)

        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <p>Le tableau '${name}' sera supprimé à jamais.</p>
            <button type="submit" ${this.state.loading ? "disabled" : ""} class="!bg-red-100 !text-red-500 w-full">
            <i class="fas fa-trash"></i>
            Supprimer</button>
        </form>
        `)
    }
}