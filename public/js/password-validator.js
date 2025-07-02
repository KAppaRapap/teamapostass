/**
 * Password Validator and Toggle Visibility
 * Validação robusta de palavra-passe com indicador visual
 */

class PasswordValidator {
    constructor() {
        this.init();
    }

    init() {
        // Inicializar quando o DOM estiver carregado
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupPasswordFields());
        } else {
            this.setupPasswordFields();
        }
    }

    setupPasswordFields() {
        // Encontrar todos os campos de palavra-passe
        const passwordFields = document.querySelectorAll('input[type="password"]');
        
        passwordFields.forEach(field => {
            this.enhancePasswordField(field);
        });
    }

    enhancePasswordField(field) {
        // Criar wrapper para o campo
        const wrapper = document.createElement('div');
        wrapper.className = 'password-field-wrapper relative';
        
        // Inserir wrapper antes do campo
        field.parentNode.insertBefore(wrapper, field);
        wrapper.appendChild(field);

        // Adicionar botão de mostrar/ocultar
        this.addToggleButton(wrapper, field);

        // Adicionar validação se for campo de nova palavra-passe
        if (field.name === 'password' && (field.form.action.includes('register') || field.form.action.includes('reset'))) {
            this.addPasswordStrengthIndicator(wrapper, field);
        }
    }

    addToggleButton(wrapper, field) {
        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.className = 'password-toggle-btn absolute text-gray-400 hover:text-white transition-colors focus:outline-none';
        toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';

        toggleBtn.addEventListener('click', () => {
            const isPassword = field.type === 'password';
            field.type = isPassword ? 'text' : 'password';
            toggleBtn.innerHTML = isPassword ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        });

        wrapper.appendChild(toggleBtn);
    }

    addPasswordStrengthIndicator(wrapper, field) {
        // Criar indicador de força
        const strengthIndicator = document.createElement('div');
        strengthIndicator.className = 'password-strength-indicator mt-2';
        strengthIndicator.innerHTML = `
            <div class="password-strength-bar mb-2">
                <div class="password-strength-fill"></div>
            </div>
            <div class="password-requirements text-xs space-y-1">
                <div class="requirement" data-requirement="length">
                    <i class="fas fa-times text-red-400"></i>
                    <span>Pelo menos 8 caracteres</span>
                </div>
                <div class="requirement" data-requirement="uppercase">
                    <i class="fas fa-times text-red-400"></i>
                    <span>Uma letra maiúscula</span>
                </div>
                <div class="requirement" data-requirement="lowercase">
                    <i class="fas fa-times text-red-400"></i>
                    <span>Uma letra minúscula</span>
                </div>
                <div class="requirement" data-requirement="number">
                    <i class="fas fa-times text-red-400"></i>
                    <span>Um número</span>
                </div>
                <div class="requirement" data-requirement="special">
                    <i class="fas fa-times text-red-400"></i>
                    <span>Um carácter especial (!@#$%^&*)</span>
                </div>
            </div>
        `;

        wrapper.appendChild(strengthIndicator);

        // Adicionar listener para validação em tempo real
        field.addEventListener('input', () => {
            this.validatePassword(field.value, strengthIndicator);
        });
    }

    validatePassword(password, indicator) {
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password),
            special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
        };

        // Atualizar indicadores visuais
        Object.keys(requirements).forEach(req => {
            const reqElement = indicator.querySelector(`[data-requirement="${req}"]`);
            const icon = reqElement.querySelector('i');
            
            if (requirements[req]) {
                icon.className = 'fas fa-check text-green-400';
                reqElement.classList.add('text-green-400');
                reqElement.classList.remove('text-gray-400');
            } else {
                icon.className = 'fas fa-times text-red-400';
                reqElement.classList.add('text-gray-400');
                reqElement.classList.remove('text-green-400');
            }
        });

        // Calcular força da palavra-passe
        const strength = Object.values(requirements).filter(Boolean).length;
        const strengthBar = indicator.querySelector('.password-strength-fill');
        
        // Atualizar barra de força
        const strengthPercentage = (strength / 5) * 100;
        strengthBar.style.width = `${strengthPercentage}%`;
        
        // Cores baseadas na força
        if (strength <= 2) {
            strengthBar.className = 'password-strength-fill bg-red-500 h-2 rounded transition-all duration-300';
        } else if (strength <= 3) {
            strengthBar.className = 'password-strength-fill bg-yellow-500 h-2 rounded transition-all duration-300';
        } else if (strength <= 4) {
            strengthBar.className = 'password-strength-fill bg-blue-500 h-2 rounded transition-all duration-300';
        } else {
            strengthBar.className = 'password-strength-fill bg-green-500 h-2 rounded transition-all duration-300';
        }

        return strength === 5;
    }

    // Método público para validar palavra-passe
    static isValidPassword(password) {
        return password.length >= 8 &&
               /[A-Z]/.test(password) &&
               /[a-z]/.test(password) &&
               /\d/.test(password) &&
               /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
    }
}

// Inicializar automaticamente
new PasswordValidator();

// Exportar para uso global
window.PasswordValidator = PasswordValidator;
