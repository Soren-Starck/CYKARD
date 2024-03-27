export class ReactiveComponent extends HTMLElement {
    props = {};
    state = {};

    constructor() {
        super();

        Array.from(this.attributes).forEach(
            (attr) => (this.props[attr.nodeName] = attr.nodeValue)
        );

        this.onMount()
        this._render()
    }

    onMount() {
    }

    setState(state, value) {
        if (typeof state === "object") this.state = {...this.state, ...state};
        else this.state[state] = value;
        this._render();
    }

    _render() {
        this.innerHTML = this.render();

        const elements = this.querySelectorAll("[data-onclick]");
        for (const element of elements) {
            const value = element.getAttribute("data-onclick");
            element.onclick = () => this[value]();
        }
    }

    render() {
        return "";
    }
}

export function loadComponent(name, component) {
    if (window.customElements.get("react-" + name)) return
    customElements.define("react-" + name, component)
}