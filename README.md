# Funcionamento do Projeto


- \Projetos\Controle-financeiro\public (nessa pasta ficam as rotas do php)
- \Projetos\Controle-financeiro\public\index.php(arquivo com as rotas
- \Projetos\Controle-financeiro\src (pasta com os arquivos de recursos do projeto)
- \Projetos\Controle-financeiro\src\Controller (pasta de arquivo para funções e abstrações)
- \Projetos\Controle-financeiro\src\View (pasta onde se cria as paginas do front-end)
- \Projetos\Controle-financeiro\src\View\index (forma de organização das páginas, divididas por tema exemplo: user, login, product etc)
- \Projetos\Controle-financeiro\src\View\index\index.php (primeiro arquivo de pagina frontend)
- \Projetos\Controle-financeiro\vendor (arquivos de recursos)
- \Projetos\Controle-financeiro\composer.json (normalmente não mechemos nisso)


# importante

### Código para rodar o projeto localmente

`php -S localhost:8000 -t public`

# 👥 Equipe e Responsabilidades

Para garantir a fluidez do desenvolvimento e evitar conflitos de código, cada membro possui responsabilidades específicas dentro da aplicação:

| Função | Responsável | Atribuições Principais |
| :--- | :--- | :--- |
| **🎨 Designer** | **Diorge** | Criação de layouts (Figma), UX/UI, design responsivo, prototipagem e manutenção da identidade visual da marca.  |
| **💻 Front-end** | **Daniel** | Transformação de layouts em código funcional (HTML/CSS/JS), consumo de APIs, interatividade, performance e acessibilidade. |
| **⚙️ Back-end** | **Douglas** | Desenvolvimento da lógica de negócios, criação de APIs, gestão de bancos de dados, segurança, autenticação e escalabilidade. |

---

### ⚠️ Orientações Importantes
* **Sincronia:** Sempre comunique mudanças em contratos de API ou alterações estruturais no layout.
* **Git:** Atente-se às suas áreas de atuação para evitar conflitos de merge.
* **UX:** O feedback do designer deve ser priorizado na implementação da interface.


## *Outros Detalhes*

### Atividades por função
 ***Designer***
É responsável por projetar, criar e manter a aparência, layout e usabilidade de sites e aplicações digitais, garantindo uma experiência do usuário (UX/UI) funcional, intuitiva e atraente em diversos dispositivos. define a estrutura, paleta de cores, tipografia e interatividade para alinhar a estética à identidade da marca.
As principais funções e responsabilidades incluem:
* Criação de Layouts: Projetar o visual de páginas web (wireframes e mockups) usando ferramentas como Adobe Photoshop, Illustrator ou Figma.
* Design Responsivo: Garantir que o site se adapte automaticamente a computadores, tablets e smartphones.
* Experiência do Usuário (UX): Focar na navegabilidade, criando menus, botões (call-to-action) e caminhos lógicos que facilitam o uso.
* Front-end básico: Conhecimentos em HTML, CSS e às vezes JavaScript para converter o design em páginas funcionais.
* Manutenção e Otimização: Atualizar conteúdos, otimizar imagens e testar a compatibilidade entre diferentes navegadores. 
* O web designer une criatividade artística com conceitos técnicos de TI para construir plataformas que ajudam os usuários a atingir seus objetivos, como compras ou leitura de conteúdo.

 ***Front-end***
É responsável por criar a interface visual e interativa de sites e aplicativos, transformando layouts de design (Figma, Adobe XD) em códigos funcionais (HTML, CSS, JavaScript). Seu objetivo principal é garantir uma boa experiência do usuário (UX), responsividade em diferentes dispositivos e desempenho ágil.
Principais Funções e Responsabilidades:
* Implementação de Interface (UI): Construir a estrutura (HTML) e o estilo (CSS) das páginas, garantindo que o design seja fiel ao planejado.
* Interatividade: Utilizar JavaScript para criar elementos dinâmicos, como menus, botões, animações e formulários interativos.
* Consumo de API (Back-end): Conectar o front-end ao back-end para exibir dados reais e enviar informações, garantindo que as funcionalidades operem corretamente.
* Responsividade e Acessibilidade: Garantir que o site funcione bem em desktops, celulares e tablets (responsivo) e seja acessível para usuários com deficiências.
* Otimização de Performance: Garantir o carregamento rápido da página para melhorar a experiência do usuário.
* Controle de Versão: Usar ferramentas como o Git para gerenciar alterações no código.

 ***Back-end***
É responsável pela "parte de trás" de aplicações, sites e sistemas, construindo a lógica, servidores e bancos de dados que o usuário não vê. Garante que o sistema funcione com segurança, rapidez e eficiência, processando dados e conectando a interface (front-end) à infraestrutura de regras de negócio.
Principais Funções e Responsabilidades:
* Desenvolvimento de Regras de Negócio: Criação da lógica de funcionamento, como algoritmos de checkout, sistemas de pagamento e fluxos de cadastro.
* Gestão de Bancos de Dados: Estruturação, armazenamento e recuperação de informações (SQL e NoSQL) de forma eficiente.
* Criação de APIs: Desenvolvimento de interfaces para comunicação entre o servidor e o front-end ou outros sistemas.
* Segurança e Autenticação: Implementação de medidas de proteção, criptografia, login e permissões de acesso para proteger dados.
* Otimização e Performance: Garantir que o sistema seja rápido e lide com alto volume de tráfego, utilizando cache e escalabilidade.
* Manutenção e Correção de Bugs: Depuração de código para encontrar e corrigir erros na infraestrutura.
