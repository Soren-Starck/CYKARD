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
        if (this.children.length === 0) return;
        const child = this.children[0];
        const attributes = Array.from(child.attributes).map(attr => attr.name);
        attributes.forEach(attr => {
            if (attr.startsWith("on")) {
                const value = child.getAttribute(attr);
                child[attr] = (e) => this[value](e);
            }
        });
    }

    render() {
        return "";
    }
}

export function loadComponent(name, component) {
    if (window.customElements.get("react-" + name)) return
    customElements.define("react-" + name, component)
}