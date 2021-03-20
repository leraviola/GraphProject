--
-- PostgreSQL database cluster dump
--

SET default_transaction_read_only = off;

SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;

--
-- Roles
--

CREATE ROLE postgres;
ALTER ROLE postgres WITH SUPERUSER INHERIT CREATEROLE CREATEDB LOGIN REPLICATION BYPASSRLS;






--
-- Databases
--

--
-- Database "template1" dump
--

\connect template1

--
-- PostgreSQL database dump
--

-- Dumped from database version 12.2
-- Dumped by pg_dump version 12.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- PostgreSQL database dump complete
--

--
-- Database "postgres" dump
--

\connect postgres

--
-- PostgreSQL database dump
--

-- Dumped from database version 12.2
-- Dumped by pg_dump version 12.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: edge; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.edge (
    id integer NOT NULL,
    vertex_id1 integer NOT NULL,
    vertex_id2 integer NOT NULL,
    weight integer NOT NULL
);


ALTER TABLE public.edge OWNER TO postgres;

--
-- Name: edge_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.edge_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.edge_id_seq OWNER TO postgres;

--
-- Name: edge_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.edge_id_seq OWNED BY public.edge.id;


--
-- Name: graph; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.graph (
    id integer NOT NULL,
    name character varying(40) NOT NULL
);


ALTER TABLE public.graph OWNER TO postgres;

--
-- Name: graph_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.graph_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.graph_id_seq OWNER TO postgres;

--
-- Name: graph_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.graph_id_seq OWNED BY public.graph.id;


--
-- Name: vertex; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vertex (
    id integer NOT NULL,
    graph_id integer NOT NULL,
    coordinate point
);


ALTER TABLE public.vertex OWNER TO postgres;

--
-- Name: vertex_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vertex_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.vertex_id_seq OWNER TO postgres;

--
-- Name: vertex_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vertex_id_seq OWNED BY public.vertex.id;


--
-- Name: edge id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.edge ALTER COLUMN id SET DEFAULT nextval('public.edge_id_seq'::regclass);


--
-- Name: graph id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.graph ALTER COLUMN id SET DEFAULT nextval('public.graph_id_seq'::regclass);


--
-- Name: vertex id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vertex ALTER COLUMN id SET DEFAULT nextval('public.vertex_id_seq'::regclass);


--
-- Data for Name: edge; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.edge (id, vertex_id1, vertex_id2, weight) FROM stdin;
1	1	2	5
2	2	1	10
7	6	7	8
8	1	8	13
9	2	9	5
10	8	9	5
12	9	8	11
\.


--
-- Data for Name: graph; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.graph (id, name) FROM stdin;
1	graph1
2	graph2
\.


--
-- Data for Name: vertex; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.vertex (id, graph_id, coordinate) FROM stdin;
1	1	(0,0)
2	1	(2,2)
6	2	(8,3)
7	2	(10,3)
8	1	(2,0)
9	1	(4,1)
\.


--
-- Name: edge_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.edge_id_seq', 14, true);


--
-- Name: graph_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.graph_id_seq', 4, true);


--
-- Name: vertex_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.vertex_id_seq', 11, true);


--
-- Name: edge pk_edge; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.edge
    ADD CONSTRAINT pk_edge PRIMARY KEY (id);


--
-- Name: graph pk_graph; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.graph
    ADD CONSTRAINT pk_graph PRIMARY KEY (id);


--
-- Name: vertex pk_vertex; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vertex
    ADD CONSTRAINT pk_vertex PRIMARY KEY (id);


--
-- Name: vertex fk_graph_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vertex
    ADD CONSTRAINT fk_graph_id FOREIGN KEY (graph_id) REFERENCES public.graph(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: edge fk_vertex_id1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.edge
    ADD CONSTRAINT fk_vertex_id1 FOREIGN KEY (vertex_id1) REFERENCES public.vertex(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: edge fk_vertex_id2; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.edge
    ADD CONSTRAINT fk_vertex_id2 FOREIGN KEY (vertex_id2) REFERENCES public.vertex(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database cluster dump complete
--

