import {ReactiveComponent} from "../reactive.js";
import {fetcher} from "../fetcher.js";

export class Column extends ReactiveComponent {
    onMount() {
        fetcher("/tableau/" + this.props.table, (data) =>
            this.setState({
                data: data ? data.colonnes.find(col => col.id === parseInt(this.props.column_id)) : []
            }), 30);
    }

    render() {
        if (!this.state.data) return "";
        return `<div class="shadow rounded-md border p-2 col-span-1 flex">
            <p>${this.state.data.titrecolonne}</p>
        </div>
        `;
    }
}