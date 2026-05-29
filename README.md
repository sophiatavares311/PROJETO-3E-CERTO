## LINKS E RECURSOS DO PROJETO
Os links abaixo dão acesso imediato aos ambientes oficiais de produção e acompanhamento de tarefas deste MVP:

* Acessar o Site Oficial EduConnect https://educonnect-3.up.railway.app/home.php
* Acessar o Quadro do Trello do Projeto https://trello.com/b/Y6ctqSr5/projeto-3e-solucoes

------------------------------
## MANUAL DE SUPORTE TÉCNICO – SISTEMA EDUCONNECT
Este documento descreve as etapas necessárias para a replicação local, manutenção e compreensão da infraestrutura de nuvem aplicadas no MVP da plataforma EduConnect.
------------------------------
## 1. CONFIGURAÇÃO E EXECUÇÃO LOCAL (PASSO A PASSO)
O sistema foi desenvolvido utilizando PHP nativo estruturado e banco de dados MySQL, sendo totalmente compatível com pacotes integrados de servidores locais como XAMPP, Laragon ou WampServer baseados em ambiente Windows ou Linux.
## Pré-requisitos

* Servidor Web Apache.
* Interpretador PHP na versão 8.2 ou superior.
* Servidor de Banco de Dados MySQL ou MariaDB.
* Navegador Web (Chrome, Firefox ou Edge).

## Etapa 1: Organização dos Arquivos

   1. Localize o diretório raiz de publicação do seu servidor local:
   * No XAMPP: C:\xampp\htdocs\
      * No Laragon: C:\laragon\www\
   2. Crie uma pasta chamada educonnect dentro desse diretório.
   3. Copie todos os arquivos do repositório (home.php, alunos.php, mentorias.php, grade-mentorias.php, conexao.php, login.php, logout.php e estilo.css) para dentro desta pasta criada.

## Etapa 2: Inicialização dos Serviços

   1. Abra o painel de controle do seu servidor local (ex: XAMPP Control Panel).
   2. Clique no botão de inicialização dos módulos Apache e MySQL.
   3. Certifique-se de que os status de ambos constam como ativos ou em execução.

## Etapa 3: Instalação do Banco de Dados Local

   1. Abra o seu navegador e acesse a ferramenta de gerenciamento através do link: http://localhost/phpmyadmin/
   2. Clique na aba Banco de Dados e crie um novo banco com o nome exato de educonnect, utilizando a codificação utf8mb4_general_ci.
   3. Selecione o banco educonnect na lista à esquerda, navegue até a aba SQL, cole o script de estruturação contido na seção 2 deste manual e execute a consulta.

## Etapa 4: Acesso ao Sistema

   1. Abra o navegador de internet.
   2. Insira a URL na barra de endereços: http://localhost/educonnect/home.php
   3. Para acessar os módulos de gerenciamento restritos (alunos.php e mentorias.php), utilize as credenciais padrão de homologação definidas no código:
   * Usuário: admin
      * Senha: admin123
   
------------------------------
## 2. ESTRUTURAÇÃO DO BANCO DE DATOS MYSQL
O banco de dados foi modelado seguindo a terceira forma normal para garantir consistência e evitar redundância. O relacionamento é do tipo 1 para N (um aluno pode possuir múltiplas mentorias vinculadas, mas uma mentoria pertence a apenas um aluno).
## Script de Criação das Tabelas

CREATE DATABASE IF NOT EXISTS educonnect;
USE educonnect;
CREATE TABLE alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    serie VARCHAR(50) NOT NULL,
    data_nascimento DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE mentorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    mentor VARCHAR(100) NOT NULL,
    data_mentoria DATE NOT NULL,
    modalidade ENUM('Presencial', 'Online') NOT NULL,
    link_local VARCHAR(255) NOT NULL,
    resumo TEXT NOT NULL,
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

## Detalhamento Técnico das Tabelas## Tabela: alunos

* id: Chave primária com incremento automático, garantindo um identificador numérico único para cada estudante.
* nome: Armazena o nome completo do estudante com limite de 100 caracteres.
* email: Campo de texto restrito com índice único (UNIQUE), impedindo a duplicação de cadastros sob o mesmo endereço eletrônico.
* serie: Campo de seleção de texto fechado para padronização dos anos escolares atendidos.
* data_nascimento: Campo do tipo data para registro da idade dos estudantes atendidos.

## Tabela: mentorias

* id: Chave primária autoincrementada da tabela de histórico.
* aluno_id: Chave estrangeira que aponta diretamente para o id da tabela alunos, estabelecendo a integridade referencial.
* mentor: Nome do voluntário responsável pela aplicação do reforço escolar.
* data_mentoria: Data em que a atividade de mentoria foi concluída.
* modalidade: Campo enumerado restringindo as opções do sistema exclusivamente para os valores Presencial ou Online.
* link_local: Campo dinâmico que recebe o endereço de salas virtuais ou a identificação física da sala de aula escolar.
* resumo: Campo do tipo texto longo para o sumário descritivo das matérias revisadas.
* ON DELETE CASCADE: Regra de restrição configurada para que, caso um aluno seja removido do sistema, todo o histórico de mentorias atrelado a ele seja limpo automaticamente pelo banco, evitando dados órfãos.

------------------------------
## 3. PASSOS E CONFIGURAÇÕES DA INFRAESTRUTURA EM NUVEM (RAILWAY)
A publicação do sistema em ambiente Linux de produção foi realizada de forma integrada utilizando a plataforma de nuvem Railway, vinculada a um pipeline de implantação contínua (CI/CD) a partir do GitHub.
## Configuração do Projeto e Variáveis de Ambiente
O contêiner Linux do Railway foi provisionado para rodar de forma acoplada, dividindo o espaço em dois serviços internos operando em uma rede isolada.

   1. Provisionamento do Banco de Dados: Foi adicionada uma instância isolada do plugin oficial do MySQL dentro do projeto do Railway, gerando credenciais automáticas de comunicação interna criptografada.
   2. Declaração de Variáveis de Ambiente: No serviço principal da aplicação web, foram injetadas variáveis dinâmicas de ambiente referenciadas para que o PHP obtenha as credenciais do banco sem expor senhas diretamente no código fonte. As variáveis configuradas foram:
   * MYSQLHOST apontando para a URL interna do servidor de banco.
      * MYSQLPORT definindo a porta padrão de escuta do banco.
      * MYSQLDATABASE definindo o nome lógico da base de dados.
      * MYSQLUSER definindo o usuário de administração em nuvem.
      * MYSQLPASSWORD injetando a chave de acesso criptografada do banco.
   3. Instalação de Módulos PHP: Para o ambiente Linux reconhecer comandos de banco de dados nativos, foram ativadas variáveis globais de compilação instruindo a engine Nixpacks do Railway a compilar os drivers necessários:
   * NIXPACKS_PHP_EXTENSIONS configurada com o valor pdo,pdo_mysql.
      * NIXPACKS_PHP_VERSION travada rigidamente na versão 8.2.
   
## Rotas de Deploy e Redirecionamento de Rede

* O Railway monitora o repositório do GitHub em tempo real. A cada nova atualização na ramificação principal (main), o servidor Linux reconstrói o ambiente em segundos.
* O arquivo inicializador padrão index.php foi adicionado na raiz do servidor para interceptar requisições HTTP da porta principal do domínio e realizar um redirecionamento dinâmico via cabeçalho para a página pública oficial do portal (home.php).
* O domínio público foi gerado nas propriedades de rede da aba Settings do Railway na modalidade TLS/SSL activa de forma nativa, convertendo todas as requisições HTTP do cliente para conexões seguras HTTPS.
