import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {ColumnStore} from "../../stores/column-store.js";

export class NewCardPopup extends Popup {
    async submit(e) {
        if (this.state.loading) return
        const data = API.formHandler(e)
        this.setState({
            loading: true
        })
        const result = await API.create(`/colonne/${this.props.column_id}/carte`, data)
        if (!result) return
        ColumnStore.addCard(this.props.column_id, result)
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
            Titre</label>
            <input type="text" id="title" name="titrecarte" required autofocus>
            <label for="description">
            <i class="fas fa-align-left"></i>
            Description</label>
            <textarea id="description" name="descriptifcarte"></textarea>
            <label for="color">
            <i class="fas fa-palette"></i>
            Couleur</label>
            <input type="color" id="color" name="couleurcarte">
            <button type="submit" ${this.state.loading ? "disabled" : ""}>
            <i class="fas fa-plus"></i>
            Créer</button>
        </form>
        `)
    }
}