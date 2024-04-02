import {Popup} from "../popup.js";
import {API} from "../../api.js";
import {ColumnStore} from "../../stores/column-store.js";

export class NewCardPopup extends Popup {
    async submit(e) {
        if (this.state.loading) return
        const data = API.formHandler(e)
        this.setState({
            loading: true
        })
        const result = await API.create(`/colonne/${this.props.column_id}/carte`, data)
        if (!result) return
        ColumnStore.addCard(this.props.column_id, result)
        this.setState({
            loading: false
        })
        this.close()
    }

    selectColor(event) {
        const color = event.target.getAttribute('data-color');
        document.getElementById('color').value = color;
        const colorCircles = event.target.parentElement.children;
        for (let i = 0; i < colorCircles.length; i++) {
            colorCircles[i].classList.remove('selected');
            if (colorCircles[i].getAttribute('data-color') === color) {
                colorCircles[i].classList.add('selected');
            }
        }
    }

    render() {

        const colors = ['#f87171', '#facc15', '#4ade80', '#60a5fa', '#c084fc', '#f472b6'];
        const colorList = colors.map(color => `
        <div class="color-circle ${this.props.color === color ? 'selected' : ''}" style="background-color: ${color};" onclick="selectColor" data-color="${color}"></div>
    `).join("");

        return super.render(`
        <form onsubmit="submit" class="flex flex-col gap-2">
            <label for="title">
            <i class="fas fa-pencil-alt"></i>
            Titre*</label>
            <input type="text" id="title" minlength="1"
                       maxlength="50" name="titrecarte" required autofocus>
            <label for="description">
            <i class="fas fa-align-left"></i>
            Description</label>
            <textarea id="description" name="descriptifcarte" class="h-[100px] resize-none"></textarea>
            <label for="color">
            <i class="fas fa-palette"></i>
            Couleur</label>
            <div class="flex gap-2">
            ${colorList}
            </div>
            <input type="hidden" id="color" name="couleurcarte" value="${this.props.color}">
            <button type="submit" ${this.state.loading ? "disabled" : ""}>
            <i class="fas fa-plus"></i>
            Créer</button>
        </form>
        `)
    }
}