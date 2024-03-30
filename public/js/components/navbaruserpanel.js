import {ReactiveComponent} from "../reactive.js";

export class Navbaruserpanel extends ReactiveComponent {
    toggleMenu() {
        this.setAttribute("showmenu", this.props.showmenu === "true" ? "false" : "true");
    }

    render() {
        console.log("rendering Navbaruserpanel : ", this.props.showmenu);
        return `
            <div onclick="toggleMenu" class="text-xl hover:cursor-pointer drop-shadow-sm text-stone-500 hover:text-stone-900 transition w-fit p-1 font-normal hover:bg-gray-100 rounded-lg whitespace-nowrap flex flex-row active:text-neutral-900">
                <i class="fas fa-user-circle"></i>
            </div>
            ${this.props.showmenu === "true" ? `
                <div id="usermenu" class="fixed top-14 right-7 w-fit h-fit rounded-md border bg-white shadow-md flex flex-col">
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