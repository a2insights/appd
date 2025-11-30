# 🎯 Guia Rápido - Filtros de Associados

> **Acesso:** `/admin/associados` → Botão "Filtros"  
> **Versão:** 2.0

---

## 🔍 Filtros Mais Usados

### 1. Encontrar crianças de 3 a 5 anos

```
Faixa de Idade (Personalizada)
├─ Min: 3
└─ Max: 5
```

### 2. Aniversariantes do mês com WhatsApp

```
Aniversariantes do Mês: [Selecione o mês]
Possui WhatsApp?: Sim
```

### 3. Associados sem carteirinha

```
Possui Carteirinha?: Não
```

### 4. PcDs com benefícios

```
Possui Deficiência?: Sim
Benefícios: [Selecione os benefícios]
```

### 5. Jovens (18-25) OU idosos (60+)

```
Faixa de Idade (Personalizada)
├─ Intervalo 1: Min: 18, Max: 25
└─ Intervalo 2: Min: 60, Max: (vazio)
```

### 6. Associados de área rural sem e-mail

```
Perímetro: Rural
Possui E-mail?: Não
```

---

## 📋 Categorias de Filtros

### 🆔 Identificação

-   Status
-   Sexo
-   Declaração Sexual
-   Estado Civil

### 📅 Datas

-   Data de Cadastro
-   Data de Nascimento
-   Data de Renovação de Carteirinha

### 🎂 Idade

-   **Faixa de Idade (Rápida)** - 6 opções pré-definidas
-   **Faixa de Idade (Personalizada)** - Intervalos customizados
-   **Aniversariantes do Mês** - Janeiro a Dezembro

### 📍 Localização

-   UF de Naturalidade
-   Cidade (Endereço)
-   Perímetro (Urbano/Rural)

### 👥 Sociodemográficos

-   Religião
-   Escolaridade
-   Raça/Cor
-   Ocupação

### ♿ Deficiência

-   Possui Deficiência? (Sim/Não)
-   Tipo de Deficiência
-   Causa da Deficiência
-   Aparelhos Utilizados
-   CID-10
-   Possui CRM? (Sim/Não)

### 🔗 Relacionamentos

-   Benefícios
-   Possui Carteirinha? (Sim/Não)
-   Possui Talento Cadastrado? (Sim/Não)

### 📞 Contato

-   Possui WhatsApp? (Sim/Não)
-   Possui E-mail? (Sim/Não)
-   Possui Foto? (Sim/Não)

---

## 💡 Dicas de Uso

### ✅ Combine múltiplos filtros

Os filtros funcionam com operador **AND** (E), exceto o filtro de idade personalizada que permite múltiplos intervalos com **OR** (OU).

### ✅ Use a busca nos filtros

Filtros com muitas opções (Cidade, CID-10, Benefícios) têm busca integrada. Digite para encontrar rapidamente.

### ✅ Salve suas buscas frequentes

Anote as combinações de filtros que você usa com frequência para agilizar o trabalho.

### ✅ Limpe os filtros

Clique no "X" de cada chip de filtro ativo ou use o botão "Limpar filtros" para resetar.

---

## 🐛 Problemas Comuns

### "Não encontro crianças de 3-5 anos"

✅ Use **Faixa de Idade (Personalizada)**, não a "Rápida"

### "Filtro de cidade está vazio"

✅ Aguarde o carregamento ou recarregue a página

### "Filtro muito lento"

✅ Evite combinar muitos filtros de relacionamento ao mesmo tempo

---

## 📊 Atalhos de Teclado

| Tecla | Ação                  |
| ----- | --------------------- |
| `F`   | Abrir/Fechar filtros  |
| `Esc` | Fechar filtros        |
| `Tab` | Navegar entre filtros |

---

## 📞 Suporte

Dúvidas? Consulte a **documentação completa** em:
`docs/filtros-associados.md`

---

**Última atualização:** 23/11/2025
