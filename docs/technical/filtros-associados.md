# 📋 Documentação dos Filtros de Associados

> **Versão:** 2.0  
> **Última atualização:** 23/11/2025  
> **Recurso:** `AssociadoResource`

---

## 🎯 Visão Geral

Os filtros de **Associados** foram completamente refatorados para oferecer:

-   ✅ **Mais de 25 filtros** organizados por categoria
-   ✅ **Performance otimizada** com `preload()` e `searchable()`
-   ✅ **UX aprimorada** com filtros ternários e labels descritivos
-   ✅ **Filtros inteligentes** baseados em relacionamentos

---

## 📂 Categorias de Filtros

### 1️⃣ Filtros Básicos de Identificação

| Filtro                | Tipo         | Descrição                                  | Múltiplo |
| --------------------- | ------------ | ------------------------------------------ | -------- |
| **Status**            | SelectFilter | Status do associado (Ativo, Inativo, etc.) | ✅ Sim   |
| **Sexo**              | SelectFilter | Sexo biológico                             | ✅ Sim   |
| **Declaração Sexual** | SelectFilter | Identidade de gênero                       | ✅ Sim   |
| **Estado Civil**      | SelectFilter | Estado civil atual                         | ✅ Sim   |

---

### 2️⃣ Filtros de Datas

| Filtro                               | Tipo            | Descrição                                                                      |
| ------------------------------------ | --------------- | ------------------------------------------------------------------------------ |
| **Data de Cadastro**                 | DateRangeFilter | Período de cadastro no sistema                                                 |
| **Data de Nascimento**               | DateRangeFilter | Período de nascimento                                                          |
| **Data de Renovação de Carteirinha** | DateRangeFilter | Filtra associados que renovaram carteirinha no período (mínimo 2 carteirinhas) |

---

### 3️⃣ Filtros de Idade (MELHORADOS) 🆕

#### **Faixa de Idade (Rápida)**

Filtro de seleção única com faixas pré-definidas:

-   **0-12 anos** (Criança)
-   **13-17 anos** (Adolescente)
-   **18-25 anos** (Jovem)
-   **26-40 anos** (Adulto)
-   **41-59 anos** (Adulto)
-   **60+ anos** (Idoso)

> **Nota:** Este filtro agora permite selecionar faixas específicas como "0-12" ou "13-17" individualmente.

#### **Faixa de Idade (Personalizada)** 🆕

Filtro avançado que permite criar **múltiplos intervalos customizados**:

-   **Exemplo 1:** Filtrar crianças de 3 a 5 anos
    -   Min: 3, Max: 5
-   **Exemplo 2:** Filtrar jovens (18-25) E idosos (60+)
    -   Intervalo 1: Min: 18, Max: 25
    -   Intervalo 2: Min: 60, Max: (deixar vazio)

**Recursos:**

-   ✅ Validação automática (idade máxima ≥ idade mínima)
-   ✅ Múltiplos intervalos (operador OR)
-   ✅ Labels dinâmicos nos chips de filtro
-   ✅ Interface colapsável para economizar espaço

#### **Aniversariantes do Mês**

Filtro por mês de nascimento (Janeiro a Dezembro).

---

### 4️⃣ Filtros de Localização

| Filtro                 | Tipo         | Descrição                     | Múltiplo |
| ---------------------- | ------------ | ----------------------------- | -------- |
| **UF de Naturalidade** | SelectFilter | Estado onde nasceu            | ✅ Sim   |
| **Cidade (Endereço)**  | SelectFilter | Cidade onde reside atualmente | ✅ Sim   |
| **Perímetro**          | SelectFilter | Urbano ou Rural               | ✅ Sim   |

---

### 5️⃣ Filtros Sociodemográficos

| Filtro           | Tipo         | Descrição                  | Múltiplo |
| ---------------- | ------------ | -------------------------- | -------- |
| **Religião**     | SelectFilter | Religião declarada         | ✅ Sim   |
| **Escolaridade** | SelectFilter | Nível de escolaridade      | ✅ Sim   |
| **Raça/Cor**     | SelectFilter | Autodeclaração de raça/cor | ✅ Sim   |
| **Ocupação**     | SelectFilter | Ocupação profissional      | ✅ Sim   |

---

### 6️⃣ Filtros de Deficiência 🆕

| Filtro                   | Tipo                    | Descrição                       |
| ------------------------ | ----------------------- | ------------------------------- |
| **Possui Deficiência?**  | TernaryFilter           | Sim / Não / Todos               |
| **Tipo de Deficiência**  | SelectFilter (múltiplo) | Física, Visual, Auditiva, etc.  |
| **Causa da Deficiência** | SelectFilter (múltiplo) | Congênita, Adquirida, etc.      |
| **Aparelhos Utilizados** | SelectFilter (múltiplo) | Cadeira de rodas, Prótese, etc. |
| **CID-10**               | SelectFilter (múltiplo) | Código e descrição da CID       |
| **Possui CRM?**          | TernaryFilter           | Sim / Não / Todos               |

> **Dica:** O filtro "Possui Deficiência?" verifica se há `tipo_deficiencia`, `causa_deficiencia` OU `cid10` preenchidos.

---

### 7️⃣ Filtros de Relacionamentos 🆕

| Filtro                         | Tipo                    | Descrição                        |
| ------------------------------ | ----------------------- | -------------------------------- |
| **Benefícios**                 | SelectFilter (múltiplo) | Filtra por benefícios vinculados |
| **Possui Carteirinha?**        | TernaryFilter           | Sim / Não / Todos                |
| **Possui Talento Cadastrado?** | TernaryFilter           | Sim / Não / Todos                |

---

### 8️⃣ Filtros de Contato 🆕

| Filtro               | Tipo          | Descrição         |
| -------------------- | ------------- | ----------------- |
| **Possui WhatsApp?** | TernaryFilter | Sim / Não / Todos |
| **Possui E-mail?**   | TernaryFilter | Sim / Não / Todos |
| **Possui Foto?**     | TernaryFilter | Sim / Não / Todos |

> **Uso prático:** Esses filtros são úteis para campanhas de comunicação (ex.: enviar mensagem apenas para quem tem WhatsApp).

---

## 🚀 Melhorias de Performance

### Eager Loading

Os relacionamentos são carregados antecipadamente para evitar N+1 queries:

```php
// No AssociadoResource::table()
->query(fn (Builder $query) => $query->with([
    'carteirinhas',
    'beneficios',
    'cid10',
]))
```

### Preload & Searchable

Filtros com muitas opções usam `preload()` para cache e `searchable()` para busca instantânea:

```php
SelectFilter::make('cidade')
    ->searchable()
    ->preload()
```

---

## 💡 Exemplos de Uso

### Exemplo 1: Encontrar crianças de 3 a 5 anos com deficiência

1. Abrir filtros
2. **Faixa de Idade (Personalizada)** → Min: 3, Max: 5
3. **Possui Deficiência?** → Sim
4. Aplicar

### Exemplo 2: Aniversariantes de Dezembro com WhatsApp

1. **Aniversariantes do Mês** → Dezembro
2. **Possui WhatsApp?** → Sim
3. Aplicar

### Exemplo 3: Associados sem carteirinha

1. **Possui Carteirinha?** → Não
2. Aplicar

### Exemplo 4: Jovens (18-25) OU idosos (60+) de área rural

1. **Faixa de Idade (Personalizada)**:
    - Intervalo 1: Min: 18, Max: 25
    - Intervalo 2: Min: 60, Max: (vazio)
2. **Perímetro** → Rural
3. Aplicar

---

## 🔧 Manutenção

### Adicionar novo filtro

1. Editar `/app/Filament/Filters/AssociadoFiltersTable.php`
2. Adicionar o filtro no array `filters()`
3. Documentar neste arquivo

### Atualizar cache de opções

Os filtros com `preload()` cacheiam as opções. Para limpar:

```bash
php artisan cache:clear
```

---

## 📊 Estatísticas

-   **Total de filtros:** 27
-   **Filtros com busca:** 15
-   **Filtros ternários:** 7
-   **Filtros de relacionamento:** 3
-   **Filtros de data:** 3

---

## 🐛 Troubleshooting

### Problema: "Não encontro crianças de 3-5 anos"

**Solução:** Use o filtro **Faixa de Idade (Personalizada)** com Min: 3, Max: 5.

### Problema: "Filtro de cidade não aparece opções"

**Solução:** Verifique se o serviço `MunicipioService` está retornando dados.

### Problema: "Filtro muito lento"

**Solução:** Verifique se o eager loading está ativo e se há índices nas colunas filtradas.

---

## 📝 Changelog

### v2.0 (23/11/2025)

-   ✅ Refatoração completa dos filtros
-   ✅ Adicionados 12 novos filtros
-   ✅ Melhorado filtro de idade com faixas customizadas
-   ✅ Adicionados filtros ternários para campos booleanos
-   ✅ Organização por categorias
-   ✅ Documentação completa

### v1.0 (anterior)

-   Filtros básicos de status, sexo, idade, etc.

---

## 🤝 Contribuindo

Para sugerir melhorias nos filtros, abra uma issue ou PR no repositório.
