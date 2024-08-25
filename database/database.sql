create schema if not exists lbaw2331;

---------------------------------------

DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS post CASCADE;
DROP TABLE IF EXISTS topic CASCADE;
DROP TABLE IF EXISTS tag CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS image CASCADE;
DROP TABLE IF EXISTS vote CASCADE;
DROP TABLE IF EXISTS saves CASCADE;
DROP TABLE IF EXISTS followUser CASCADE;
DROP TABLE IF EXISTS ban CASCADE;
DROP TABLE IF EXISTS admin CASCADE;
DROP TABLE IF EXISTS journalist CASCADE;
DROP TABLE IF EXISTS followTag CASCADE;
DROP TABLE IF EXISTS postWithTag CASCADE;
DROP TABLE IF EXISTS voteComment CASCADE;

DROP DOMAIN IF EXISTS "Today" CASCADE;
DROP TYPE IF EXISTS notification_type CASCADE;

DROP TRIGGER IF EXISTS prevent_self_follow ON followUser;
DROP TRIGGER IF EXISTS prevent_post_delete ON post;
DROP TRIGGER IF EXISTS prevent_comment_delete ON comment;
DROP TRIGGER IF EXISTS comment_notification ON comment;
DROP TRIGGER IF EXISTS vote_notification ON vote;
DROP TRIGGER IF EXISTS new_post_notification ON post;

DROP FUNCTION IF EXISTS prevent_self_follow();
DROP FUNCTION IF EXISTS prevent_post_deletion();
DROP FUNCTION IF EXISTS prevent_comment_delete();
DROP FUNCTION IF EXISTS notify_user_on_comment();
DROP FUNCTION IF EXISTS notify_user_on_vote();
DROP FUNCTION IF EXISTS notify_new_post();

CREATE DOMAIN "Today" AS date NOT NULL DEFAULT ('now'::text)::date;
CREATE TYPE notification_type AS ENUM ('comment', 'rating', 'post');

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR NOT NULL UNIQUE,
    password VARCHAR NOT NULL,              
    firstName VARCHAR NOT NULL, 
    lastName VARCHAR NOT NULL,
    email VARCHAR NOT NULL UNIQUE,
    reputation INTEGER  DEFAULT 0 NOT NULL,
    username_tsvectors TSVECTOR 
);

CREATE TABLE topic (
    id SERIAL PRIMARY KEY,
    title VARCHAR NOT NULL UNIQUE,
    description VARCHAR,
    type VARCHAR NOT NULL,
    title_tsvectors TSVECTOR
);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    "date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    popularity INTEGER NOT NULL,
    title VARCHAR NOT NULL,
    description VARCHAR,
    id_user INTEGER REFERENCES users(id) ON DELETE CASCADE,
    id_topic INTEGER REFERENCES topic(id) ON DELETE CASCADE,
    title_tsvectors TSVECTOR 
);

CREATE TABLE tag (
    id SERIAL PRIMARY KEY,
    title VARCHAR NOT NULL UNIQUE,
    description VARCHAR
);

CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    content VARCHAR NOT NULL,
    "date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    likes INTEGER,
    id_post INTEGER REFERENCES post(id) ON DELETE CASCADE,
    id_user INTEGER REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    content VARCHAR NOT NULL,
    "date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    type notification_type NOT NULL,
    id_user INTEGER REFERENCES users(id) ON DELETE CASCADE,
    id_post INTEGER REFERENCES post(id) ON DELETE CASCADE

);

CREATE TABLE image (
    id SERIAL PRIMARY KEY,
    imagePath VARCHAR NOT NULL,
    typeOfImage VARCHAR NOT NULL,
    id_post INTEGER REFERENCES post(id) ON DELETE CASCADE,
    id_user INTEGER REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE vote (
    id SERIAL PRIMARY KEY,
    rating INTEGER NOT NULL,
    CONSTRAINT rating CHECK ((rating = -1) OR (rating = 1)),
    id_post INTEGER REFERENCES post(id) ON DELETE CASCADE,
    id_user INTEGER REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE voteComment (
    id_user INTEGER REFERENCES users(id) ON DELETE CASCADE,
    id_comment INTEGER REFERENCES comment(id) ON DELETE CASCADE,
    PRIMARY KEY(id_user, id_comment)
);

CREATE TABLE saves (
    id_post INTEGER REFERENCES post(id) ON DELETE CASCADE,
    id_user INTEGER REFERENCES users(id) ON DELETE CASCADE,
    PRIMARY KEY(id_post, id_user)
);

CREATE TABLE followUser (
    id_user1 INTEGER REFERENCES users(id) ON DELETE CASCADE,
    id_user2 INTEGER REFERENCES users(id) ON DELETE CASCADE,
    PRIMARY KEY(id_user1, id_user2)
);

CREATE TABLE ban (
    id SERIAL PRIMARY KEY,
    reason VARCHAR NOT NULL,
  	id_user INTEGER REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE admin (
    id_admin INTEGER PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE journalist (
    id_journalist INTEGER PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE followTag (
    id_user INTEGER REFERENCES users(id) ON DELETE CASCADE,
    id_tag INTEGER REFERENCES users(id) ON DELETE CASCADE,
    PRIMARY KEY(id_user, id_tag)
);

CREATE TABLE postWithTag (
    id_post INTEGER REFERENCES post(id) ON DELETE CASCADE,
    id_tag INTEGER REFERENCES tag(id) ON DELETE CASCADE,
    PRIMARY KEY(id_post, id_tag)
);

-- Inserir dados de exemplo na tabela 'users'
INSERT INTO users (username, password, firstName, lastName, email, reputation, username_tsvectors) VALUES
    ('usuario1', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Primeiro', 'Usuário', 'usuario1@email.com', 2, setweight(to_tsvector('english', 'usuario1'), 'A')),
    ('usuario2', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Segundo', 'Usuário', 'usuario2@email.com', -1, setweight(to_tsvector('english', 'usuario2'), 'A')),
    ('usuario3', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Segundo', 'Usuário', 'usuario3@email.com', 1, setweight(to_tsvector('english', 'usuario3'), 'A')),
    ('usuario4', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Segundo', 'Usuário', 'usuario4@email.com', 0, setweight(to_tsvector('english', 'usuario4'), 'A')),
    ('usuario5', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Segundo', 'Usuário', 'usuario5@email.com', 0, setweight(to_tsvector('english', 'usuario5'), 'A')),
    ('usuario6', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Segundo', 'Usuário', 'usuario6@email.com', 0, setweight(to_tsvector('english', 'usuario6'), 'A')),
    ('usuario7', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Segundo', 'Usuário', 'usuario7@email.com', 0, setweight(to_tsvector('english', 'usuario7'), 'A')),
    ('usuario8', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Segundo', 'Usuário', 'usuario8@email.com', 0, setweight(to_tsvector('english', 'usuario8'), 'A')),
    ('usuario9', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Segundo', 'Usuário', 'usuario9@email.com', 0, setweight(to_tsvector('english', 'usuario9'), 'A')),
    ('usuario10', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Terceiro', 'Usuário', 'usuario10@email.com', 0, setweight(to_tsvector('english', 'usuario10'), 'A'));


-- Inserir tópicos
INSERT INTO topic (title, description, type, title_tsvectors) VALUES
    ('Atualidade', 'Notícias sobre atualidades universitárias','Accept', setweight(to_tsvector('english', 'Atualidade'), 'A')),
    ('Desporto', 'Notícias sobre esportes universitários','Accept', setweight(to_tsvector('english', 'Desporto'), 'A')),
    ('Portugal', 'Notícias sobre o mundo universitário em Portugal','Accept', setweight(to_tsvector('english', 'Portugal'), 'A')),
    ('Mundo', 'Notícias sobre o mundo universitário internacional','Accept', setweight(to_tsvector('english', 'Mundo'), 'A')),
    ('Opinião', 'Artigos de opinião sobre o mundo universitário','Accept', setweight(to_tsvector('english', 'Opinião'), 'A'));

-- Inserir notícias fictícias na tabela 'post'
INSERT INTO post ("date", popularity, title, description, id_user, id_topic, title_tsvectors)
VALUES
    ('2023-10-19 09:00:00', 3, 'Pesquisadores descobrem nova espécie de dinossauro na Universidade de Lisboa', 'Cientistas destacados da Universidade de Lisboa recentemente realizaram uma descoberta notável que está atraindo a atenção da comunidade académica e do público em geral. Durante uma pesquisa no campus universitário, os pesquisadores encontraram os ossos fossilizados de uma espécie de dinossauro até então desconhecida. A descoberta é particularmente significativa, uma vez que os fósseis apresentam características distintas que diferenciam essa espécie recém-descoberta de dinossauro de todas as outras já catalogadas. Os cientistas estão agora envolvidos em estudos detalhados para compreender melhor a anatomia, o comportamento e o papel ecológico desse fascinante exemplar do passado. Acredita-se que esses fósseis fornecerão insights valiosos sobre a diversidade da vida pré-histórica e podem até lançar luz sobre eventos evolutivos desconhecidos até então. O processo de escavação e análise está sendo conduzido com extrema cautela para preservar a integridade dos fósseis e maximizar o potencial de descobertas científicas. A Universidade de Lisboa planeja envolver a comunidade local e a sociedade em geral nessa jornada emocionante de descoberta paleontológica. Está sendo organizada uma série de eventos educativos, palestras públicas e visitas guiadas para compartilhar informações sobre o processo de pesquisa, a importância da descoberta e os avanços científicos que essa nova espécie de dinossauro pode desencadear. Essa recente descoberta não apenas destaca a excelência da pesquisa conduzida na Universidade de Lisboa, mas também sublinha a importância contínua da exploração científica para expandir nosso conhecimento sobre a vida na Terra ao longo das eras.', 1, 1, setweight(to_tsvector('english', 'Pesquisadores descobrem nova espécie de dinossauro na Universidade de Lisboa'), 'A')),
    ('2023-10-18 14:30:00', 2, 'Estudantes da Universidade de Coimbra triunfam em Competição de Robótica', 'O grupo de robótica da Universidade de Coimbra alcançou o topo ao conquistar o primeiro lugar numa competição nacional. Neste feito notável, os estudantes demonstraram não apenas habilidades técnicas impressionantes, mas também uma dedicação excepcional e trabalho de equipa exemplar. A vitória não só destaca a excelência académica da Universidade de Coimbra, mas também reforça a sua posição na vanguarda da inovação e tecnologia. Os participantes, orientados por mentores experientes da universidade, enfrentaram desafios complexos que testaram a sua engenhosidade, resolução de problemas e criatividade. O reconhecimento nacional na competição de robótica é um testemunho do compromisso da Universidade de Coimbra com a formação de estudantes talentosos e preparação para os desafios do futuro. Esta vitória não apenas enche de orgulho a comunidade académica da Universidade de Coimbra, mas também destaca o papel fundamental das instituições de ensino superior na promoção do desenvolvimento de habilidades avançadas e na preparação de líderes no campo da tecnologia e inovação.', 2, 2, setweight(to_tsvector('english', 'Estudantes da Universidade de Coimbra triunfam em Competição de Robótica'), 'A')),
    ('2023-10-17 11:15:00', 1, 'Universidade Técnica de Lisboa revela Novo Laboratório de Pesquisa', 'A Universidade Técnica de Lisboa celebrou a inauguração do seu mais recente laboratório de pesquisa, equipado com tecnologia de última geração para benefício tanto de estudantes quanto de professores. Este novo espaço de inovação representa um compromisso contínuo com a excelência académica e a promoção da investigação de ponta. Estudantes terão agora acesso a instalações de última geração, proporcionando-lhes um ambiente propício para o desenvolvimento de projetos de pesquisa inovadores.Além disso, este laboratório será um ponto focal para professores dedicados à investigação, permitindo-lhes aprofundar os seus estudos e contribuir significativamente para o avanço do conhecimento em diversas áreas. A inauguração deste laboratório destaca o compromisso da Universidade Técnica de Lisboa em oferecer recursos de vanguarda e impulsionar a pesquisa e o desenvolvimento no cenário académico.', 3, 3, setweight(to_tsvector('english', 'Universidade Técnica de Lisboa revela Novo Laboratório de Pesquisa'), 'A')),
    ('2023-10-16 16:45:00', 0, 'Aluno da Universidade Liderança ganha prémio de melhor tese de mestrado', 'Um estudante da Universidade Liderança foi distinguido pelo seu trabalho excecional numa tese de mestrado centrada na inovação tecnológica. Esta conquista notável não só destaca o talento académico do estudante, mas também reflete o compromisso contínuo da Universidade Liderança em promover a excelência educacional e a investigação de qualidade. A tese premiada não apenas contribui para o avanço do conhecimento na área, mas também demonstra a capacidade dos alunos da universidade de liderar em áreas de vanguarda. O prémio é uma prova do ambiente académico estimulante proporcionado pela Universidade Liderança, que nutre o potencial dos seus alunos e os inspira a destacarem-se em projetos inovadores. Esta conquista será, sem dúvida, um impulso significativo para a carreira futura do estudante e um motivo de orgulho para a instituição académica.', 4, 4, setweight(to_tsvector('english', 'Aluno da Universidade Liderança ganha prémio de melhor tese de mestrado'), 'A')),
    ('2023-10-15 12:20:00', 0, 'Universidade Vanguarda Recebe Destacada Conferência Internacional em Ciências Sociais', 'A Universidade Vanguarda solidificou sua reputação como um centro acadêmico de excelência ao sediar uma conferência internacional de grande destaque no campo das ciências sociais. O evento reuniu renomados académicos, especialistas e investigadores de várias partes do mundo, proporcionando um fórum intelectual vibrante para discussões aprofundadas e partilha de conhecimentos inovadores. Ao longo dos dias da conferência, temas cruciais para as ciências sociais foram abordados, desde questões sociopolíticas até análises de tendências culturais globais. O palco académico da Universidade Vanguarda não só ofereceu uma atmosfera estimulante para a troca de ideias, mas também destacou a sua posição como um polo de investigação e aprendizagem progressista. Os participantes beneficiaram não apenas das apresentações notáveis, mas também da oportunidade de colaborar em redes académicas internacionais. Esta conferência não apenas reforça o prestígio da Universidade Vanguarda no âmbito das ciências sociais, mas também destaca o seu papel fundamental na promoção da pesquisa interdisciplinar e na contribuição para a compreensão aprofundada dos desafios sociais contemporâneos. O sucesso do evento é um testemunho não apenas da excelência acadêmica da instituição, mas também do seu impacto duradouro no cenário global das ciências sociais.', 5, 5, setweight(to_tsvector('english', 'Universidade Vanguarda Recebe Destacada Conferência Internacional em Ciências Sociais'), 'A')),
    ('2023-10-14 09:30:00', 0, 'Projeto de Voluntariado da Universidade de Coimbra Contribui para o Bem-Estar da Comunidade Local', 'Estudantes da Universidade de Coimbra dedicaram tempo e esforço a um projeto de voluntariado notável, resultando em impactos positivos significativos na comunidade circundante. Este projeto exemplar não só demonstra o compromisso social dos estudantes da Universidade de Coimbra, mas também reflete a importância de uma educação que vai além dos limites do campus académico. Ao envolver-se ativamente nas necessidades locais, os estudantes não apenas aplicaram os seus conhecimentos académicos na prática, mas também fortaleceram os laços entre a universidade e a comunidade. As ações voluntárias abordaram diversas áreas, desde apoio educacional até iniciativas de sustentabilidade ambiental, evidenciando a abrangência do impacto positivo gerado. A comunidade local beneficiou-se não apenas dos resultados tangíveis do projeto, mas também do espírito solidário demonstrado pelos estudantes da Universidade de Coimbra. Esta iniciativa de voluntariado não só enriqueceu a experiência dos estudantes, mas também reforçou o papel fundamental das instituições académicas na promoção do serviço comunitário. A Universidade de Coimbra destaca-se não apenas como um centro de aprendizagem, mas como uma força motriz para a mudança positiva e a contribuição significativa para o bem-estar da comunidade local.', 6, 1, setweight(to_tsvector('english', 'Projeto de Voluntariado da Universidade de Coimbra Contribui para o Bem-Estar da Comunidade Local'), 'A')),
    ('2023-10-13 15:00:00', 0, 'Estudantes da Universidade de Lisboa Inovam com Lançamento de Aplicativo de Ensino à Distância', 'Num feito empreendedor notável, estudantes da Universidade de Lisboa deram um passo significativo ao criar um aplicativo inovador dedicado a aulas online. Este projeto destaca não apenas a criatividade e habilidades empreendedoras dos alunos, mas também evidencia o compromisso da Universidade de Lisboa em preparar os estudantes para enfrentar os desafios contemporâneos da educação. O aplicativo, desenvolvido por essa jovem equipa talentosa, oferece uma abordagem única e eficiente para o ensino à distância, integrando recursos avançados e uma interface intuitiva. A iniciativa não só demonstra o potencial inovador dos alunos da Universidade de Lisboa, mas também enfatiza a importância crescente da tecnologia na educação moderna. Este aplicativo promete não apenas melhorar a experiência de aprendizagem dos estudantes, mas também contribuir para a evolução contínua dos métodos de ensino à distância. O sucesso deste projeto é um testemunho da cultura empreendedora fomentada pela Universidade de Lisboa, que continua a inspirar os estudantes a transformar ideias inovadoras em soluções práticas para os desafios educacionais do século XXI.', 7, 2, setweight(to_tsvector('english', 'Estudantes da Universidade de Lisboa Inovam com Lançamento de Aplicativo de Ensino à Distância'), 'A')),
    ('2023-10-12 10:10:00', 0, 'Universidade de Coimbra Recebe Doação Substancial para Bolsas de Estudo', 'Um benfeitor generoso fez uma contribuição notável à Universidade de Coimbra, doando uma significativa quantia que possibilitará a criação de mais bolsas de estudo. A generosidade deste benfeitor destaca o compromisso contínuo da Universidade de Coimbra em fornecer oportunidades educacionais acessíveis e de alta qualidade. As novas bolsas de estudo não apenas aliviarão o fardo financeiro dos estudantes, mas também abrirão portas para o acesso à educação superior para aqueles que de outra forma poderiam enfrentar desafios financeiros. Esta doação substancial não só fortalece a missão da Universidade de Coimbra de promover a igualdade de acesso à educação, mas também destaca a importância da generosidade individual no apoio à formação académica e ao desenvolvimento de jovens talentosos. A comunidade universitária expressa profunda gratidão pelo gesto altruísta do benfeitor, reconhecendo que essa doação terá um impacto duradouro não apenas nos beneficiários diretos das bolsas, mas também na própria universidade e na sociedade em geral.', 8, 3, setweight(to_tsvector('english', 'Universidade Z recebe doação significativa para bolsas de estudo'), 'A')),
    ('2023-10-11 13:45:00', 0, 'Estudante da Universidade do Porto Assume Presidência da Associação Estudantil', 'Um dedicado aluno da Universidade do Porto conquistou a posição de presidente na associação estudantil, prometendo implementar melhorias significativas para o benefício da comunidade académica. Eleito pelos seus colegas, o novo presidente demonstrou não apenas liderança, mas também um compromisso firme em representar os interesses dos estudantes. Sua plataforma inclui propostas para aprimorar a qualidade da vida estudantil, promover iniciativas culturais e desportivas, e fortalecer a comunicação entre os estudantes e a administração. A Universidade do Porto, conhecida pela sua comunidade académica dinâmica, está confiante de que o presidente recém-eleito trará uma abordagem proativa e inovadora para a gestão da associação estudantil. Este evento não apenas destaca a participação ativa dos estudantes na vida universitária, mas também sublinha a importância de líderes estudantis engajados na criação de um ambiente educacional enriquecedor e inclusivo.', 9, 4, setweight(to_tsvector('english', 'Estudante da Universidade do Porto Assume Presidência da Associação Estudantil'), 'A')),
    ('2023-10-10 17:25:00', 0, 'Professor da Universidade do Porto Recebe Prémio de Excelência em Pesquisa', 'Num feito notável, um distinto docente da Universidade do Porto foi agraciado com um prestigiado prémio internacional em reconhecimento à sua pesquisa inovadora. O prémio, concedido em virtude dos contributos notáveis para a investigação, ressalta não apenas a competência e dedicação excecionais do professor, mas também destaca a posição de destaque da Universidade do Porto no panorama académico global. A pesquisa inovadora, que capturou a atenção internacional, é reflexo do compromisso inabalável da universidade em fomentar a excelência académica e catalisar avanços significativos em diversas áreas do conhecimento. Este reconhecimento sólido fortalece ainda mais a reputação da Universidade do Porto como uma instituição de ensino superior comprometida com a produção de investigação de alta qualidade e contribuições notáveis para o progresso científico. O professor distinguido não apenas enriqueceu a comunidade académica da universidade, mas também amplificou o prestígio da instituição a nível global, sublinhando a relevância vital do papel das universidades na promoção contínua da inovação e da investigação de excelência.', 10, 5, setweight(to_tsvector('english', 'Professor da Universidade do Porto Recebe Prémio de Excelência em Pesquisa'), 'A'));

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
    ('Notícia interessante', NOW(), 1, 1, 1),
    ('É impressionante como ainda há imensa coisa por descobrir no mundo', NOW(), 0, 1, 2),
    ('É um orgulho dizer que já estudei nessa universidade', NOW(), 0, 2, 1);

INSERT INTO voteComment (id_user, id_comment) VALUES
    (2, 1);

-- Inserir dados de exemplo na tabela 'notification'
INSERT INTO notification (content, "date", type, id_user, id_post) VALUES
    ('A comment has been added to your post', NOW(), 'comment', 1, 1),
    ('Your post has been upvoted', NOW(), 'rating', 1, 1),
    ('Your post has been edited', NOW(), 'post', 3, 3);

-- Inserir dados de exemplo na tabela 'image'
INSERT INTO image (imagePath, typeOfImage, id_post, id_user) VALUES
    ('images/dino.jpg', 'Post', 1, NULL),
    ('images/robo.jpg', 'Post', 2, NULL),
    ('images/lab.png', 'Post', 3, NULL),
    ('images/tese.jpg', 'Post', 4, NULL),
    ('images/conf.jpg', 'Post', 5, NULL),
    ('images/volt.jpg', 'Post', 6, NULL),
    ('images/dist.jpg', 'Post', 7, NULL),
    ('images/bolsa.jpg', 'Post', 8, NULL),
    ('images/pres.jpg', 'Post', 9, NULL),
    ('images/prof.jpg', 'Post', 10, NULL),
    ('images/profile.jpg', 'User', NULL, 1),
    ('images/profile.jpg', 'User', NULL, 2),
    ('images/profile.jpg', 'User', NULL, 3),
    ('images/profile.jpg', 'User', NULL, 4),
    ('images/profile.jpg', 'User', NULL, 5),
    ('images/profile.jpg', 'User', NULL, 6),
    ('images/profile.jpg', 'User', NULL, 7),
    ('images/profile.jpg', 'User', NULL, 8),
    ('images/profile.jpg', 'User', NULL, 9),
    ('images/profile.jpg', 'User', NULL, 10);

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
    ('Comportamento inadequado', 4),
    ('Spam', 5);

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