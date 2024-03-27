import {ReactiveComponent} from "../reactive.js";
import {fetcher} from "../fetcher.js";

export class Card extends ReactiveComponent {
    onMount() {
        fetcher("/tableau/" + this.props.table, (data) => {
            const column = data.colonnes.find(col => col.id === parseInt(this.props.column_id))
            if (!column) return
            this.setState({
                data: column.cartes.find(carte => carte.id === parseInt(this.props.card_id))
            })
        });
    }

    render() {
        if (!this.state.data) return "";
        return `<div class="shadow rounded-md border p-2 col-span-1 flex">
            <p>${this.state.data.titrecarte}</p>
        </div>
        `;
    }
}