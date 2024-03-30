import {ReactiveComponent} from "../reactive.js";

export class Popup extends ReactiveComponent {
    close() {
        this.setAttribute("visible", "false")
    }

    render(children) {
        if (this.props.visible === "false") return "";
        return `<div onclick="close" class="w-full h-full backdrop-blur backdrop-brightness-50 absolute top-0 left-0 BackgroundBlur"></div>
            <div class="absolute !pt-10 top-1/4 z-50 w-1/2 max-h-1/2 left-1/4 border bg-white shadow-lg rounded-lg p-4 popups">
                <h2 class="absolute top-2">${this.props.title}</h2>
                <i onclick="close" class="fas fa-times absolute top-2 right-2 cursor-pointer"></i>
                ${children}
            </div>`;
    }
}

export function openPopup(id, props) {
    const popup = document.getElementById(id)
    popup.setAttribute("visible", "true")
    Object.keys(props).forEach(key => {
        popup.setAttribute(key, props[key])
    })
}