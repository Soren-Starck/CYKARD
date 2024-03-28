import {ReactiveComponent} from "../reactive.js";

export class Popup extends ReactiveComponent {
    close() {
        this.setAttribute("visible", "false")
        console.log("close")
    }

    render() {
        if (this.props.visible === "false") return "";
        return `
    <div onclick="close" class="w-full h-full backdrop-blur backdrop-brightness-50 absolute top-0 left-0"></div>
    <div class="absolute top-1/4 z-50 w-1/2 max-h-1/2 left-1/4 border bg-white shadow-lg rounded-lg p-4">
        PopupContent example
        <i onclick="close" class="fas fa-times absolute top-2 right-2 cursor-pointer"></i>
    </div>
    `;
    }
}