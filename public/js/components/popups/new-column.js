import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {ColumnStore} from "../../stores/column-store.js";

export class NewColumn extends Popup {
    async submit(e) {
        if (this.state.loading) return
        const data = API.formHandler(e)
        this.setState({
            loading: true
        })
        const result = await API.create(`/tableau/${this.props.table}/colonne`, data)
        if (!result) return
        result.cartes = []
        ColumnStore.addColumn(result)
        if (this.props.card) {
            const card = JSON.parse(this.props.card)
            ColumnStore.moveCard(card.from, result.id, card)
            API.update(`/carte/${card.id}/modify`, {
                colonne_id: result.id
            })
            this.removeAttribute("card")
        }
        this.setState({
            loading: false
        })
        this.close()
    }

    render() {
        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <label for="title">
            <i class="fas fa-pencil-alt"></i>
            Titre*</label>
            <input type="text" id="title" minlength="1"
                       maxlength="50" name="TitreColonne" value="Nouvelle colonne" required autofocus>
            <button type="submit" name="modify" ${this.state.loading ? "disabled" : ""}>
            <i class="fas fa-save"></i>
            Créer</button>
        </form>
        `)
    }
}