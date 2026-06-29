# Skill de Identidade Visual

## Objetivo

Definir a identidade visual da aplicação, garantindo uma experiência consistente e imersiva no universo Shin Megami Tensei.

## Paleta de Cores

- **Fundo escuro**: `#1a1a1a` ou `#0d0d0d`
- **Texto principal**: `#f0f0f0` (branco suave)
- **Destaques (vermelho)**: `#cc0000` ou `#ff1a1a` (para botões, links, cabeçalhos)
- **Destaques (amarelo)**: `#e6b800` ou `#ffd700` (para ícones, badges, elementos de destaque)
- **Cinza médio**: `#444444` para cards, bordas, separadores.
- **Cinza claro**: `#888888` para textos secundários.

## Tipografia

- **Fonte principal**: `'Roboto', sans-serif` para legibilidade.
- **Títulos**: `'Cinzel', serif` ou `'Uncial Antiqua', cursive` (opcional, para um toque medieval/místico).
- **Hierarquia**:
  - H1: 2.5rem, bold.
  - H2: 2rem, bold.
  - H3: 1.75rem, semibold.
  - Corpo: 1rem, regular.

## Componentes Visuais

- **Cards**: fundo `#2a2a2a`, bordas arredondadas (8px), padding 20px, sombra leve.
- **Botões**:
  - Primário: fundo vermelho (`#cc0000`), texto branco, hover escurece.
  - Secundário: fundo cinza escuro (`#444`), texto branco.
  - Outline: borda vermelha, fundo transparente, texto vermelho.
- **Tabelas**: fundo `#222`, zebra com `#2a2a2a`, cabeçalho com fundo `#333`.
- **Formulários**: inputs com fundo `#333`, borda `#555`, texto branco, foco com borda vermelha.
- **Alertas**: sucesso (verde), erro (vermelho), aviso (amarelo).

## Responsividade

- Mobile first: usar grid do Bootstrap ou flexbox.
- Quebras: 576px (sm), 768px (md), 992px (lg), 1200px (xl).
- Menus colapsáveis no mobile.
- Cards e tabelas com scroll horizontal em telas pequenas.

## Experiência do Usuário (UX)

- **Feedback visual**: hover em botões, transições suaves.
- **Mensagens**: alertas com tempo de exibição (ex: fade out após 5s).
- **Navegação**: menu claro, breadcrumbs (se necessário).
- **Carregamento**: indicadores de loading (spinners) em ações assíncronas.
- **Acessibilidade**: contraste de cores adequado, labels em formulários, atributos `alt` em imagens.

## Padronização de Layout

- **Header**: logo à esquerda, navegação à direita (login/logout, perfil).
- **Conteúdo principal**: container centralizado (max-width 1200px).
- **Footer**: informações de copyright, links para redes sociais (opcional).
- **Favicon**: ícone relacionado ao tema (ex: pentagrama, lua).

## Exemplo de Layout

```html
<!-- layout principal -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SMT Team Builder')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        /* Cores e estilos globais */
        body { background-color: #0d0d0d; color: #f0f0f0; font-family: 'Roboto', sans-serif; }
        .btn-primary { background-color: #cc0000; border: none; }
        .btn-primary:hover { background-color: #990000; }
        .card { background-color: #2a2a2a; border-radius: 8px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.5); }
        /* ... */
    </style>
</head>
<body>
    @include('partials.header')
    <main class="container py-4">
        @yield('content')
    </main>
    @include('partials.footer')
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
```
Conclusão

Seguir esta Skill garante que todas as telas da aplicação mantenham uma identidade visual coesa, atraente e funcional, adequada ao tema Shin Megami Tensei.