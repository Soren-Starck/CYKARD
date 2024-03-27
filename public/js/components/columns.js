import {ReactiveComponent} from "../reactive.js";
import {fetcher} from "../fetcher.js";

export class Columns extends ReactiveComponent {
    onMount() {
        //loadComponent("column", Column)
        fetcher("/tableau/" + this.props.table, (data) =>
            this.setState({
                data
            }), 30);
    }

    render() {
        if (!this.state.data) return "";
        const columns = this.state.data.colonnes.map(col => `
            <react-column table="${this.props.table}" column_id="${col.id}"></react-column>
        `).join("")
        return `<div class="grid grid-cols-4 gap-2">
            ${columns}
        </div>`;
    }
}