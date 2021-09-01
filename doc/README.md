# Etapas de desenvolvimento
Todos os passos e requisitos necessários para a elaboração desse sistema podem ser encontrados aqui, bem como os arquivos utilizados.

# Levantamento de requisitos

A primeira etapa para a elaboração desse projeto foi pesquisar a necessidade de tal sistema. Para isso, foi feita uma consulta com os profissionais de TI que atuam na DTI (Divisão de Tecnologia da Informação) da UFC - campus Sobral, os quais relataram que o setor carecia de um sistema virtual de comunicação entre técnico - cliente, uma vez que dependiam da comunicação via telefone ou e-mail. Nesse caso, o funcionário que quisesse um atendimento sobre um problema técnico em determinada área do campi teria que ligar para a DTI solicitando um atendimento.Tanto a solicitação, como o prazo de atendimento e demais informações eram feitas de maneira onerosa, muitas vezes necessitando o tramitamento desnecessário das pessoas envolvidas no serviço.

Nesse sentido, os profissionais relataram todos os serviços que um sistema virtual devesse abarcar, tais como:
- Abrir chamado (implementado com suceso)
- Consultar chamado (implementado com suceso)
- Excluir chamado (implementado com suceso)
- Alterar status do chamado (implementado com suceso)
- Alterar o técnico responsável por um chamado (implementado com suceso)
- Informar ao cliente a conclusão do chamado (Ainda não implementado)
- Sistema de bate-papo entre técnico e cliente (implementado com suceso)

# Tecnologias

A escolha das linguagens de programação e dos editores de código tiveram como base uma menor curva de aprendizagem. Desse modo, utilizou-se:

- *Front End* : para o front end utilizou-se linguagem de marcação HTML5 e CSS3. E Bootstrap como framework CSS
- *Back End* : para o back end da aplicação utilizou-se a liguagem PHP, por ser de fácil aprendizagem, por implementar recursos de segurança na validação de senhas, por fazer requisições do tipo POST e por implementar a metodologia PDO para comunicação com o banco de dados.
- *Banco de Dados* : Foi utilizado o MySQL como Sistema de Gerenciamento de Banco de Dados relacional, e, como servidor local, utilizou-se o Apache.


# Arquitetura

O projeto foi dividido em 3 partes:

1. front End : O Java Script faz o intermédio entre o DOM da página e o back end em php.
2. Back End : Aqui o PHP faz a comunicação entre as informações que o usuário requisita/envia e o banco de dados, por meio do PDO do php. 
3. Banco de Dados : Aqui acontece a manipulação dos dados no BD, por meio do CRUD da aplicação.

Foi adotado a arquitetura MVC para uma melhor distribuição e organização do código. Além disso, foram usados apenas um framework, o Bootstrap.


# UML

Informações sobre a UML podem ser encontradas no diretório [UML](https://github.com/TailsonAlves/Trabalho-Final_Eng.Soft-2021.1/tree/main/doc/UML)

# Feedback

O protótipo do sistema foi apresentado aos tecnicos da Divisão de Tecnologia da UFC de Sobral pelo aluno Tailson Alves. Ao final da conferência, o sistema passou por diversos testes de caso a fim de encontrar redundâncias, erros ou falta de implementação. Em resumo, o sistema atendeu a maioria dos requisitos, porém apresentou algumas funcionalidades que, embora não impacte no propósito do sistema, seriam opcionais ou não seriam usadas, além de que foi ressaltado a falta da implementação do recurso de confirmação de conclusão de chamado através do email.

