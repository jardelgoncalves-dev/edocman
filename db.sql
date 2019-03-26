CREATE TABLE tags(
	id SERIAL PRIMARY KEY NOT NULL,
	name CHARACTER VARYING NOT NULL,
	created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE access_level(
	id SERIAL PRIMARY KEY NOT NULL,
	name CHARACTER VARYING NOT NULL,
	created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users(
	id SERIAL PRIMARY KEY NOT NULL,
	name CHARACTER VARYING NOT NULL,
	email CHARACTER VARYING NOT NULL,
	password CHARACTER VARYING NOT NULL,
	access_level_id INTEGER,
	FOREIGN KEY (access_level_id) REFERENCES access_level (id),
	created TIMESTAMP DEFAULT CURRENT_TIMESTAMP	
);

CREATE TABLE documents(
	id SERIAL PRIMARY KEY NOT NULL,
	filename CHARACTER VARYING NOT NULL,
	size REAL NOT NULL,
	author CHARACTER VARYING NOT NULL,
	tag_id INTEGER,
	FOREIGN KEY (tag_id) REFERENCES tags (id),
	created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


--- functions
CREATE FUNCTION f_auth_verify(CHARACTER VARYING, CHARACTER VARYING) RETURNS BOOL AS
$$
BEGIN
	IF EXISTS ( SELECT * FROM users WHERE email = $1 AND password = $2) THEN
		RETURN TRUE;
	ELSE
		RETURN FALSE;
	END IF;
END;
$$
LANGUAGE 'plpgsql';



CREATE FUNCTION f_users_findByEmail(CHARACTER VARYING) RETURNS SETOF users AS
$$
BEGIN
	RETURN QUERY
	SELECT * FROM users WHERE email = $1;
END;
$$
LANGUAGE 'plpgsql';


--- function users
CREATE OR REPLACE FUNCTION f_find_users() 
RETURNS TABLE(id INTEGER, name CHARACTER VARYING, email CHARACTER VARYING,
password CHARACTER VARYING, access_level CHARACTER VARYING, created TIMESTAMP) AS
$$
BEGIN

	RETURN QUERY
	SELECT u.id, u.name, u.email, u.password, a.name as access_level, u.created
	FROM users as u INNER JOIN access_level as a ON u.access_level_id = a.id;

END;
$$
LANGUAGE 'plpgsql';


CREATE FUNCTION f_delete_users(INTEGER) RETURNS BOOL AS
$$
BEGIN
	IF EXISTS (SELECT * FROM users WHERE id = $1) THEN
    DELETE FROM users WHERE id = $1;
    RETURN TRUE;
  ELSE
    RETURN FALSE;
  END IF;
END;
$$
LANGUAGE 'plpgsql';

CREATE FUNCTION f_save_users(
            CHARACTER VARYING, CHARACTER VARYING, CHARACTER VARYING, INTEGER
) RETURNS BOOL AS
$$
BEGIN
  INSERT INTO users (name, email, password, access_level_id)
  VALUES ($1, $2, $3, $4);
  RETURN TRUE;
END;
$$
LANGUAGE 'plpgsql';

CREATE FUNCTION f_find_users() RETURNS SETOF users AS
$$
BEGIN
	RETURN QUERY
	SELECT * FROM users;
END;
$$
LANGUAGE 'plpgsql';

CREATE FUNCTION f_users_findbyemail(CHARACTER VARYING) RETURNS SETOF users AS
$$
BEGIN
	RETURN QUERY
	SELECT * FROM users WHERE email = $1;
END;
$$
LANGUAGE 'plpgsql';


--- function tags
CREATE FUNCTION f_findById_tags(INTEGER) RETURNS SETOF tags AS
$$
BEGIN
	RETURN QUERY
	SELECT * FROM tags WHERE id = $1;
END;
$$
LANGUAGE 'plpgsql';


CREATE FUNCTION f_delete_tags(INTEGER) RETURNS BOOL AS
$$
BEGIN
	IF EXISTS (SELECT * FROM tags WHERE id = $1) THEN
    DELETE FROM tags WHERE id = $1;
    RETURN TRUE;
  ELSE
    RETURN FALSE;
  END IF;
END;
$$
LANGUAGE 'plpgsql';

CREATE FUNCTION f_save_tags(CHARACTER VARYING) RETURNS BOOL AS
$$
BEGIN
  INSERT INTO tags (name)
  VALUES ($1);
  RETURN TRUE;
END;
$$
LANGUAGE 'plpgsql';

CREATE FUNCTION f_find_tags() RETURNS SETOF tags AS
$$
BEGIN
	RETURN QUERY
	SELECT * FROM tags;
END;
$$
LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION f_info_tags() 
RETURNS TABLE (id INTEGER, name CHARACTER VARYING, total_size REAL,
total_files BIGINT, created TIMESTAMP) AS
$$
BEGIN
	RETURN QUERY
	SELECT t.id, t.name, sum(d.size) AS total_size, count(d.image) AS total_files, t.created
	FROM tags AS t INNER JOIN documents AS d ON d.tag_id = t.id GROUP BY t.id;

END;
$$
LANGUAGE 'plpgsql';




--- function documents
CREATE FUNCTION f_findById_documents(INTEGER) RETURNS SETOF documents AS
$$
BEGIN
	RETURN QUERY
	SELECT * FROM documents WHERE id = $1;
END;
$$
LANGUAGE 'plpgsql';


CREATE FUNCTION f_delete_documents(INTEGER) RETURNS BOOL AS
$$
BEGIN
	IF EXISTS (SELECT * FROM documents WHERE id = $1) THEN
    DELETE FROM documents WHERE id = $1;
    RETURN TRUE;
  ELSE
    RETURN FALSE;
  END IF;
END;
$$
LANGUAGE 'plpgsql';

CREATE FUNCTION f_save_documents(
	CHARACTER VARYING, REAL, CHARACTER VARYING, INTEGER, TEXT 
) RETURNS BOOL AS
$$
BEGIN
  INSERT INTO documents (filename, size, author, tag_id, image)
  VALUES ($1, $2, $3, $4, $5);
  RETURN TRUE;
END;
$$
LANGUAGE 'plpgsql';


CREATE FUNCTION f_find_documents() RETURNS SETOF documents AS
$$
BEGIN
	RETURN QUERY
	SELECT * FROM documents;
END;
$$
LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION find_documents_simple(CHARACTER VARYING) RETURNS SETOF documents AS
$$
BEGIN
	RETURN QUERY
	SELECT * FROM documents WHERE filename LIKE CONCAT('%', LOWER($1), '%') OR  author LIKE CONCAT('%', LOWER($1), '%');
		
END;
$$
LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION find_documentsByTagId(INTEGER) RETURNS SETOF documents AS
$$
BEGIN
	RETURN QUERY
	SELECT * FROM documents WHERE tag_id = $1;
		
END;
$$
LANGUAGE 'plpgsql';




--- AUTH
CREATE FUNCTION f_user_auth(CHARACTER VARYING, CHARACTER VARYING) RETURNS BOOL AS
$$
BEGIN
	IF EXISTS (SELECT * FROM users WHERE email = $1 AND password = $2) THEN
		RETURN TRUE;
	ELSE
		RETURN FALSE;
	END IF;
END;
$$
LANGUAGE 'plpgsql';



--- inner join function
CREATE OR REPLACE FUNCTION f_documents_ij_tags_all()
RETURNS TABLE (id INTEGER, filename CHARACTER VARYING, size REAL, tag_name CHARACTER VARYING) AS
$$
BEGIN
	RETURN QUERY
	SELECT d.id, d.filename, d.size, t.name as tag_name 
	FROM documents d INNER JOIN tags as t ON d.tag_id = t.id;
END;
$$
LANGUAGE 'plpgsql';


--- function access_level
CREATE OR REPLACE FUNCTION f_find_accesslevel() RETURNS SETOF access_level AS
$$
BEGIN
	RETURN QUERY
	SELECT * FROM access_level;
END;
$$
LANGUAGE 'plpgsql';



---inserts
insert into access_level (name) Values ('admin') 

--- hash password = 123
insert into users (name, email, password, access_level_id)
values ('Admin', 'admin@example.com', '202cb962ac59075b964b07152d234b70', 1);