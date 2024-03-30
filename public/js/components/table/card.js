import {ReactiveComponent} from "../../reactive.js";
import {Store} from "../../store.js";

export class Card extends ReactiveComponent {
    onMount() {
        const column = Store.getByIndex("columns", parseInt(this.props.column_id))
        if (!column) return
        const card = column.cartes.find(carte => carte.id === parseInt(this.props.card_id))
        this.setState({data: card})
    }

    drag(event) {
        if (!this.state.data) return
        const dt = this.state.data
        dt.from = parseInt(this.props.column_id)
        event.dataTransfer.setData("text", JSON.stringify(dt));
    }

    render() {
        if (!this.state.data) return "";
        return `<div class="shadow rounded-md border p-2 flex flex-col gap-1 hover:cursor-grab active:cursor-grabbing" draggable="true" ondragstart="drag" style="background: ${this.state.data.couleurcarte}">
            <p class="font-bold">${this.state.data.titrecarte}</p>
            <p>${this.state.data.descriptifcarte ?? ""}</p>
        </div>
        `;
    }
}