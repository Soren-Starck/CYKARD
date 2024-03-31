import {loadComponent, ReactiveComponent} from "../../reactive.js";
import {fetcher} from "../../fetcher.js";
import {Column} from "./column.js";
import {Store} from "../../store.js";
import {openPopup} from "../popup.js";

export class Columns extends ReactiveComponent {
    onMount() {
        loadComponent("column", Column)

        fetcher("/tableau/" + this.props.table, (data) => {
            const left = document.getElementById("columns-container")
            this.setState({
                left: left ? left.scrollLeft : 0
            })
            const columns = {}
            for (const column of data.colonnes)
                columns[column.id] = column
            Store.set("columns", columns)
            Store.set("table", data.titretableau)
        }, 30);

        Store.subscribe("table", (table) => {
            this.setState({title: table})
        })

        Store.subscribe("columns", (columns) => {
            const left = document.getElementById("columns-container")
            this.setState({
                columns: Object.values(columns),
                left: left ? left.scrollLeft : 0
            })
        })
    }

    settings() {
        openPopup("settings-popup", {table: this.props.table})
    }

    newColumn() {
        openPopup("new-column-popup", {table: this.props.table})
    }

    dropNewColumn(e) {
        e.preventDefault();
        openPopup("new-column-popup", {table: this.props.table, card: e.dataTransfer.getData("text")})
    }

    allowDropNewCol(e) {
        e.preventDefault()
    }

    render() {
        if (!this.state.columns) return `
            <div class="text-center h-[80vh] flex justify-center items-center">
            <div role="status" class="my-auto">
                <svg aria-hidden="true" class="inline w-12 h-12 text-gray-200 animate-spin dark:text-gray-50 fill-neutral-900" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
            </div>`;

        const columns = this.state.columns.map(col => `
            <react-column table="${this.props.table}" column_id="${col.id}"></react-column>
        `).join("")

        return `<div class="w-full flex justify-between">
                <h1 class="font-bold">${this.state.title}</h1>
                <i onclick="settings" class="cursor-pointer fa-solid fa-gear"></i>
            </div>
            <div class="w-full grow !h-full overflow-auto py-6" id="columns-container">
                <div class="flex gap-4 min-h-[500px]">
                    ${columns}
                    <div ondrop="dropNewColumn" ondragover="allowDropNewCol" onclick="newColumn" class="relative cursor-pointer transition hover:bg-slate-50 shadow p-2 rounded-md flex-1 shrink-0 !max-w-[300px] min-w-[300px] min-h-full border-2 border-dashed">
                        <p class="w-24 h-4 rounded-md shadow bg-slate-200"></p>
                        <i class="fa-solid fa-plus fa-2xl text-slate-200  absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
                    </div>
                </div>
            </div>
        `;
    }

    afterRender() {
        const left = document.getElementById("columns-container")
        if (!left) return
        left.scrollLeft = this.state.left
    }
}