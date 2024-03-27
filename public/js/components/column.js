import {loadComponent, ReactiveComponent} from "../reactive.js";
import {fetcher} from "../fetcher.js";
import {Card} from "./card.js";

export class Column extends ReactiveComponent {
    onMount() {
        loadComponent("card", Card)

        fetcher("/tableau/" + this.props.table, (data) =>
            this.setState({
                data: data.colonnes.find(col => col.id === parseInt(this.props.column_id))
            }));
    }

    render() {
        if (!this.state.data) return "";
        const cards = this.state.data.cartes.map(carte => `
            <react-card table="${this.props.table}" column_id="${this.props.column_id}" card_id="${carte.id}"></react-card>
        `).join("")
        return `<div class="shadow rounded-md border p-2 col-span-1 flex flex-col gap-2">
            <p>${this.state.data.titrecolonne}</p>
            <div class="flex flex-col gap-1">
                ${cards}
            </div>
        </div>
        `;
    }
}