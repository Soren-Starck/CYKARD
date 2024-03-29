import {loadComponent, ReactiveComponent} from "../reactive.js";
import {fetcher, mutate} from "../fetcher.js";
import {Card} from "./card.js";
import {API} from "../api.js";

export class Column extends ReactiveComponent {
    onMount() {
        loadComponent("card", Card)

        fetcher("/tableau/" + this.props.table, (data) => {
            const column = data.colonnes.find(col => col.id === parseInt(this.props.column_id))
            this.setState({
                column,
                cards: column.cartes
            })
        });
    }

    drop(e) {
        e.preventDefault();
        const card = JSON.parse(e.dataTransfer.getData("text"));
        if (card.from === parseInt(this.props.column_id)) return
        if (this.state.cards.find(c => c.id === card.id)) return
        document.getElementById(`card-${card.id}`).parentElement.remove()
        API.update(`/carte/${card.id}/modify`, {
            colonne_id: parseInt(this.props.column_id)
        })
        this.setState({
            cards: [...this.state.cards, card]
        })
        mutate(`/tableau/${this.props.table}`)
    }

    allowDrop(e) {
        e.preventDefault()
    }

    render() {
        if (!this.state.column) return "";

        const cards = this.state.cards.map(carte => `
            <react-card table="${this.props.table}" column_id="${carte.colonne_id}" new_column_id="${this.props.column_id}" card_id="${carte.id}"></react-card>
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