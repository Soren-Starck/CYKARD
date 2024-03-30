import {loadComponent, ReactiveComponent} from "../../reactive.js";
import {Card} from "./card.js";
import {API} from "../../api.js";
import {Store} from "../../store.js";
import {ColumnStore} from "../../stores/column-store.js";
import {openPopup} from "../popup.js";

export class Column extends ReactiveComponent {
    onMount() {
        loadComponent("card", Card)

        Store.subscribeOnce("columns", (columns) => {
            const column = columns[parseInt(this.props.column_id)]
            if (!column) return
            this.setState({
                column,
                cards: column.cartes
            })
        })
    }

    drop(e) {
        e.preventDefault();
        const card = JSON.parse(e.dataTransfer.getData("text"));
        if (card.from === parseInt(this.props.column_id)) return
        const added = ColumnStore.moveCard(card.from, this.props.column_id, card)
        if (!added) return
        API.update(`/carte/${card.id}/modify`, {
            colonne_id: parseInt(this.props.column_id)
        })
    }

    allowDrop(e) {
        e.preventDefault()
    }

    newCard() {
        openPopup("new-card-popup", {
            column_id: this.props.column_id
        })
    }

    modify() {
        openPopup("modify-column-popup", {
            column_id: this.props.column_id,
            name: this.state.column.titrecolonne
        })
    }

    render() {
        if (!this.state.column) return "";

        const cards = this.state.cards.map(carte => `
            <react-card column_id="${carte.colonne_id}" card_id="${carte.id}"></react-card>
        `).join("")

        return `<div ondrop="drop" ondragover="allowDrop" class="min-w-[300px] min-h-full shadow rounded-md border-2 p-2 col-span-1 flex flex-col gap-2 group">
    <div class="flex justify-between">
        <p class="ml-1 font-bold">${this.state.column.titrecolonne}</p>
        <i onclick="modify" class="cursor-pointer transition fa-solid fa-pen opacity-0 group-hover:opacity-100"></i>
    </div>
    <div class="flex flex-col gap-2">
        ${cards}
    </div>
    <div onclick="newCard" class="group-hover:opacity-100 opacity-0 hover:bg-slate-50 transition relative bg-white shadow rounded-md border-dashed border-2 p-2 flex flex-col gap-1 hover:cursor-grab active:cursor-grabbing group">
            <div class="w-14 h-3 rounded-full absolute top-3 right-7"></div>
            <p class="w-10 h-4 rounded-md shadow bg-slate-200"></p>
            <p class="w-20 h-4 rounded-md shadow bg-slate-200"></p>
            <i class="fa-solid fa-plus fa-2xl text-slate-200 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
        </div>
</div>
        `;
    }
}