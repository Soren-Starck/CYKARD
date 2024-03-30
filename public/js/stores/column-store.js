import {Store} from "../store.js";

export class ColumnStore {
    static getColumn(column_id) {
        if (typeof column_id === "string") column_id = parseInt(column_id)
        return Store.getByIndex("columns", column_id)
    }

    static moveCard(from, to, card) {
        if (from === to) return false
        if (typeof from === "string") from = parseInt(from)
        if (typeof to === "string") to = parseInt(to)
        const columns = Store.get("columns")
        if (!columns[from] || !columns[to]) return false
        card.colonne_id = to
        columns[from].cartes = columns[from].cartes.filter(c => c.id !== card.id)
        columns[to].cartes = [...columns[to].cartes, card]
        Store.set("columns", columns)
        return true
    }

    static addCard(column_id, card) {
        if (typeof column_id === "string") column_id = parseInt(column_id)
        const columns = Store.get("columns")
        const column = columns[column_id]
        column.cartes = [...column.cartes, card]
        Store.set("columns", columns)
    }

    static modifyColumn(column_id, callback) {
        const column = this.getColumn(column_id)
        const newColumn = callback(column)
        const columns = Store.get("columns")
        columns[column_id] = newColumn
        Store.set("columns", columns)
    }

    static deleteColumn(column_id) {
        if (typeof column_id === "string") column_id = parseInt(column_id)
        Store.removeByIndex("columns", column_id)
    }
}