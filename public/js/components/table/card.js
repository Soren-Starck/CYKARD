import {ReactiveComponent} from "../../reactive.js";
import {Store} from "../../store.js";
import {openPopup} from "../popup.js";

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

    editCard() {
        openPopup("edit-card-popup", {
            column_id: this.props.column_id,
            card_id: this.props.card_id,
            card: this.state.data,
            title: this.state.data.titrecarte,
            description: this.state.data.descriptifcarte,
            color: this.state.data.couleurcarte
        })
    }

    render() {
        if (!this.state.data) return "";
        return `<div onclick="editCard" class="hover:opacity-70 hover:!border-blue-600 transition relative bg-white shadow rounded-md border-2 p-2 flex flex-col gap-1 hover:cursor-grab active:cursor-grabbing group" draggable="true" ondragstart="drag" style="border-color: ${this.state.data.couleurcarte === '#ffffff' ? '#e5e7eb' : this.state.data.couleurcarte}">
            <div class="w-14 h-3 rounded-full absolute top-3 right-4" style="background: ${this.state.data.couleurcarte}"></div>
            <p class="font-bold">${this.state.data.titrecarte}</p>
            <p>${this.state.data.descriptifcarte ?? ""}</p>
        </div>
        `;
    }
}