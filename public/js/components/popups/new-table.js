import {Popup} from "../popup.js";
import {API} from "../../api.js";

export class NewTable extends Popup {
    async submit(e) {
        const data = API.formHandler(e)
        const result = await API.create(`/tableau`, data)
        if (!result) return
        window.location.href = `/tableaux/${result.id}`
    }

    render() {
        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <label for="title">Titre</label>
            <input type="text" id="title" name="titretableau" value="Mon tableau" required autofocus>
            <button type="submit">Créer</button>
        </form>
        `)
    }
}