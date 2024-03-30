import {ReactiveComponent} from "../reactive.js";

export class Popup extends ReactiveComponent {
    close() {
        this.setAttribute("visible", "false")
    }

    render(children) {
        if (!this.props.visible || this.props.visible === "false") return "";
        return `<div onclick="close" class="w-full h-full z-50 backdrop-blur backdrop-brightness-50 absolute top-0 left-0 BackgroundBlur"></div>
            <div class="absolute !pt-10 top-1/3 z-50 w-1/2 left-1/4 md:w-1/3 md:left-1/3 lg:w-1/5 lg:left-[40%] max-h-1/2 border bg-white shadow-lg rounded-lg p-4 popups">
                ${this.props.title ? `<h2 class="absolute top-2">${this.props.title}</h2>` : ""}
                <i onclick="close" class="fas fa-times absolute top-2 right-2 cursor-pointer"></i>
                ${children}
            </div>`;
    }
}

export function openPopup(id, props) {
    const popup = document.getElementById(id)
    popup.setAttribute("visible", "true")
    if (!props) return
    Object.keys(props).forEach(key => {
        popup.setAttribute(key, props[key])
    })
}