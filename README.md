<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# CESBEN - Sistema de Gestão de Clínica

Este é o sistema completo (API + Frontend Inertia/Vue) CESBEN, desenvolvido para gerenciar processos clínicos com foco em performance e segurança.

## 🚀 Tecnologias Utilizadas
* **Framework:** Laravel 11
* **Frontend:** Vue.js 3 + Inertia.js
* **Autenticação:** Laravel Sanctum (Autenticação via Tokens)
* **Banco de Dados:** MySQL
* **Linguagem:** PHP 8.2+ / JavaScript
* **Requisições HTTP:** Axios

## 🛠️ Funcionalidades Implementadas
* **Autenticação Segura:** Login e logout com proteção contra ataques e expiração de sessão.
* **Gestão de Usuários:** Cadastro de administradores com senhas criptografadas (Bcrypt).
* **Gestão de Pacientes e Profissionais:** CRUD completo documentado.
* **Agendamentos e Evoluções:** Registro de prontuários médicos.
* **Sistema de Notificações:** Avisos automáticos na plataforma.

## 📦 Como Rodar o Projeto

1. **Clonar o Repositório:**
   ```bash
   git clone https://github.com/Yanss12/Projeto-MedCare.git
   ```

2. **Instalar Dependências Dinâmicas (Backend e Frontend):**
   ```bash
   # Instala os pacotes do PHP/Laravel
   composer install
   
   # Instala os pacotes do Node/Vue (como Axios, Inertia, Tailwind)
   npm install
   ```

3. **Configuração de Ambiente:**
   * Renomeie o arquivo `.env.example` para `.env` (ou copie e cole com esse nome).
   * Configure as credenciais do seu banco de dados MySQL local.

4. **Gerar Chave da Aplicação:**
   ```bash
   php artisan key:generate
   ```

5. **Migrações e Banco de Dados:**
   * Certifique-se de que o banco de dados configurado no `.env` (ex: `cesben_db`) existe no seu MySQL criado.
   * Rode as migrations para criar as tabelas:
   ```bash
   php artisan migrate
   ```

6. **Iniciar os Servidores (Você precisa rodar ambos):**
   
   *No terminal 1 (Servidor Backend/PHP):*
   ```bash
   php artisan serve
   ```
   
   *No terminal 2 (Servidor Frontend/Vite):*
   ```bash
   npm run dev
   ```

A aplicação estará rodando em: `http://127.0.0.1:8000`

## 👥 Contribuição

Desenvolvido por Hélio Vinícius e Yan Seligman.
