import {Store} from "../store.js";

export class TablesStore {
    static set(tables) {
        const data = {}
        tables.forEach(table => data[table.id] = table)
        Store.set("tables", data)
    }

    static delete(id) {
        id = parseInt(id)
        Store.removeByIndex("tables", id)
    }

    static getName(id) {
        id = parseInt(id)
        const table = Store.getByIndex("tables", id)
        return table ? table.titretableau : ""
    }
}