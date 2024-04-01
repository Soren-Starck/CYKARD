import {loadComponent, ReactiveComponent} from "../../reactive.js";
import {TablesStore} from "../../stores/tables-store.js";
import {Store} from "../../store.js";
import {ModifyTable} from "../popups/modify-table.js";
import {openPopup} from "../popup.js";
import {DeleteTable} from "../popups/delete-table.js";

export class TableList extends ReactiveComponent {
    onMount() {
        loadComponent("edit-table", ModifyTable)
        const tables = JSON.parse(this.props.tables)
        TablesStore.set(tables)
        Store.subscribe("tables", (tables) => this.setState({tables}))
        loadComponent("delete-table", DeleteTable)
    }

    delete(e) {
        e.preventDefault()
        openPopup("delete-table", {table_id: e.target.getAttribute("table_id")})
    }

    render() {
        if (!this.state.tables) return ""
        if (Object.values(this.state.tables).length === 0) return `<div class="text-center">Aucun tableau trouvé</div>`
        const tables = Object.values(this.state.tables).map(table => `
            <div class="group relative h-20 flex flex-col hover:cursor-pointer overflow-hidden shadow rounded-md border w-full justify-around items-center bg-[linear-gradient(45deg,transparent_25%,rgba(68,68,68,.2)_50%,transparent_75%,transparent_100%)] bg-[length:250%_250%,100%_100%] bg-[position:-100%_0,0_0] bg-no-repeat hover:bg-[position:200%_0,0_0] hover:transition-[background-position_0s_ease] hover:duration-[1500ms]">
                <a href="/tableaux/${table.id}"
                   class="h-full w-full flex justify-around items-center">
                    <div>
                        <h2 class="mr-auto">${table.titretableau}</h2>
                    </div>
                    <div class="transition absolute top-1 right-2 opacity-0 group-hover:opacity-100 text-neutral-700">
                        <i table_id="${table.id}" onclick="delete" class="cursor-pointer fas fa-trash"></i>
                    </div>
                    ${table.user_role === "USER_ADMIN" ? `<i class="fas fa-user-cog absolute bottom-1 left-2"></i>` : (table.user_role === "USER_EDITOR" ? `<i class="fas fa-user absolute bottom-1 left-2"></i>` : `<i class="fas fa-user absolute bottom-0 left-0"></i>`)}
                </a>
            </div>
        `).join("")

        return `
    <react-delete-table id="delete-table" title="Êtes-vous sûr de vouloir supprimer ce tableau ?"></react-delete-table>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        ${tables}
    </div>
    `
    }
}