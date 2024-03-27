const notifs = {}

export class Notif {
    static _notif(title, message, type) {
        let notif = document.createElement('div');
        notif.classList.add(type, 'move-up', 'notification');
        let titleH1 = document.createElement('h2');
        titleH1.innerText = title;
        notif.appendChild(titleH1);
        if (message) {
            let messageP = document.createElement('p');
            messageP.innerText = message;
            notif.appendChild(messageP);
        }
        let crossButton = document.createElement('i');
        crossButton.classList.add('fas', 'fa-times', 'cross-button');
        notif.appendChild(crossButton);
        crossButton.addEventListener('click', () => {
            notif.style.opacity = '0';
            notif.style.maxHeight = '0';
            notif.style.margin = '0';
            notif.classList.add('move-out');
            setTimeout(() => {
                notif.remove();
            }, 300);
        });
        document.getElementById('notificationContainer').prepend(notif);
        setTimeout(() => {
            notif.style.opacity = '0';
            notif.style.maxHeight = '0';
            notif.style.margin = '0';
            notif.classList.add('move-out');
            setTimeout(() => {
                notif.remove();
            }, 300);
        }, 6000);
    }

    static success(title, message, id) {
        if (id && notifs[id] && notifs[id] === "success") return;
        notifs[id] = "success";
        this._notif(title, message, 'success');
    }

    static error(title, message, id) {
        if (id && notifs[id] && notifs[id] === "error") return;
        notifs[id] = "error";
        this._notif(title, message, 'error');
    }

    static warning(title, message, id) {
        if (id && notifs[id] && notifs[id] === "warning") return;
        notifs[id] = "warning";
        this._notif(title, message, 'warning');
    }
}