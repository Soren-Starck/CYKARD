import {ReactiveComponent} from "../reactive.js";
import {API} from "../api.js";
import {Notif} from "../notifications.js";

export class ChangePassword extends ReactiveComponent {
    onMount() {
        this.setState({
            error: ''
        })
    }

    async change(e) {
        e.preventDefault();
        const data = API.formHandler(e);
        if (password1.value !== password2.value) {
            this.setState({
                error: 'Les mots de passe ne correspondent pas'
            });
            return;
        }
        let hasError = false;
        await API.update('/user/modify-password', {
            old_password: data.old_password,
            new_password: data.password1
        }, () => {
            this.setState({
                error: 'Mot de passe incorrect'
            })
            hasError = true;
        })
        if (!hasError)
            Notif.success('Mot de passe modifié avec succès');
    }

    render() {
        return `
            <form onsubmit="change" class="w-full flex flex-col gap-2">
                <div class="form-group flex flex-col w-full">
                    <label for="old_password">
                        <i class="fas fa-lock"></i>
                    Ancien mot de passe</label>
                    <input type="password" class="form-control" id="old_password" name="old_password" required>
                </div>
                <div class="form-group flex flex-col w-full">
                    <label for="password1">
                        <i class="fas fa-lock"></i>
                    Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password1" name="password1" required>
                </div>
                <div class="form-group flex flex-col w-full">
                    <label for="password2">
                        <i class="fas fa-lock"></i>
                    Confirmer le mot de passe</label>
                    <input type="password" class="form-control" id="password2" name="password2" required>
                </div>
                <button type="submit" class="btn btn-primary w-full">
                    <i class="fas fa-save"></i>
                Modifier</button>
                <p class="text-danger">${this.state.error}</p>
            </form>
        `;
    }
}