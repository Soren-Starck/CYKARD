import {loadComponent, ReactiveComponent} from "../reactive.js";
import {Card} from "./card.js";
import {API} from "../api.js";
import {Store} from "../store.js";
import {ColumnStore} from "../stores/column-store.js";
import {mutate} from "../fetcher.js";

export class Column extends ReactiveComponent {
    onMount() {
        loadComponent("card", Card)

        Store.subscribe("columns", (columns) => {
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
        mutate(`/tableau/${this.props.table}`)
    }

    allowDrop(e) {
        e.preventDefault()
    }

    render() {
        if (!this.state.column) return "";

        const cards = this.state.cards.map(carte => `
            <react-card column_id="${carte.colonne_id}" card_id="${carte.id}"></react-card>
        `).join("")

        return `<div ondrop="drop" ondragover="allowDrop" class="min-w-[300px] min-h-full shadow rounded-md border p-2 col-span-1 flex flex-col gap-2 group">
    <div class="flex justify-between">
        <p class="ml-1 font-bold">${this.state.column.titrecolonne}</p>
        <div>
            <i class="transition fa-solid fa-pen opacity-0 group-hover:opacity-100"></i>
            <i class="transition fa-solid fa-plus opacity-0 group-hover:opacity-100"></i>
        </div>
    </div>
    <div class="flex flex-col gap-2">
        ${cards}
    </div>
</div>
        `;
    }
}