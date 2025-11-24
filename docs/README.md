# 📚 Estrutura de Documentação do Sistema APPD

Este diretório contém toda a documentação do sistema, organizada em duas categorias principais:

---

## 📁 Estrutura de Pastas

```
docs/
├── releases/          🚀 Notas de Versão
│   └── *.md          Arquivos de release (ex: v2.0-sistema-documentacao.md)
│
└── technical/         💻 Documentação Técnica
    └── *.md          Guias técnicos e referências
```

---

## 🚀 Releases (`docs/releases/`)

**Propósito:** Notas de versão, changelogs e anúncios de novas funcionalidades

**Nomenclatura:** `vX.Y-nome-descritivo.md`

**Exemplos:**

-   `v2.0-sistema-documentacao.md`
-   `v2.1-novos-filtros.md`
-   `v3.0-refatoracao-completa.md`

**Conteúdo típico:**

-   ✅ O que foi implementado
-   ✅ Melhorias e correções
-   ✅ Breaking changes
-   ✅ Próximos passos
-   ✅ Métricas de impacto

**Acesso:** `/admin/releases`

---

## 💻 Documentação Técnica (`docs/technical/`)

**Propósito:** Guias técnicos, referências de API, tutoriais e documentação de código

**Categorização automática por nome:**

| Padrão no Nome            | Categoria     | Exemplos                |
| ------------------------- | ------------- | ----------------------- |
| `filtro*`                 | 🔍 Filtros    | `filtros-associados.md` |
| `api*`                    | ⚡ API        | `api-reference.md`      |
| `database*`, `migration*` | 💾 Database   | `database-schema.md`    |
| `deploy*`, `install*`     | 🚀 Deployment | `deployment-guide.md`   |
| Outros                    | 📄 Outros     | `guia-rapido-*.md`      |

**Conteúdo típico:**

-   📖 Guias passo a passo
-   🔧 Referências técnicas
-   💡 Exemplos de código
-   🐛 Troubleshooting
-   ⚙️ Configurações

**Acesso:** `/admin/docs-tecnicas`

---

## 📝 Como Adicionar Documentação

### Adicionar uma Release:

```bash
# 1. Criar arquivo na pasta releases
touch docs/releases/v2.1-nova-funcionalidade.md

# 2. Escrever o conteúdo em Markdown
# 3. O arquivo aparecerá automaticamente em /admin/releases
```

### Adicionar Documentação Técnica:

```bash
# 1. Criar arquivo na pasta technical
touch docs/technical/filtros-avancados.md

# 2. Escrever o conteúdo em Markdown
# 3. O arquivo será categorizado automaticamente
# 4. Aparecerá em /admin/docs-tecnicas
```

---

## ✍️ Formato Markdown

Ambos os tipos suportam **GitHub Flavored Markdown** completo:

-   ✅ Headings (`#`, `##`, `###`)
-   ✅ Listas ordenadas e não ordenadas
-   ✅ Tabelas
-   ✅ Code blocks com syntax highlighting
-   ✅ Blockquotes
-   ✅ Links e imagens
-   ✅ Emojis
-   ✅ Task lists

**Exemplo:**

```markdown
# 🚀 Release v2.0 - Sistema de Documentação

## ✨ Novidades

-   **Filtros de Idade:** 16 opções organizadas
-   **Central de Documentação:** 3 páginas integradas

## 📊 Métricas

| Métrica | Antes | Depois |
| ------- | ----- | ------ |
| Filtros | 6     | 16     |

## 💻 Código

\`\`\`php
SelectFilter::make('idade')
->options([...])
\`\`\`
```

---

## 🎨 Interface

### Releases

-   **Cor:** Verde (🟢)
-   **Ícone:** Foguete (🚀)
-   **Layout:** Lista lateral simples
-   **Ordenação:** Mais recentes primeiro

### Docs Técnicas

-   **Cor:** Azul (🔵)
-   **Ícone:** Code Bracket (💻)
-   **Layout:** Sidebar com categorias
-   **Organização:** Por tipo de documento

---

## 🔗 Acesso Rápido

1. **Dropdown na Topbar:**

    - Clique em "Docs" no canto superior direito
    - Escolha entre:
        - 📖 Documentação (guias interativos)
        - 🚀 Releases (notas de versão)
        - 💻 Docs Técnicas (referências)

2. **URLs Diretas:**
    - `/admin/documentacao` - Guias interativos
    - `/admin/releases` - Notas de versão
    - `/admin/docs-tecnicas` - Documentação técnica

---

## 📦 Dependências

-   **league/commonmark** - Parser de Markdown
-   Suporte a GitHub Flavored Markdown
-   Suporte a tabelas
-   Syntax highlighting automático

---

**Última atualização:** 24/11/2025  
**Versão:** 2.0
