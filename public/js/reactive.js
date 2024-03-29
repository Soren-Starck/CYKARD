export class ReactiveComponent extends HTMLElement {
    props = {};
    state = {};
    child = ""

    constructor() {
        super();

        this._loadProps()

        this.child = this.innerHTML

        const observer = new MutationObserver(() => {
            this._loadProps();
            this._render();
        });
        observer.observe(this, {attributes: true});

        this.onMount()
        this._render()
    }

    onMount() {
    }

    _loadProps() {
        Array.from(this.attributes).forEach(
            (attr) => (this.props[attr.nodeName] = attr.nodeValue)
        );
    }

    setState(state, value) {
        if (typeof state === "object") this.state = {...this.state, ...state};
        else this.state[state] = value;
        this._render();
    }

    _render() {
        this.innerHTML = this.render();
        this.afterRender()
        if (this.children.length === 0) return;
        for (const child of this.children)
            this._bind(child)
    }

    _bind(element) {
        const attributes = Array.from(element.attributes).map(attr => attr.name);
        attributes.forEach(attr => {
            if (attr.startsWith("on")) {
                const value = element.getAttribute(attr);
                if (this[value] === undefined) return
                element[attr] = (e) => {
                    if (e) this[value](e);
                    else this[value]();
                }
            }
        });
        for (const el of element.children)
            this._bind(el)
    }

    afterRender() {

    }

    render() {
        return "";
    }
}

export function loadComponent(name, component) {
    if (window.customElements.get("react-" + name)) return
    customElements.define("react-" + name, component)
}