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

    drag(event) {
        if (!this.state.data) return
        const dt = this.state.data
        dt.from = parseInt(this.props.new_column_id)
        event.dataTransfer.setData("text", JSON.stringify(dt));
    }

    render() {
        if (!this.state.data) return "";
        return `<div id="card-${this.props.card_id}" class="shadow rounded-md border p-2 col-span-1 flex" draggable="true" ondragstart="drag">
            <p>${this.state.data.titrecarte}</p>
        </div>
        `;
    }
}