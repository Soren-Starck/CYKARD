import {ReactiveComponent} from "../reactive.js";

export class Navbaruserpanel extends ReactiveComponent {
    onMount() {
        window.onclick = (event) => {
            if (this.props.showmenu === "true")
                this.setAttribute("showmenu", "false");
        }
    }

    toggleMenu(e) {
        e.stopPropagation()
        this.setAttribute("showmenu", this.props.showmenu === "true" ? "false" : "true");
    }

    render() {
        return `
            <div onclick="toggleMenu" class="z-[200] text-xl hover:cursor-pointer drop-shadow-sm text-stone-500 hover:text-stone-900 transition w-fit p-1 font-normal hover:bg-gray-100 rounded-lg whitespace-nowrap flex flex-row active:text-neutral-900">
                <i class="fas fa-user-circle"></i>
            </div>
            ${this.props.showmenu === "true" ? `
                <div id="usermenu" class="!z-[200] fixed top-14 right-7 bg-white w-fit h-fit rounded-md border shadow-md flex flex-col text-sm">
                    <a href="${this.props['account-url']}">
                        Mon compte (<span>${this.props['current-user']}</span>)
                    </a>
                    <a href="${this.props['logout-url']}" class="">
                        Se déconnecter
                        <i class="fas fa-sign-out-alt ml-2"></i>
                    </a>
                </div>
            ` : ''}
        `;
    }
}