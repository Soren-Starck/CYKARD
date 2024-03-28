import {loadComponent, ReactiveComponent} from "../reactive.js";
import {fetcher} from "../fetcher.js";
import {Column} from "./column.js";

export class Columns extends ReactiveComponent {
    onMount() {
        loadComponent("column", Column)

        fetcher("/tableau/" + this.props.table, (data) =>
            this.setState({
                data
            }), 30);
    }

    render() {
        if (!this.state.data) return "Loading...";

        const columns = this.state.data.colonnes.map(col => `
            <react-column table="${this.props.table}" column_id="${col.id}"></react-column>
        `).join("")

        return `<div class="w-full overflow-auto py-6">
                <div class="flex gap-2">
                    ${columns}
                    <div class="cursor-pointer shadow rounded-md flex-1 shrink-0 !min-w-[200px] min-h-full flex justify-center items-center border border-dashed">
                        <i class="fa-solid fa-plus fa-2xl text-zinc-500"></i>
                    </div>
                </div>
            </div>
        `;
    }
}