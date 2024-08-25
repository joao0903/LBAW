-- Inserir dados de exemplo na tabela 'users'
INSERT INTO users (username, password, firstName, lastName, email, reputation) VALUES
    ('usuario1', 'senha1', 'Primeiro', 'Usuário', 'usuario1@email.com', 100),
    ('usuario2', 'senha2', 'Segundo', 'Usuário', 'usuario2@email.com', 75),
    ('usuario3', 'senha2', 'Segundo', 'Usuário', 'usuario3@email.com', 75),
    ('usuario4', 'senha2', 'Segundo', 'Usuário', 'usuario4@email.com', 75),
    ('usuario5', 'senha2', 'Segundo', 'Usuário', 'usuario5@email.com', 75),
    ('usuario6', 'senha2', 'Segundo', 'Usuário', 'usuario6@email.com', 75),
    ('usuario7', 'senha2', 'Segundo', 'Usuário', 'usuario7@email.com', 75),
    ('usuario8', 'senha2', 'Segundo', 'Usuário', 'usuario8@email.com', 75),
    ('usuario9', 'senha2', 'Segundo', 'Usuário', 'usuario9@email.com', 75),
    ('usuario10', 'senha3', 'Terceiro', 'Usuário', 'usuario10@email.com', 50);

-- Inserir tópicos
INSERT INTO topic (title, description) VALUES
    ('Atualidade', 'Notícias sobre atualidades universitárias'),
    ('Desporto', 'Notícias sobre esportes universitários'),
    ('Portugal', 'Notícias sobre o mundo universitário em Portugal'),
    ('Mundo', 'Notícias sobre o mundo universitário internacional'),
    ('Opinião', 'Artigos de opinião sobre o mundo universitário');

-- Inserir notícias fictícias na tabela 'post'
INSERT INTO post ("date", popularity, title, description, id_user, id_topic)
VALUES
    ('2023-10-19 09:00:00', 100, 'Pesquisadores descobrem nova espécie de dinossauro na Universidade X', 'Cientistas da Universidade X descobriram os ossos de uma nova espécie de dinossauro no campus.', 1, 1),
    ('2023-10-18 14:30:00', 90, 'Alunos da Universidade Y vencem competição de robótica', 'A equipe de robótica da Universidade Y conquistou o primeiro lugar na competição nacional.', 2, 2),
    ('2023-10-17 11:15:00', 80, 'Universidade Z inaugura novo laboratório de pesquisa', 'A Universidade Z inaugurou seu laboratório de pesquisa de última geração para estudantes e professores.', 3, 3),
    ('2023-10-16 16:45:00', 70, 'Estudante da Universidade W ganha prêmio de melhor tese de mestrado', 'Um aluno da Universidade W recebeu reconhecimento por sua tese de mestrado excepcional sobre inovação tecnológica.', 4, 4),
    ('2023-10-15 12:20:00', 60, 'Conferência internacional sobre ciências sociais na Universidade V', 'A Universidade V sediou uma conferência internacional de renome sobre ciências sociais.', 5, 5),
    ('2023-10-14 09:30:00', 50, 'Projeto de voluntariado da Universidade X beneficia comunidade local', 'Estudantes da Universidade X realizaram um projeto de voluntariado que teve um impacto positivo na comunidade.', 6, 1),
    ('2023-10-13 15:00:00', 40, 'Alunos da Universidade Y lançam aplicativo de ensino à distância', 'Estudantes empreendedores da Universidade Y criaram um aplicativo inovador para aulas online.', 7, 2),
    ('2023-10-12 10:10:00', 30, 'Universidade Z recebe doação significativa para bolsas de estudo', 'Um benfeitor generoso doou uma grande quantia para a Universidade Z, permitindo mais bolsas de estudo.', 8, 3),
    ('2023-10-11 13:45:00', 20, 'Estudante da Universidade W eleito presidente da associação estudantil', 'Um aluno da Universidade W foi eleito presidente da associação estudantil, prometendo melhorias para seus colegas.', 9, 4),
    ('2023-10-10 17:25:00', 10, 'Professor da Universidade V recebe prêmio de excelência em pesquisa', 'Um professor da Universidade V foi reconhecido internacionalmente por sua pesquisa inovadora.', 10, 5);

-- Insira algumas tags relacionadas às notícias na tabela tag (opcional)
INSERT INTO tag (title, description)
VALUES
    ('Universidade', 'Uma notícia sobre a universidade'),
    ('Desporto Universitário', 'Uma notícia sobre a desporto universitário'),
    ('Ensino Superior', 'Uma notícia sobre o ensino superior'),
    ('Estudantes Internacionais', 'Uma notícia sobre os estudantes internacionais'),
    ('Ranking Universitário', 'Uma notícia sobre o ranking universitário');

-- Inserir dados de exemplo na tabela 'comment'
INSERT INTO comment (content, "date", likes, id_post, id_user) VALUES
    ('Comentário 1', NOW(), 5, 1, 1),
    ('Comentário 2', NOW(), 10, 1, 2),
    ('Comentário 3', NOW(), 8, 2, 1);

-- Inserir dados de exemplo na tabela 'notification'
INSERT INTO notification (content, "date", type, id_user) VALUES
    ('Notificação 1', NOW(), 'comment', 1),
    ('Notificação 2', NOW(), 'rating', 2),
    ('Notificação 3', NOW(), 'post', 3);

-- Inserir dados de exemplo na tabela 'image'
INSERT INTO image (image, typeOfImage, id_post, id_user) VALUES
    ('imagem1.jpg', 'jpg', 1, 1),
    ('imagem2.png', 'png', 2, 2),
    ('imagem3.jpg', 'jpg', 3, 3);

-- Inserir dados de exemplo na tabela 'vote'
INSERT INTO vote (rating, id_post, id_user) VALUES
    (1, 1, 2),
    (-1, 2, 3),
    (1, 3, 1);

-- Inserir dados de exemplo na tabela 'saves'
INSERT INTO saves (id_post, id_user) VALUES
    (1, 2),
    (2, 3),
    (3, 1);

-- Inserir dados de exemplo na tabela 'followUser'
INSERT INTO followUser (id_user1, id_user2) VALUES
    (1, 2),
    (2, 3),
    (3, 1);

-- Inserir dados de exemplo na tabela 'ban'
INSERT INTO ban (reason, id_user) VALUES
    ('Comportamento inadequado', 1),
    ('Spam', 2);

-- Inserir dados de exemplo na tabela 'admin' e 'journalist'
INSERT INTO admin (id_admin) VALUES
    (1),
    (2);

INSERT INTO journalist (id_journalist) VALUES
    (3),
    (1);

-- Inserir dados de exemplo na tabela 'followTag'
INSERT INTO followTag (id_user, id_tag) VALUES
    (1, 1),
    (2, 2),
    (3, 3);

-- Inserir elementos na tabela 'postWithTag' associando postagens a tags
INSERT INTO postWithTag (id_post, id_tag)
VALUES
    (1, 1), -- Post 1 está associado à Tag 1
    (2, 2), -- Post 2 está associado à Tag 2
    (3, 3), -- Post 3 está associado à Tag 3
    (4, 4), -- Post 4 está associado à Tag 4
    (5, 5), -- Post 5 está associado à Tag 5
    (6, 1), -- Post 6 está associado à Tag 1
    (7, 2), -- Post 7 está associado à Tag 2
    (8, 3), -- Post 8 está associado à Tag 3
    (9, 4), -- Post 9 está associado à Tag 4
    (10, 5); -- Post 10 está associado à Tag 5
