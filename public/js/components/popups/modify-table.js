import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {Store} from "../../store.js";

export class ModifyTable extends Popup {
    submit(e) {
        const data = API.formHandler(e)
        API.update(`/tableau/${this.props.table}/modify`, data)
        Store.set("table", data.titretableau)
        this.close()
    }

    render() {
        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <label for="title">Titre</label>
            <input type="text" id="title" value="${Store.get("table")}" name="titretableau" required autofocus>
            <button type="submit">Modifier</button>
        </form>
        `)
    }
}