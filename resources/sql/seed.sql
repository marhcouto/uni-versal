drop table if exists notification CASCADE;
drop table if exists report CASCADE;
drop table if exists bookmark CASCADE;
drop table if exists rating CASCADE;
drop table if exists answer CASCADE;
drop table if exists question CASCADE;
drop table if exists media CASCADE;
drop table if exists post CASCADE;
drop table if exists topic CASCADE;
drop table if exists banned CASCADE;
drop table if exists admin CASCADE;
drop table if exists moderator CASCADE;
drop table if exists "user" CASCADE;
drop table if exists password_resets CASCADE; 

drop type if exists media_type CASCADE;
drop type if exists university_role CASCADE;
drop type if exists user_permissions CASCADE;
drop type if exists notification_type CASCADE;

--                                                   SCHEMA DEFINITION

CREATE TYPE "media_type" AS ENUM ('File', 'Image');
CREATE TYPE "university_role" AS ENUM ('Professor', 'Student');
CREATE TYPE "user_permissions" AS ENUM ('Administrator', 'Moderator', 'User');
CREATE TYPE "notification_type" AS ENUM ('Upvote', 'Answer');

CREATE TABLE "user" (
    id SERIAL PRIMARY KEY,
    email TEXT NOT NULL CONSTRAINT email_uk UNIQUE,  
    name TEXT NOT NULL,
    password TEXT NOT NULL,
    faculty TEXT,
    area TEXT,
    role university_role NOT NULL DEFAULT 'Student',
    img TEXT DEFAULT NULL,
    ban BOOLEAN NOT NULL DEFAULT False,
    permissions user_permissions NOT NULL DEFAULT 'User',
    email_verified_at TIMESTAMP DEFAULT NULL,
    remember_token TEXT,
    CONSTRAINT email_format CHECK(email LIKE '%@up.pt')
);

CREATE TABLE "password_resets" (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);

CREATE TABLE "banned"(
    id_user INTEGER PRIMARY KEY REFERENCES "user" ON UPDATE CASCADE,
    id_moderator INTEGER REFERENCES "user" ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE "post"(
    id SERIAL PRIMARY KEY,
    title TEXT DEFAULT NULL,
    text TEXT DEFAULT NULL,
    date TIMESTAMP WITH TIME ZONE DEFAULT now(),
    no_votes INTEGER NOT NULL DEFAULT 0,
    draft BOOLEAN NOT NULL DEFAULT FALSE,
    anonymous BOOLEAN NOT NULL DEFAULT FALSE,
    id_user INTEGER REFERENCES "user" ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE "notification"(
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    body TEXT NOT NULL,
    id_post INTEGER NOT NULL REFERENCES "post" ON DELETE CASCADE ON UPDATE CASCADE,
    id_user INTEGER NOT NULL REFERENCES "user" ON DELETE CASCADE ON UPDATE CASCADE,
    date TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT now(),
    seen BOOLEAN NOT NULL DEFAULT FALSE,
    notif_type notification_type NOT NULL
);

CREATE TABLE "report"(
    id_post INTEGER NOT NULL REFERENCES "post" ON DELETE CASCADE ON UPDATE CASCADE,
    id_user INTEGER NOT NULL REFERENCES "user" ON DELETE CASCADE ON UPDATE CASCADE,
    date TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT now(),
    text TEXT NOT NULL,
    PRIMARY KEY(id_post, id_user)
);

CREATE TABLE "media"(
    id SERIAL PRIMARY KEY,
    url TEXT UNIQUE NOT NULL,
    type media_type NOT NULL, 
    id_post INTEGER NOT NULL REFERENCES "post" ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE "topic"(
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL UNIQUE,
    area TEXT
);

CREATE TABLE "question"(
    id_post INTEGER PRIMARY KEY REFERENCES "post" ON DELETE CASCADE ON UPDATE CASCADE,
    id_topic INTEGER DEFAULT NULL REFERENCES topic ON DELETE CASCADE ON UPDATE CASCADE,
    solved BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE "answer"(
    id_post INTEGER PRIMARY KEY REFERENCES "post" ON DELETE CASCADE ON UPDATE CASCADE,
    id_question INTEGER NOT NULL REFERENCES question ON DELETE CASCADE ON UPDATE CASCADE,
    verified BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE "rating"(
    id_post INTEGER REFERENCES "post" ON DELETE CASCADE ON UPDATE CASCADE,
    id_user INTEGER REFERENCES "user" ON DELETE CASCADE ON UPDATE CASCADE,
    rating BOOLEAN NOT NULL,
    PRIMARY KEY(id_post, id_user)
);

CREATE TABLE "bookmark"(
    id_question INTEGER REFERENCES question ON DELETE CASCADE ON UPDATE CASCADE,
    id_user INTEGER REFERENCES "user" ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY(id_question, id_user)
);






--                                                  PERFORMANCE INDICES

DROP INDEX IF EXISTS post_date_idx;
DROP INDEX IF EXISTS post_votes_idx;
DROP INDEX IF EXISTS name_search_idx;
DROP INDEX IF EXISTS post_search_idx;

DROP TRIGGER IF EXISTS name_search ON "user";
DROP TRIGGER IF EXISTS post_search ON "post";

CREATE INDEX post_date_idx ON "post" USING btree (date);
CREATE INDEX post_votes_idx ON "post" USING btree (no_votes);


-- FULL-TEXT SEARCH ON USER'S NAME

ALTER TABLE "user" ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION name_search() RETURNS TRIGGER AS 
$BODY$
BEGIN 
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            to_tsvector('simple', NEW.name)
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF NEW.name <> OLD.name THEN 
            NEW.tsvectors = (
                to_tsvector('simple', NEW.name)
            );
        END IF;
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER name_search  
    BEFORE INSERT OR UPDATE ON "user"
    FOR EACH ROW
    EXECUTE PROCEDURE name_search();

CREATE INDEX name_search_idx ON "user" using GIN (tsvectors);


-- FULL TEXT SEARCH INDEX ON POSTS

ALTER TABLE "post" ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION post_search() RETURNS TRIGGER AS 
$BODY$
BEGIN 
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('simple', NEW.title), 'A') ||
            setweight(to_tsvector('simple', NEW.text), 'B')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF NEW.title <> OLD.title OR OLD.text <> NEW.text THEN 
            NEW.tsvectors = (
                setweight(to_tsvector('simple', NEW.title), 'A') ||
                setweight(to_tsvector('simple', NEW.text), 'B')
            );
        END IF;
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER post_search  
    BEFORE INSERT OR UPDATE ON "post"
    FOR EACH ROW
    EXECUTE PROCEDURE post_search();

CREATE INDEX post_search_idx ON "post" using GIN (tsvectors);



--                                                  TRIGGERS

DROP TRIGGER IF EXISTS update_votes ON rating;
DROP TRIGGER IF EXISTS update_question_status ON answer;
DROP TRIGGER IF EXISTS prohibit_invalid_draft ON post;
DROP TRIGGER IF EXISTS update_rating_notification ON rating;
DROP TRIGGER IF EXISTS update_rating_notification_on_delete ON rating;
DROP TRIGGER IF EXISTS answer_notification_create ON answer;
DROP TRIGGER IF EXISTS answer_notification_delete ON answer;
DROP TRIGGER IF EXISTS update_ban_status ON banned;

-- Trigger to update user ban status
CREATE OR REPLACE FUNCTION update_ban_status() RETURNS TRIGGER AS 
$BODY$
BEGIN
    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
        UPDATE "user"
        SET ban = TRUE
        WHERE id = NEW.id_user;
    END IF;
    IF TG_OP = 'DELETE' OR TG_OP = 'UPDATE' THEN
        UPDATE "user"
        SET ban = FALSE
        WHERE id = OLD.id_user;
    END IF;
    RETURN NULL;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_ban_status
    AFTER INSERT OR UPDATE OF id_user OR DELETE ON banned
    FOR EACH ROW
    EXECUTE PROCEDURE update_ban_status();

-- Trigger to update question status
CREATE OR REPLACE FUNCTION update_question_status() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
        IF NEW.verified THEN
            UPDATE question
            SET solved = TRUE
            WHERE question.id_post = NEW.id_question;
        ELSE
            UPDATE question
            SET solved = FALSE
            WHERE question.id_post = NEW.id_question;
        END IF;
    END IF;
    IF TG_OP = 'DELETE' THEN
        IF OLD.verified THEN
            UPDATE question
            SET solved = FALSE
            WHERE question.id_post = OLD.id_question;
        END IF;
    END IF;
    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_question_status
    AFTER INSERT OR UPDATE OF verified OR DELETE ON answer
    FOR EACH ROW
    EXECUTE PROCEDURE update_question_status();



-- Trigger to update post votes
CREATE OR REPLACE FUNCTION update_votes() RETURNS TRIGGER AS 
$BODY$
BEGIN 
    IF TG_OP = 'INSERT' THEN
        IF NEW.rating THEN
            UPDATE post
            SET no_votes = (SELECT no_votes FROM post WHERE post.id = NEW.id_post) + 1
            WHERE post.id = NEW.id_post;
        ELSE 
            UPDATE post
            SET no_votes = (SELECT no_votes FROM post WHERE post.id = NEW.id_post) - 1
            WHERE post.id = NEW.id_post;
        END IF;
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF NEW.rating AND NOT OLD.RATING THEN
            UPDATE post
            SET no_votes = (SELECT no_votes FROM post WHERE post.id = NEW.id_post) + 2
            WHERE post.id = NEW.id_post;
        END IF;
        IF NOT NEW.rating AND OLD.RATING THEN
            UPDATE post
            SET no_votes = (SELECT no_votes FROM post WHERE post.id = NEW.id_post) - 2
            WHERE post.id = NEW.id_post;
        END IF;
    END IF;
    IF TG_OP = 'DELETE' THEN
        IF OLD.rating THEN
            UPDATE post
            SET no_votes = (SELECT no_votes FROM post WHERE post.id = OLD.id_post) - 1
            WHERE post.id = OLD.id_post;
        ELSE
            UPDATE post
            SET no_votes = (SELECT no_votes FROM post WHERE post.id = OLD.id_post) + 1
            WHERE post.id = OLD.id_post;
        END IF;
    END IF;
    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_votes
    AFTER INSERT OR UPDATE OF rating OR DELETE ON rating
    FOR EACH ROW
    EXECUTE PROCEDURE update_votes();



-- Trigger to prohibit a draft from having a date and votes
CREATE OR REPLACE FUNCTION prohibit_invalid_draft() RETURNS TRIGGER AS 
$BODY$
BEGIN
    IF (TG_OP = 'INSERT' OR TG_OP = 'UPDATE') AND ((NOT (NEW.date IS NULL)) OR (NEW.no_votes <> 0)) AND NEW.draft THEN 
        RAISE EXCEPTION 'A draft cannot have a date nor number of votes';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER prohibit_invalid_draft
    BEFORE INSERT OR UPDATE ON post
    FOR EACH ROW
    EXECUTE PROCEDURE prohibit_invalid_draft();



-- Update vote notification ON INSERT/UPDATE
CREATE OR REPLACE FUNCTION update_rating_notification() RETURNS TRIGGER AS
$BODY$
DECLARE user_id INTEGER := (SELECT id FROM "user" WHERE id = NEW.id_user);
DECLARE no_upvotes INTEGER := (SELECT COUNT(id_user) FROM rating WHERE id_post = NEW.id_post AND rating=TRUE);
DECLARE no_downvotes INTEGER := (SELECT COUNT(id_user) FROM rating WHERE id_post = NEW.id_post AND rating=FALSE);
DECLARE no_rating INTEGER := no_upvotes - no_downvotes;
DECLARE body_post TEXT := (SELECT text FROM post WHERE id = NEW.id_post);
DECLARE notification_id INTEGER := (SELECT max(id) FROM notification);
DECLARE not_body TEXT := ('Post: ' || substring(body_post from 1 for 500));
DECLARE not_title TEXT := ('Your Post got a rating of ' || CAST(no_rating AS TEXT) || '!');
BEGIN 
    IF LENGTH(body_post) > 500 THEN
        not_body := (not_body || '...');
    END IF;
    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN 
        IF EXISTS (SELECT 1 FROM notification WHERE id_post = NEW.id_post AND notif_type = 'Upvote') THEN
            UPDATE notification SET title = not_title, seen = FALSE, date = now()  WHERE id_post = NEW.id_post AND notif_type = 'Upvote';
        END IF;

        IF NOT EXISTS (SELECT 1 FROM Notification WHERE id_post = NEW.id_post AND notif_type = 'Upvote') THEN
            INSERT INTO notification (title, body, id_post, id_user, notif_type) VALUES (not_title, not_body, NEW.id_post, (SELECT id_user FROM post WHERE id = NEW.id_post), 'Upvote');
        END IF;

    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_rating_notification
    AFTER INSERT OR UPDATE ON rating
    FOR EACH ROW
    EXECUTE PROCEDURE update_rating_notification();



-- Update vote notification ON DELETE
CREATE OR REPLACE FUNCTION update_rating_notification_on_delete() RETURNS TRIGGER AS
$BODY$
DECLARE no_upvotes INTEGER := (SELECT COUNT(id_user) FROM rating WHERE id_post = OLD.id_post AND rating=TRUE);
DECLARE no_downvotes INTEGER := (SELECT COUNT(id_user) FROM rating WHERE id_post = OLD.id_post AND rating=FALSE);
DECLARE no_rating INTEGER := no_upvotes - no_downvotes;
DECLARE not_title TEXT := ('Your question got a rating of ' || CAST(no_rating AS TEXT) || '!');
BEGIN 
    UPDATE notification SET title = not_title, seen = FALSE, date = now()  WHERE id_post = OLD.id_post AND notif_type = 'Upvote';
    RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_rating_notification_on_delete
    AFTER DELETE ON rating
    FOR EACH ROW
    EXECUTE PROCEDURE update_rating_notification_on_delete();




-- Answer notification when answer is created
CREATE OR REPLACE FUNCTION answer_notification_create() RETURNS TRIGGER AS
$BODY$
DECLARE answer_author_id INTEGER := (SELECT id_user FROM post WHERE id = NEW.id_post);
DECLARE body_post TEXT := (SELECT text FROM post WHERE id = NEW.id_post);
DECLARE new_body TEXT := ('Answer: ' || substring(body_post from 1 for 500));
DECLARE new_title TEXT := ((SELECT name FROM "user" WHERE id = answer_author_id) || ' replied to your post!');
DECLARE target_post_q INTEGER := (SELECT id_post FROM question WHERE id_post = NEW.id_question);

BEGIN 
    IF LENGTH(body_post) > 500 THEN
        new_body := (new_body || '...');
    END IF;
    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
        INSERT INTO notification (title, body, id_post, id_user, notif_type) VALUES (new_title, new_body, NEW.id_post, (SELECT id_user FROM post WHERE id = target_post_q), 'Answer');
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER answer_notification
    BEFORE INSERT OR UPDATE OF id_post ON answer
    FOR EACH ROW
    EXECUTE PROCEDURE answer_notification_create();





-- Answer notification when answer is deleted
CREATE OR REPLACE FUNCTION answer_notification_delete() RETURNS TRIGGER AS
$BODY$
BEGIN 
    IF TG_OP = 'DELETE' THEN
        DELETE FROM notification WHERE id_post = OLD.id_post;
    END IF;
    RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER answer_notification_delete
    BEFORE DELETE ON answer
    FOR EACH ROW
    EXECUTE PROCEDURE answer_notification_delete();


--                                                          POPULATE



INSERT INTO "user" (name,email,password,faculty,area,role,permissions,email_verified_at)
VALUES  
  ('João Afonso','up201905589@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FEUP','Engenharia Informática','Student','Administrator',now()), 
  ('Sérgio Estêvão','up201905681@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FEUP','Engenharia Informática','Student','Administrator',now()), 
  ('André Santos','up201907879@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FEUP','Engenharia Informática','Student','Administrator',now()),
  ('Marcelo Couto','up201906086@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FEUP','Engenharia Informática','Student','Administrator',now()), 
  ('Miguel Rosa','up201905688@up.pt','$2y$10$USDW2N629PXJvUAs1dgacuLbO43NnbJWtZR75sSsWTljK0RWxrJoO','FEUP','Engenharia Informática','Student','Administrator',now()), 
  ('Seth Farmer','ne123c@up.pt','$2y$10$USDW2N629PXJvUAs1dgacuLbO43NnbJWtZR75sSsWTljK0RWxrJoO','FAUP','Desporto','Professor', 'Moderator',now()),
  ('Oren Hernandez','me12tus.eu@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq,','FDUP','Desporto','Student', 'Moderator',now()),
  ('Inga Bowers','eu.eli12t@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FLUP','Desporto','Student', 'Moderator',now()),
  ('Isaac Oneil','odi12o@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FCUP','Desporto','Student', 'Moderator',now()),
  ('Chase Morse','dui.suspen12disse@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FAUP','Desporto','Student', 'Moderator',now()),
  ('Ann Velasquez','odio.12a@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FAUP','Desporto','Student', 'Moderator',now()),
  ('Patricia Jenkins','vel12it@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FEP','Desporto','Student', 'Moderator',now()),
  ('Clarke Church','curabit12ur.vel@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FDUP','Desporto','Professor', 'Moderator',now());

INSERT INTO "user" (name,email,password,faculty,area,role)
VALUES  
  ('Tate York','at.veli3t.cras@up.pt', 'est','FEUP','Engenharia Informática','Professor'), --1
  ('Libby Moon','sagitt3is@up.pt','Fusce','FADEUP',NULL,'Student'), --2
  ('Forrest Cline','cur3abitur@up.pt','Donec','FCUP','Bioquimica','Professor'), --3
  ('Jessica Duke','erat3.vel.pede@up.pt','lorem','FMDUP', 'Medicina Dentária','Student'), --4
  ('Irma Kelly','intege3r.eu@up.pt','tempus','FEP',NULL,'Student'), --5
  ('Jasper Bowen','nec.3mauris.blandit@up.pt','dui','FMUP','Medicina','Professor'), --6
  ('Zahir Whitley','arc3u@up.pt','tortor','FEUP','Engenharia Informática','Student'), --7
  ('Bianca Jacobs','u2t@up.pt','enim','FADEUP','Desporto','Student'), --8
  ('Shad Pugh','ut.ips1um@up.pt','velit','FCUP','Biologia','Student'), --9
  ('Matthew Maldonado','1a@up.pt','augue','FEP','Economia','Student'), --10
  ('Kasimir Bush','lac2us.ut.nec@up.pt','neque','FEUP','Engenharia de Gestão Industrial','Professor'),  --11
  ('Iona Tillman','fri2ngilla.purus@up.pt','Phasellus','FADEUP','Desporto','Professor'), --12
  ('Leila Mooney','var2ius.nam.porttitor@up.pt','eu','FDUP','Direito','Student'), --13
  ('Astra Daugherty','2id@up.pt','risus','FEUP','Engenharia Mecânica','Student'), --14
  ('Ebony Alvarez','or2nare.lectus@up.pt','purus','FEP',NULL,'Student'), --15
  ('Mallory Mccray','p2haretra.sed@up.pt','nibh','FCUP','Bioquimica','Student'), --16
  ('Hilary Nicholson','ul1tricies.sem@up.pt','Praesent','FEUP','Engenharia de Gestão Industrial','Student'), --17
  ('Marshall Campbell','o1rci.adipiscing@up.pt','Sed','FMUP','Medicina','Student'), --18
  ('Samantha Guerrero','p1lacerat.eget@up.pt','id','FMDUP', 'Medicina Dentária','Professor'), --19
  ('Flynn Le','diam.luctu1s@up.pt','vulputate','FMUP','Medicina','Student'), --20
  ('Frances Miranda','ips1um.sodales.purus@up.pt','tellus','FLUP', 'Arqueologia','Student'), --21
  ('Hermione Reeves','con1vallis.est.vitae@up.pt','mollis','FEUP','Engenharia de Gestão Industrial','Student'), --22
  ('Phillip Barrett','fel1is@up.pt','sodales','FLUP', 'Filosofia','Professor'), --23
  ('Brent Villarreal','en1im.commodo@up.pt','sed','FEP','Gestão','Student'), --24
  ('Quinn Fletcher','luct1us.curabitur@up.pt','erat','FADEUP','Desporto','Student'), --25
  ('Kimberly Fitzgerald','a34liquam.tincidunt.nunc@up.pt','accumsan','FMUP','Medicina','Student'), --26
  ('Natalie Perry','feugiat34.non@up.pt','hendrerit','FMDUP', 'Medicina Dentária','Student'), --27
  ('Ezra Foreman','curabitu34rem@up.pt','mollis','FLUP', 'História','Professor'), --28
  ('Erica Curtis','mauris.u34t.quam@up.pt','placerat','FBAUP', 'Belas Artes','Student'), --29
  ('Rigel Hyde','duis.eleme34ntum@up.pt','Vestibulum','FADEUP','Desporto','Student'), --30
  ('Janna Sloan','maecenas.34ornare.egestas@up.pt','Morbi','FEUP','Engenharia Mecânica','Student'), --31
  ('Blossom Blankenship','e34gestas.fusce.aliquet@up.pt','vestibulum','FAUP', 'Arquitetura','Student'), --32
  ('Gary Livingston','dolor34.nonummy@up.pt','tellus','FADEUP','Desporto','Professor'), --33
  ('Deirdre Sloan','per.con34ubia@up.pt','sollicitudin','FEUP','Engenharia Mecânica','Student'), --34
  ('Benídio','scelerisqu2e5@up.pt','non','FCUP','Engenharia Física','Student'), --35
  ('Wade Ayers','aliquet2.nec@up.pt','elementum','FEP','Economia','Student'), --36
  ('Nehru Barr','felis.p2urus@up.pt','ligula','FEUP','Engenharia Informática','Student'), --37
  ('Ramona Knox','eroas@up.pt','Proin','FAUP', 'Arquitetura','Student'), --38
  ('Portia Weiss','odaio.etiam@up.pt','vulputate','FCUP','Biologia','Professor'), --39
  ('Mufutau Hickman','n2ibh.aliquam@up.pt','diam','FMUP','Medicina','Student'), --40
  ('Xena Maynard','matt2is@up.pt','consectetuer','FEP',NULL,'Student'), --41
  ('Jakeem Gilliam','fu2sce@up.pt','adipiscing','FEP','Economia','Student'), --42
  ('Axel Barrera','dict2um.phasellus.in@up.pt','nibh','FEUP','Engenharia Mecânica','Student'), --43
  ('Wyoming Savage','ar2cu.vestibulum.ante@up.pt','vitae','FDUP','Direito','Student'), --44
  ('Myra Maldonado','nec1@up.pt','nec,','FMUP','Engenharia Informática','Student'),
  ('Harriet Hardy','grav1ida.non@up.pt','vel','FEP','Engenharia Informática','Student'),
  ('Neville Higgins','ne1que.vitae@up.pt','elementum','FLUP','Engenharia Informática','Student'),
  ('Xanthus Tyler','adip1iscing.fringilla@up.pt','Vivamus','FCUP','Engenharia Informática','Student'),
  ('Winifred Jordan','ip1sum.primis.in@up.pt','felis,','FEUP','Engenharia Informática','Professor'),
  ('Marsden Allison','ar1cu2@up.pt','vitae','FMUP','Engenharia Informática','Student'),
  ('Zachary Terry','ac.l1ibero.nec@up.pt','ipsum','FAUP','Engenharia Informática','Student'),
  ('Blythe Donaldson','p1ede.malesuada@up.pt','tellus.','FMDUP','Engenharia Informática','Professor'),
  ('Kerry Brady','bibend1um@up.pt','Donec','FAUP','Engenharia Informática','Student'),
  ('Jesse Valenzuela','ves2tibulum.neque.sed@up.pt','a,','FADEUP','Engenharia Informática','Professor'),
  ('Steven Coleman','bland2it@up.pt','parturient','FADEUP','Engenharia Informática','Professor'),
  ('Otto Walter','in.tinci2dunt@up.pt','iaculis','FDUP','Engenharia Informática','Professor'),
  ('Alyssa Moore','quis.ma2ssa@up.pt','in,','FMDUP','Engenharia Informática','Student'),
  ('Berk Knox','egestas.al2iquam@up.pt','vulputate,','FEUP','Engenharia Informática','Student'),
  ('Juliet Koch','pretium.2et.rutrum@up.pt','orci.','FEP','Engenharia Informática','Student'),
  ('Colleen Odonnell','sag2ittis2@up.pt','Ut','FMUP','Engenharia Informática','Professor'),
  ('Georgia Mullen','adipi2scing.enim@up.pt','nec','FLUP','Engenharia Informática','Student'),
  ('Marny Salazar','ege42@up.pt','tellus.','FEP','Engenharia Informática','Student'),
  ('Jeremy Daniel','nul4am.lobortis.quam@up.pt','Aliquam','FLUP','Engenharia Informática','Professor'),
  ('Harper Lindsey','ni4h.vulputate4@up.pt','pellentesque','FCUP','Engenharia Informática','Student'),
  ('Brendan Clemons','a4.libero@up.pt','amet,','FMDUP','Medicina','Student'),
  ('Adrienne Ruiz','mor4i@up.pt','urna.','FEUP','Medicina','Student'),
  ('Adrienne Bell','ali4uet@up.pt','non','FMUP','Medicina','Professor'),
  ('Jemima Nielsen','at12122@up.pt','eget,','FEUP','Medicina','Professor'),
  ('Zelenia Maynard','l1uctus.vulputate@up.pt','nisl','FAUP','Medicina','Professor'),
  ('Mara French','euism1od.est@up.pt','nulla','FAUP','Medicina','Student'),
  ('Hyatt Anderson','se1d@up.pt','taciti','FMDUP','Medicina','Student'),
  ('Serina English','ip1sum@up.pt','volutpat.','FEUP','Medicina','Student'),
  ('Bradley Marks','pos1uere.vulputate@up.pt','aliquam','FAUP','Medicina','Professor'),
  ('Shafira Simon','mae1cenas.libero.est@up.pt','est.','FEUP','Medicina','Student'),
  ('Rigel Morales','eu.1enim@up.pt','augue','FAUP','Arqueologia','Professor'),
  ('Abel Mack','purus2@up.pt','posuere','FCUP','Arqueologia','Professor'),
  ('Harper Aguirre','pen6atibus.et@up.pt','non,','FCUP','Arqueologia','Professor'),
  ('Jermaine Henry','pla6cerat.cras@up.pt','Donec','FEP','Arqueologia','Professor'),
  ('Jacob Owen','pede.cu6m.sociis@up.pt','ac','FDUP','Arqueologia','Student'),
  ('Brielle Reynolds','n6unc@up.pt','Phasellus','FEUP','Arqueologia','Student'),
  ('Brenda Mueller','iac6ulis@up.pt','leo','FCUP','Arqueologia','Professor'),
  ('David Michael','rhon6cus.donec@up.pt','vel','FMUP','Arqueologia','Professor'),
  ('Bo Maynard','maecena6s.malesuada@up.pt','Cras','FMUP','Arqueologia','Professor'),
  ('Astra Peterson','sol6licitudin.commodo.ipsum@up.pt','blandit','FEUP','Arqueologia','Student'),
  ('Kibo Dorsey','interd6um.sed.auctor@up.pt','pede.','FEP','Engenharia Mecânica','Professor'),
  ('Harrison Joyce','ac.7turpis@up.pt','fermentum','FMUP','Engenharia Mecânica','Student'),
  ('Whitney Avila','ut.o7dio@up.pt','at','FMUP','Engenharia Mecânica','Professor'),
  ('Aimee Blevins','eu.t7empor.erat@up.pt','fermentum','FCUP','Engenharia Mecânica','Student'),
  ('Shellie Love','lectu7s@up.pt','Integer','FEUP','Engenharia Mecânica','Professor'),
  ('Selma Yang','eu.accu7msan.sed@up.pt','ultricies','FMUP','Engenharia Mecânica','Student'),
  ('Caesar Best','lacus.7ut@up.pt','ornare','FMDUP','Engenharia Mecânica','Professor'),
  ('Beverly Davenport','eg3t@up.pt','tincidunt','FDUP','Engenharia Mecânica','Professor'),
  ('Ursula Terrell','et.ma3nis@up.pt','sed','FAUP','Engenharia Mecânica','Student'),
  ('Driscoll Sexton','non.3nte@up.pt','eleifend','FMUP','Engenharia Mecânica','Student'),
  ('Sigourney Bean','sed.d3ctum@up.pt','Etiam','FAUP','Bioquimica','Professor'),
  ('Shellie Cummings','sus3endisse.commodo@up.pt','enim.','FADEUP','Bioquimica','Student'),
  ('Evangeline Powell','me3us@up.pt','aliquet,','FLUP','Bioquimica','Professor'),
  ('Jasper Solis','erat.eg3t@up.pt','eu','FMUP','Bioquimica','Student'),
  ('Martin Porter','at2@up.pt','non,','FMDUP','Bioquimica','Student'),
  ('Conan Stevenson','r2isus@up.pt','malesuada','FEP','Bioquimica','Professor'),
  ('Montana Francis','m2auris@up.pt','dictum','FADEUP','Bioquimica','Professor'),
  ('Hollee Stanton','et2@up.pt','lectus','FMDUP','Bioquimica','Professor'),
  ('Eric Lawrence','con2gue.in@up.pt','ipsum','FDUP','Bioquimica','Professor'),
  ('Mariko Pennington','e1rat@up.pt','eu','FDUP','Bioquimica','Student'),
  ('Illiana Kent','viverr1a@up.pt','ut','FEP','Arquitetura','Student'),
  ('Eve Griffin','dictum.1phasellus@up.pt','Sed','FCUP','Arquitetura','Student'),
  ('Desiree Russo','variu1s.ultrices.mauris@up.pt','netus','FEP','Arquitetura','Professor'),
  ('Cedric Ruiz','ornare.1in@up.pt','augue.','FAUP','Arquitetura','Student'),
  ('Kylan Sutton','sceler1isque2@up.pt','rhoncus','FADEUP','Arquitetura','Professor'),
  ('Sophia Collins','moll1is.lectus@up.pt','nec','FDUP','Arquitetura','Professor'),
  ('Amir Austin','nascetu1r.ridiculus.mus@up.pt','nulla.','FMUP','Arquitetura','Professor'),
  ('Ora Sanford','ac12@up.pt','Duis','FLUP','Arquitetura','Professor'),
  ('McKenzie Vinson','ves5tibulum.lorem@up.pt','vel','FCUP','Arquitetura','Student'),
  ('Rashad Ball','nulla.d5onec.non@up.pt','nulla.','FDUP','Arquitetura','Professor'),
  ('Chadwick Cannon','id.5libero.donec@up.pt','Morbi','FADEUP','Economia','Professor'),
  ('Ann Nguyen','velit.pe5llentesque@up.pt','non,','FCUP','Economia','Professor'),
  ('Zenaida Bruce','a.mi.5fringilla@up.pt','lacinia','FADEUP','Economia','Student'),
  ('Keane Bender','alique5t.magna.a@up.pt','nisi','FDUP','Economia','Student'),
  ('Megan Robinson','maur5is.sapien.cursus@up.pt','Aenean','FEP','Economia','Student'),
  ('Hyacinth Chavez','sus5cipit.est@up.pt','a','FLUP','Economia','Professor'),
  ('Arsenio Cabrera','nib5h.phasellus@up.pt','arcu','FEP','Economia','Student'),
  ('Alma Love','imperdiet5.erat@up.pt','imperdiet','FLUP','Economia','Student'),
  ('Brian Aguirre','matti5s.cras@up.pt','sit','FEUP','Economia','Student'),
  ('Kirestin Keller','ele5ifend@up.pt','risus','FAUP','Economia','Professor'),
  ('Marah Kidd','faucibus5.leo@up.pt','nulla','FAUP','Engenharia de Gestão Industrial','Student'),
  ('Tate Wynn','quisqu2e@up.pt','diam','FEP','Engenharia de Gestão Industrial','Student'),
  ('Ezra Johns','egestas.12sed@up.pt','odio.','FLUP','Engenharia de Gestão Industrial','Professor'),
  ('Duncan Bradley','ult12ricies@up.pt','Nunc','FDUP','Engenharia de Gestão Industrial','Student'),
  ('Bertha Turner','libe12ro@up.pt','lacus.','FEUP','Engenharia de Gestão Industrial','Professor'),
  ('Melyssa Hall','nibh.vul6utate@up.pt','Donec','FAUP','Engenharia de Gestão Industrial','Professor'),
  ('Amal Blankenship','cura6itur.massa@up.pt','Suspendisse','FCUP','Engenharia de Gestão Industrial','Professor'),
  ('Blaze Edwards','quam.ve6.sapien@up.pt','elementum','FLUP','Engenharia de Gestão Industrial','Student'),
  ('Quinlan Pollard','dolor6egestas@up.pt','arcu','FMUP','Engenharia de Gestão Industrial','Student'),
  ('Haley Lara','adipiscing6fringilla@up.pt','sem','FEUP','Engenharia de Gestão Industrial','Professor'),
  ('Amaya Short','molestie.6odales@up.pt','Vestibulum','FMDUP','Engenharia de Gestão Industrial','Professor'),
  ('Laith Pierce','mollis.n6n@up.pt','amet,','FLUP','Engenharia de Gestão Industrial','Professor'),
  ('Kiayada Anthony','mi.al6quam.gravida@up.pt','sed','FEP','Engenharia de Gestão Industrial','Professor'),
  ('Mason Bass','in.consequ6t.enim@up.pt','malesuada','FMUP','Engenharia de Gestão Industrial','Professor'),
  ('Rosalyn Castro','luctu12s@up.pt','justo','FADEUP','Engenharia de Gestão Industrial','Student'),
  ('Meredith Crane','vitae12.sodales@up.pt','Nunc','FEP','Engenharia de Gestão Industrial','Professor'),
  ('Amos Jones','lobortis.12quis@up.pt','semper','FMUP','Engenharia de Gestão Industrial','Professor'),
  ('Gil Russell','et.magni12s@up.pt','scelerisque,','FEUP','Engenharia de Gestão Industrial','Professor'),
  ('Aaron Riley','ullamcor12per.velit.in@up.pt','faucibus.','FMDUP','Engenharia de Gestão Industrial','Professor'),
  ('Vladimir Sutton','sem.12ut.cursus@up.pt','augue','FMDUP','Engenharia de Gestão Industrial','Professor'),
  ('Aretha Witt','feugiat.12sed.nec@up.pt','tristique','FAUP','Engenharia de Gestão Industrial','Student'),
  ('Hedwig Arnold','aliqua12m.arcu@up.pt','nulla.','FDUP','Engenharia de Gestão Industrial','Student'),
  ('Bertha Jenkins','vel.t12urpis@up.pt','Nam','FMUP','Engenharia de Gestão Industrial','Student'),
  ('Tiger Bartlett','sit.a12met.nulla@up.pt','ut','FDUP','Engenharia de Gestão Industrial','Professor'),
  ('Mercedes Watts','ultri12cies@up.pt','amet','FLUP','Engenharia de Gestão Industrial','Professor'),
  ('Holmes Campos','tincid12unt.tempus@up.pt','dapibus','FAUP','Engenharia de Gestão Industrial','Professor'),
  ('Hadassah Raymond','ege12t.ipsum.donec@up.pt','amet','FEP','Engenharia de Gestão Industrial','Professor'),
  ('Quemby Kidd','pellente12sque.ultricies.dignissim@up.pt','ante','FAUP','Engenharia de Gestão Industrial','Professor'),
  ('Chantale Baker','ut3@up.pt','semper','FCUP','Engenharia de Gestão Industrial','Student'),
  ('Hop Davis','nunc.pulvi1nar@up.pt','Phasellus','FEUP','Engenharia de Gestão Industrial','Professor'),
  ('Beck Rosario','orci.la1cus@up.pt','nulla','FAUP','Engenharia de Gestão Industrial','Professor'),
  ('Nayda Stuart','parturi1ent.montes@up.pt','malesuada','FLUP','Engenharia de Gestão Industrial','Student'),
  ('Willow Barnes','lorem.1eu.metus@up.pt','congue,','FMUP','Engenharia de Gestão Industrial','Professor'),
  ('Destiny Davis','laoree1t@up.pt','ac','FMDUP','Engenharia de Gestão Industrial','Student'),
  ('Jesse Boone','donec.ti1ncidunt@up.pt','elit.','FEUP','Engenharia de Gestão Industrial','Professor'),
  ('Calvin Mcclain','pelle1ntesque.tincidunt@up.pt','sodales','FLUP','Engenharia de Gestão Industrial','Professor'),
  ('Miriam Rivas','et.laci1nia@up.pt','arcu.','FMDUP','Engenharia de Gestão Industrial','Student'),
  ('Minerva Johnson','ut.m1olestie@up.pt','ac','FADEUP','Engenharia de Gestão Industrial','Professor'),
  ('Damian Diaz','enim.ne2c@up.pt','Integer','FAUP','Engenharia de Gestão Industrial','Student'),
  ('Rogan George','sed.au2ctor@up.pt','Nullam','FADEUP','Engenharia de Gestão Industrial','Professor'),
  ('Blythe Brown','es1t@up.pt','velit.','FLUP','Bioquimica','Professor'),
  ('Rhona Clayton','metus.1enean@up.pt','dolor','FCUP','Bioquimica','Professor'),
  ('Clinton Taylor','tinci1unt.dui@up.pt','sit','FMUP','Bioquimica','Professor'),
  ('Jenette Strong','quam.1ignissim@up.pt','montes,','FLUP','Bioquimica','Professor'),
  ('Orson Bean','integer.s1m.elit@up.pt','fames','FEUP','Bioquimica','Professor'),
  ('Naomi Hayes','feugiat.1ec@up.pt','urna.','FMDUP','Bioquimica','Professor'),
  ('Nolan Powell','vivamus1rhoncus@up.pt','vel,','FEUP','Bioquimica','Professor'),
  ('Sage Vinson','feugiat.1orem@up.pt','ac','FLUP','Bioquimica','Professor'),
  ('Hedley Herring','socii1.natoque.penatibus@up.pt','mus.','FMUP','Bioquimica','Professor'),
  ('Hector Holloway','sem.1itae@up.pt','vel,','FMUP','Bioquimica','Professor'),
  ('Erasmus Singleton','se1.magna@up.pt','mauris','FEP','Bioquimica','Student'),
  ('Malik Silva','arcu.imp1rdiet.ullamcorper@up.pt','Aliquam','FEUP','Bioquimica','Student'),
  ('Nathaniel Slater','tur1is.in.condimentum@up.pt','dui','FADEUP','Bioquimica','Professor'),
  ('Michael Stokes','accum1an.neque@up.pt','ipsum','FEUP','Bioquimica','Student'),
  ('Kyla Bernard','libero.1ui.nec@up.pt','tristique','FMUP','Bioquimica','Student'),
  ('Rina Torres','eti2am@up.pt','ac','FCUP','Bioquimica','Professor'),
  ('Bertha Frazier','velit1.eu.sem@up.pt','augue','FCUP','Bioquimica','Student'),
  ('Hyatt Petty','nunc.mau1ris@up.pt','elit,','FDUP','Bioquimica','Student'),
  ('Courtney Buck','vulput1ate.velit@up.pt','sed,','FDUP','Bioquimica','Student'),
  ('Fleur Pierce','ante.ip1sum@up.pt','nec','FADEUP','Bioquimica','Professor'),
  ('Nora Burke','quam.pell1entesque@up.pt','arcu.','FEP','Bioquimica','Student'),
  ('Azalia Brennan','a.mi.1fringilla@up.pt','libero','FMUP','Bioquimica','Student'),
  ('Meredith Skinner','int1eger.urna@up.pt','urna','FCUP','Bioquimica','Professor'),
  ('Merrill Allison','faci1lisis.facilisis@up.pt','amet','FADEUP','Bioquimica','Professor'),
  ('Quyn Bowman','nec.metu1s.facilisis@up.pt','mi','FAUP','Bioquimica','Professor'),
  ('Wilma Dillard','pellen1tesque.ut.ipsum@up.pt','per','FAUP','Bioquimica','Professor'),
  ('Christopher Logan','te1mpor@up.pt','Aliquam','FEP','Bioquimica','Professor'),
  ('Curran Haynes','egesta1s.a.dui@up.pt','massa','FMUP','Bioquimica','Student'),
  ('Clinton Edwards','lacu1s.vestibulum.lorem@up.pt','eget,','FEUP','Bioquimica','Professor'),
  ('Luke Fitzpatrick','sit1.amet.risus@up.pt','velit.','FAUP','Bioquimica','Student'),
  ('Ivy Ray','enim.ne12c@up.pt','amet','FMDUP','Medicina','Professor'),
  ('Edward Holder','phasell21us.fermentum.convallis@up.pt','egestas','FMDUP','Medicina','Professor'),
  ('Bruno Wilkerson','quisq21ue.libero@up.pt','euismod','FMDUP','Medicina','Student'),
  ('Kevyn Lancaster','ultri21ces.mauris@up.pt','semper','FDUP','Medicina','Student'),
  ('Shay Walker','erat.volu21tpat@up.pt','dolor','FMUP','Medicina','Student'),
  ('Marcia Dorsey','nato12que@up.pt','Cum','FAUP','Medicina','Professor'),
  ('Buckminster Holmes','od12io.vel@up.pt','est,','FDUP','Medicina','Student'),
  ('Abel Maddox','orci.u12t@up.pt','nunc','FCUP','Medicina','Professor'),
  ('Chase Higgins','tellu13s.faucibus@up.pt','nec','FEP','Medicina','Professor'),
  ('Jarrod Hopper','lacin13ia.orci@up.pt','vitae','FMUP','Medicina','Professor'),
  ('Sarah Hall','torquent13.per@up.pt','non','FMDUP','Medicina','Professor'),
  ('Ora Finley','nibh.don13ec@up.pt','enim','FMUP','Medicina','Student'),
  ('August Turner','variu13s.orci@up.pt','Integer','FMUP','Medicina','Student'),
  ('Ahmed Lloyd','nam.ligul14a@up.pt','eget,','FAUP','Medicina','Student'),
  ('Hanna Stephenson','lobo14rtis@up.pt','elit','FADEUP','Medicina','Student'),
  ('TaShya Hopper','tristiq14ue.neque@up.pt','lectus','FEUP','Medicina','Professor'),
  ('Randall Weiss','risus.a14t.fringilla@up.pt','tincidunt,','FAUP','Medicina','Professor'),
  ('Lewis Fletcher','sollic14itudin.commodo.ipsum@up.pt','Cras','FDUP','Medicina','Student'),
  ('Summer Gonzales','done12c@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FADEUP','Medicina','Professor'),
  ('Kyle Knox','qu12am@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FCUP','Medicina','Professor'),
  ('Larissa Ballard','ves12tibulum.ut.eros@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FMUP','Desporto','Student'),
  ('Tatyana Neal','cursus12.vestibulum.mauris@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FLUP','Desporto','Professor'),
  ('Mark Downs','pellente12sque.tellus@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQqMauris','FAUP','Desporto','Professor'),
  ('Peter Emerson','moles12tie.in@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FLUP','Desporto','Professor'),
  ('Walter Joseph','aucto12r@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FAUP','Desporto','Student'),
  ('Andrew Mcintosh','qua12m.quis@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FCUP','Desporto','Student'),
  ('Quemby Cleveland','ve12l.turpis.aliquam@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FADEUP','Desporto','Student'),
  ('Dante Casey','eleifen12d.cras@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FADEUP','Desporto','Student'),
  ('Daquan Gomez','maecen12as.mi.felis@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FADEUP','Desporto','Professor'),
  ('Plato Dillon','amet.d12apibus@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FMUP','Desporto','Professor'),
  ('Cally Becker','tortor12@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FLUP','Desporto','Professor'),
  ('Hayfa Merrill','natoq12ue.penatibus@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FMDUP','Desporto','Professor'),
  ('Neville Pierce','dict12um.placerat@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FAUP','Desporto','Professor'),
  ('Nita Albert','non.arc12u.vivamus@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FCUP','Desporto','Student'),
  ('Joseph Walter','nequ13e@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FADEUP','Desporto','Professor'),
  ('Belle Stewart','condimentum.d12nec@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FMDUP','Desporto','Student'),
  ('Chase Valenzuela','vitae.puru12@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FEP','Desporto','Professor'),
  ('Marsden Suarez','lacus.quisqu12@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FAUP','Desporto','Student'),
  ('Branden Dominguez','tellus.se12@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FMUP','Desporto','Professor'),
  ('Aphrodite Barton','fusce.moll12s@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FMUP','Desporto','Student'),
  ('Idona Blevins','donec.digniss12m@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq','FEP','Desporto','Professor'),
  ('Marsden Vaughn','conubia.nost12a@up.pt','$2y$10$bP1sCOV1QE1QVmNoee5aRecWNEIi9DWTIemyn/CV16c00Ku4yYrQq,','FCUP','Desporto','Student');



INSERT INTO banned(id_user, id_moderator)
VALUES (9,35),
       (13,48),
       (19,22),
       (26,22),
       (30,45),
       (37,45),
       (41,47),
       (74,94),
       (98, 155);
       

INSERT INTO post(title, text, no_votes, id_user) VALUES 
   ('Cálculo material', 'Não estou a conseguir acompanhar a matéria de Cálculo, alguém tem material de anos anteriores?', 0, 7), --1
   ('Erro clion', 'Estou neste momento a ter uma cadeira de Programação e estou a ter problemas a instalar o Clion. Não consigo fazer o download da versão estudante.', 1, 7), --2
   ('Livro direito barato', 'Alguém sabe onde arranjar o livro de Introdução ao direito barato?', 0, 13), --3
   ('Praxe Fcup', 'Alguém já foi à praxe do Fcup? Como é?', 0, 39), --4
   ('Casa Cordoaria', 'Estou à procura de casa na zona da Cordoaria.', 0, 29), --5
   ('Melhor cantina UP', 'Qual a melhor cantina para comer na UP?', 1, 40), --6
   ('Congelei matricula', 'Congelei a matricula porque não estava a gostar do curso, mas agora que parei, estou indeciso e não sei bem o que fazer.', 0, 24), --7
   ('Mudar de curso tarde', 'Mudar de curso aos 23 é muito tarde?', 0, 42), --8
   ('Bolsa atrasada', 'Ainda não me responderam a dizer se vou ter bolsa ou não, as propinas já passaram do prazo e estão a aumentar o preço, o que faço?', 0, 21), --9
   ('Não estou a gostar do curso', 'Entrei este ano no curso de Bioquímica, mas não estou a gostar. Pensei que era isto que queria fazer para a vida, mas o que aprendemos aqui é muito diferente...', 0, 16), --10
   ('Repetir exames', 'Queria ir para medicina mas não tive média e entrei em dentária, mas não estou a gostar. Devo sair e estudar para repetir exames ou ficar e ver se ganho equivalências?', 2, 27), --11
   ('Rendas Porto', 'Vivo um bocado longe do Porto, mas as rendas estão a preços absurdos, tem sido mesmo difícil andar tanto de transporte todos os dias. O que faço?',1, 34), --12
   ('Assaltos', 'Têm havido muitas histórias de assaltos à noite, dicas para não ter confusões?', 0, 20), --13
   ('Geometria dificuldade', 'Alguém sabe se Geometria II na FAUP é assim tão complicado como dizem?', 0, 38), --14
   ('Anatomia não estou a conseguir', 'Anatomia está a ser muito dificil comparado ao nível de dificuldade do secundário...', 0, 20), --15
   ('Preço ginásio fadeup', 'Quanto custa o ginásio da FADEUP para alunos da UP? Queria fazer exercicio este semestre.', 0, 18), --16
    
    (NULL, 'Desinstala e volta a instalar, costuma funcionar.', 0, 37), --17
    (NULL, 'Na papelaria da D.Bia costuma ter muitos livros e material de cadeiras a preços académicos.', 0, 34), --18
    (NULL, 'A cantina da FEUP nunca desilude.', 0, 1), --19
    (NULL, 'Costumo ir a Letras e não é mau', 0, 23), --20
    (NULL, 'Qualquer resposta que não seja FMUP está errada.', 2, 47), --21
    (NULL, 'Não muito. Atrasam todos os anos. É triste mas é verdade.', 0, 22), --22
    (NULL, 'É lidar, para a próxima estuda', 1, 37); --23

INSERT INTO report (text, id_post, id_user) 
VALUES ('Misinformation', 1, 1);

  

INSERT INTO media (url, type, id_post)
VALUES ('http://dummyimage.com/243x100.png/cc0000/ffffff', 'Image', 3),     
      ('http://dummyimage.com/193x100.png/cc0000/ffffff', 'File', 8),
      ('http://dummyimage.com/114x100.png/5fa2dd/ffffff', 'File', 15),
      ('http://dummyimage.com/217x100.png/cc0000/ffffff', 'Image', 20),
      ('http://dummyimage.com/148x100.png/5fa2dd/ffffff', 'File', 22);



INSERT INTO topic (area, title)
VALUES ('Outros', 'Alojamentos'), --1
      ('Outros', 'Vida Académica'), -- 2
      ('Outros', 'Vida Noturna'), -- 3
      ('Outros', 'Bolsas de Estudo'), -- 4
      ('Engenharia','Informática'), -- 5
      ('Engenharia','Gestão Industrial'), -- 6
      ('Engenharia','Química'), -- 7
      ('Engenharia','Civil'), -- 8
      ('Engenharia','Eletrotécnica'), -- 9
      ('Engenharia','Física'), -- 10
      ('Saúde','Anatomia'), -- 11
      ('Saúde','Medicina geral'), -- 12
      ('Saúde','Fisioterapia'), -- 13
      ('Artes', 'Arquitetura'), -- 14
      ('Humanidades', 'Arqueologia'), -- 15
      ('Humanidades' , 'Direito'), -- 16
      ('Artes', 'Música'), -- 18
      ('Artes', 'Escultura'), -- 19
      ('Artes', 'Design'), -- 20
      ('Humanidades', 'História'), -- 21
      ('Humanidades', 'Geografia'), -- 22
      ('Economia', 'Gestão'), -- 23
      ('Economia', 'Contabilidade'), -- 24
      ('Saúde','Enfermagem'), -- 25
      ('Saúde','Dentária'), -- 26
      ('Saúde','Psicologia'); -- 27
      

INSERT INTO question(id_post, id_topic) VALUES
    (1, 1),
    (2, 5),
    (3, 16),
    (4, 2),
    (5, 1),
    (6, 2),
    (7, 2),
    (8, 2),
    (9, 4),
    (10, 2),
    (11, 2),
    (12, 1),
    (13, 7),
    (14, 14),
    (15, 11),
    (16, 2);
      
INSERT INTO answer(id_post, id_question) VALUES
    (17, 2),
    (18, 3),
    (19, 6),
    (20, 6),
    (21, 6),
    (22, 9),
    (23, 15);


INSERT INTO rating(id_post, id_user, rating) VALUES 
    (2, 2, TRUE),
    (6, 4, TRUE),
    (11, 7, TRUE),
    (11, 20, TRUE),
    (12, 22, TRUE),
    (21, 31, TRUE),
    (21, 32, TRUE),
    (23, 34, TRUE);


INSERT INTO bookmark (id_question, id_user)
VALUES (5, 7),
      (6, 15),
      (6, 22),
      (6, 38),
      (15, 18);

