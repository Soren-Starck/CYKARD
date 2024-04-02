import {ReactiveComponent} from "../../reactive.js";
import {Store} from "../../store.js";
import {openPopup} from "../popup.js";
import {UserStore} from "../../stores/user-store.js";

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
            color: this.state.data.couleurcarte,
            assigned: this.state.data.user_carte_login
        })
    }

    render() {
        if (!this.state.data) return "";
        let regex = /\bhttps?:\/\/[^)''"]+\.(?:jpg|jpeg|gif|png)\b/g;

        let description = this.state.data.descriptifcarte ?? "";
        let matches = description.match(regex);
        matches = matches && matches.length > 0 ? matches[0] : null;
        if (matches) description = description.replace(matches, "");

        const canModify = UserStore.canModify()
        return `<div ${canModify ? `onclick="editCard" draggable="true" ondragstart="drag"` : ''} class="hover:opacity-70 hover:!border-blue-600 transition relative bg-white shadow rounded-md border-2 p-2 flex flex-col gap-1 ${canModify ? "hover:cursor-grab active:cursor-grabbing" : ''} group" style="border-color: ${this.state.data.couleurcarte === '#ffffff' ? '#e5e7eb' : this.state.data.couleurcarte}">
            <div class="w-14 h-3 rounded-full absolute top-3 right-4" style="background: ${this.state.data.couleurcarte}"></div>
            <p class="font-bold">${this.state.data.titrecarte}</p>
            <p class="bg-neutral-900 text-sm rounded-lg px-2 text-white w-fit">${this.state.data.user_carte_login ?? ""}</p>
            ${matches ? `<img src="${matches}" class="w-full mt-2 h-36 object-cover rounded-md bg-zinc-300" alt="Image de la carte">` : ""}
            <p>${description}</p>
        </div>
        `;
    }
}