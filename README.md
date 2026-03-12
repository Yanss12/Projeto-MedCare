<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

```markdown
# CESBEN - Sistema de Gestão de Clínica (API Backend)

Este é o motor (API) do sistema CESBEN, desenvolvido para gerenciar processos clínicos com foco em performance e segurança.

## 🚀 Tecnologias Utilizadas
* **Framework:** Laravel 11
* **Autenticação:** Laravel Sanctum (Autenticação via Tokens)
* **Banco de Dados:** MySQL
* **Linguagem:** PHP 8.2+

## 🛠️ Funcionalidades Implementadas
* **Autenticação Segura:** Login e logout com proteção contra ataques e expiração de sessão.
* **Gestão de Usuários:** Cadastro de administradores com senhas criptografadas (Bcrypt).
* **API RESTful:** Endpoints preparados para integração total com o frontend em Vue.js.
* **Segurança CORS:** Configurada para aceitar requisições apenas do ambiente autorizado.

## 📦 Como Rodar o Projeto

1. **Clonar o Repositório:**
   ```bash
   git clone [https://github.com/SEU_USUARIO/cesben-backend.git](https://github.com/SEU_USUARIO/cesben-backend.git)

```

2. **Instalar Dependências:**
```bash
composer install

```


3. **Configuração de Ambiente:**
* Renomeie o arquivo `.env.example` para `.env`.
* Configure as credenciais do seu banco de dados MySQL local.


4. **Gerar Chave da Aplicação:**
```bash
php artisan key:generate

```


5. **Migrações e Banco de Dados:**
* Certifique-se de que o banco `cesben_db` existe.
* Rode as migrations: `php artisan migrate`


6. **Iniciar o Servidor:**
```bash
php artisan serve

```


A API estará rodando em: `http://127.0.0.1:8000`

## 👥 Contribuição

Desenvolvido por Hélio Vinícius e Yan Seligman.

```
