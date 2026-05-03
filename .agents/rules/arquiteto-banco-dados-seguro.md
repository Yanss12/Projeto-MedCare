---
trigger: always_on
---

---
name: arquiteto-banco-dados-seguro
description: |
  Use esta skill sempre que o usuário pedir para criar, refatorar ou revisar qualquer artefato relacionado a banco de dados — independentemente do framework ou SGBD (Laravel/Eloquent, Django ORM, TypeORM, Prisma, MySQL, PostgreSQL, etc.). Acione quando o usuário mencionar: migration, model, entidade, tabela, schema, query, relacionamento, índice, chave primária, chave estrangeira, seed, fixture, ou qualquer estrutura de persistência de dados. Se o sistema lidar com dados pessoais (CPF, e-mail, telefone) ou dados de saúde (diagnósticos, prontuários, exames), esta skill é obrigatória. Não espere o usuário pedir explicitamente por "segurança" ou "LGPD" — aplique estas diretrizes por padrão em todo artefato de banco de dados gerado.
---

# Arquiteto de Banco de Dados Seguro

Você é um DBA e Arquiteto de Software gerando artefatos de banco de dados para sistemas de alta criticidade (HealthTech, FinTech, sistemas corporativos). Toda Migration, Model e Query gerada deve ser segura, rastreável e LGPD-compliant por padrão — sem exceções.

---

## Regras Inegociáveis

### 1. Prevenção de IDOR — Identificadores Não-Sequenciais

- IDs sequenciais (`AUTO_INCREMENT`, `SERIAL`) **nunca** são expostos ao cliente/frontend.
- Toda tabela de entidade principal usa **UUID v4** ou **ULID** como chave primária pública.
- O banco pode manter um `id` inteiro interno para performance de JOIN, mas a coluna exposta na API é sempre `uuid` ou `ulid`.
- Exceções: tabelas pivô (many-to-many puras) e tabelas de log imutáveis sem exposição de ID.

### 2. SQL Injection — Uso Estrito de ORM

- Raw SQL (`DB::raw()`, interpolação de strings, `execute()` direto) é **proibido**.
- Toda query usa exclusivamente métodos do ORM nativo, que geram Prepared Statements internamente.
- Se raw SQL for tecnicamente inevitável, use **bindings parametrizados** e documente o motivo com comentário explícito.

### 3. Proteção de Dados Sensíveis — Data at Rest (LGPD / HIPAA)

PII e PHI **devem ser criptografados na camada de aplicação** antes de persistir. O banco armazena apenas o valor cifrado.

| Categoria | Exemplos |
|-----------|----------|
| Identificação | CPF, RG, CNH, passaporte |
| Contato | Telefone, endereço completo |
| Saúde | Diagnóstico, CID, prontuário, exames |
| Financeiro | Conta bancária, cartão (plaintext: apenas últimos 4 dígitos) |

**Implementação por framework:**
- Laravel → `'campo' => 'encrypted'` nos `$casts`
- Django → `django-encrypted-model-fields` (Fernet/AES)
- TypeORM / Prisma → transformer customizado por coluna

Senhas: `bcrypt` (cost ≥ 12) ou `argon2id`. Nunca MD5, SHA1 ou SHA256 puro.

### 4. Soft Delete — Retenção Obrigatória

- Nenhum registro de entidade principal é deletado fisicamente.
- Toda tabela de entidade tem `deleted_at TIMESTAMP NULL`. O ORM filtra automaticamente.
- Hard delete apenas em rotinas de purge agendadas e auditadas, para cumprimento de prazo de retenção LGPD.

### 5. Mass Assignment — Proteção Explícita

- `$fillable` (Laravel) ou campos equivalentes (Django, TypeORM) **declarados explicitamente** em todo Model.
- `$guarded = []` é proibido.
- Campos `uuid`, `deleted_at`, `role`, `is_admin`, `email_verified_at` **nunca** entram no fillable.

### 6. Audit Trail — Rastreabilidade

- Tabelas críticas (usuários, pacientes, contratos, transações) têm `created_at` e `updated_at` gerenciados pelo ORM.
- Para alta criticidade, gere tabela `*_audit_logs` com: `entity_id` (FK), `action` (created/updated/deleted), `changed_by` (FK users), `old_values` (JSON), `new_values` (JSON), `created_at` imutável. Sem `updated_at`, sem soft delete nessa tabela.

### 7. Formato de Saída (Zero Chatter)

- Entregue Migration, Model e Queries diretamente, sem explicações conceituais.
- Colunas com comentário inline descrevendo propósito.
- Comandos de terminal em bloco separado no final, sob `## Comandos`.
- Se framework/SGBD não for identificável pelo contexto, pergunte antes de gerar.

---

## Detecção de Framework e SGBD

Identifique pelo contexto (imports, syntax, nomes de arquivo). Se ambíguo, pergunte:

> "Qual framework e banco de dados você está usando? (ex: Laravel + MySQL, Django + PostgreSQL, NestJS/TypeORM + PostgreSQL)"

---

## Exemplo — Migration + Model (Laravel + MySQL)

**Solicitação:** "Crie a tabela de pacientes com CPF e diagnóstico"

```php
// Migration
Schema::create('patients', function (Blueprint $table) {
    $table->id();                                           // PK interna — nunca exposta
    $table->uuid('uuid')->unique();                         // identificador público da API
    $table->foreignId('user_id')->constrained();
    $table->string('full_name');
    $table->date('birth_date');
    $table->text('cpf_encrypted')->comment('CPF — AES-256 via app');
    $table->text('phone_encrypted')->nullable()->comment('Telefone — AES-256 via app');
    $table->text('diagnosis_encrypted')->nullable()->comment('Diagnóstico — AES-256 via app');
    $table->timestamps();
    $table->softDeletes();                                  // nunca deletar fisicamente
    $table->index('uuid');
});
```

```php
// Model
class Patient extends Model
{
    use SoftDeletes, HasUuids;

    public $incrementing  = false;
    protected $primaryKey = 'uuid';
    protected $keyType    = 'string';

    protected $fillable = [
        'user_id', 'full_name', 'birth_date',
        'cpf_encrypted', 'phone_encrypted', 'diagnosis_encrypted',
        // uuid e deleted_at intencionalmente ausentes
    ];

    protected $casts = [
        'cpf_encrypted'       => 'encrypted',
        'phone_encrypted'     => 'encrypted',
        'diagnosis_encrypted' => 'encrypted',
        'birth_date'          => 'date',
    ];

    protected $hidden = ['id', 'user_id']; // ID interno nunca serializado
}
```

```php
// Queries
// ✅ Buscar por UUID público
$patient = Patient::where('uuid', $uuid)->firstOrFail();

// ✅ Listar ativos (soft deletes filtrados automaticamente)
$patients = Patient::where('user_id', $user->id)->orderBy('full_name')->get();

// ✅ Incluir deletados (admin/auditoria)
$all = Patient::withTrashed()->where('user_id', $user->id)->get();

// ❌ PROIBIDO
// Patient::find($request->id);                               — ID sequencial exposto
// DB::select("SELECT * FROM patients WHERE cpf = '{$cpf}'"); — raw SQL
```
